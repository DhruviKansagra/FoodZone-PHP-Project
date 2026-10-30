<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "food");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Database connection failed.");
}

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$food_items = [];
$is_cart_order = isset($_GET['cart']);

// Direct Buy Now
if (isset($_GET['f_id'])) {
    $f_id = filter_input(INPUT_GET, 'f_id', FILTER_VALIDATE_INT);

    if (!$f_id || $f_id <= 0) {
        die("Invalid food item.");
    }

    $stmt = $conn->prepare("SELECT f_id, food_name, price, image FROM food WHERE f_id = ?");
    $stmt->bind_param("i", $f_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $food = $result->fetch_assoc();
    $stmt->close();

    if (!$food) {
        die("Food not found.");
    }

    $food_items[] = [
        "f_id" => (int) $food['f_id'],
        "food_name" => $food['food_name'],
        "food_price" => (float) $food['price'],
        "food_image" => $food['image'],
        "quantity" => 1
    ];
} elseif ($is_cart_order) {
    $stmt = $conn->prepare("SELECT f_id, food_name, food_price, food_image, quality FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $food_items[] = [
            "f_id" => (int) $row['f_id'],
            "food_name" => $row['food_name'],
            "food_price" => (float) $row['food_price'],
            "food_image" => $row['food_image'],
            "quantity" => max(1, (int) $row['quality'])
        ];
    }
    $stmt->close();

    if (empty($food_items)) {
        die("Your cart is empty. <a href='menu.php'>Browse menu</a>");
    }
} else {
    die("Invalid request. Please select a food item or order from your cart.");
}

// Place order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // This page currently displays Cash on Delivery, so save the same method.
    $payment_method = 'Cash On Delivery';

    if ($name === '' || mb_strlen($name) > 100) {
        die("Please enter a valid name (maximum 100 characters).");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
        die("Please enter a valid email address.");
    }
    if ($address === '' || mb_strlen($address) > 500) {
        die("Please enter a valid delivery address (maximum 500 characters).");
    }
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        die("Please enter a valid 10-digit mobile number.");
    }
    if (empty($food_items)) {
        die("No food items selected.");
    }

    $conn->begin_transaction();

    try {
        // Insert each food item as an order row. Use the first inserted order ID
        // for the shipping record so it is a real order ID, never 0.
        $first_order_id = 0;

        $insert = $conn->prepare(
            "INSERT INTO orders
                (user_id, f_id, quantity, food_price, payment_method, food_name, food_image)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        foreach ($food_items as $item) {
            $item_f_id = (int) $item['f_id'];
            $quantity = (int) $item['quantity'];
            $food_price = (float) $item['food_price'];
            $food_name = (string) $item['food_name'];
            $food_image = (string) $item['food_image'];

            $insert->bind_param(
                "iiidsss",
                $user_id,
                $item_f_id,
                $quantity,
                $food_price,
                $payment_method,
                $food_name,
                $food_image
            );
            $insert->execute();

            $new_order_id = (int) $conn->insert_id;
            if ($new_order_id <= 0) {
                throw new RuntimeException("Could not generate a valid order ID.");
            }
            if ($first_order_id === 0) {
                $first_order_id = $new_order_id;
            }
        }
        $insert->close();

        if ($first_order_id <= 0) {
            throw new RuntimeException("Order was not created.");
        }

        // Save one shipping record linked to a valid order row.
        $shipping = $conn->prepare(
            "INSERT INTO shipping (user_id, order_id, name, email, address, phone)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $shipping->bind_param(
            "iissss",
            $user_id,
            $first_order_id,
            $name,
            $email,
            $address,
            $phone
        );
        $shipping->execute();
        $shipping->close();

        // Empty cart only after order and shipping inserts succeed.
        if ($is_cart_order) {
            $clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $clear->bind_param("i", $user_id);
            $clear->execute();
            $clear->close();
        }

        $conn->commit();
        header("Location: thankyou.php");
        exit();
    } catch (Throwable $e) {
        $conn->rollback();
        error_log("FoodZone order error: " . $e->getMessage());
        http_response_code(500);
        die("Order could not be placed. Please check your database tables and try again. Details: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}

$grand_total = 0;
foreach ($food_items as $item) {
    $grand_total += (float) $item['food_price'] * (int) $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FoodZone - Your Orders</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
</head>
<body class="sub_page">
<div class="hero_area">
    <div class="bg-box"><img src="images/hero-bg.jpg" alt=""></div>
    <header class="header_section">
        <div class="container">
            <nav class="navbar navbar-expand-lg custom_nav-container">
                <a class="navbar-brand" href="index.php"><span>FoodZone</span></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    </ul>
                    <div class="user_option">
                        <a href="login.php" class="user_link"><i class="fa fa-user" aria-hidden="true"></i></a>
                        <a class="cart_link" href="addtocart.php" aria-label="Shopping cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                    </div>
                </div>
            </nav>
        </div>
    </header>
</div>

<div class="container py-5 my-5" style="min-height:70vh">
    <div class="text-center pt-3 mb-5">
        <h2 class="font-weight-bold mb-2">Your Orders</h2>
        <p class="text-muted mb-0">Review your order and enter your delivery details</p>
    </div>

    <?php if (!empty($food_items)): ?>
    <form method="POST" action="" id="orderForm">
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-dark text-white py-3"><h5 class="mb-0"><i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i>Order Summary</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center mb-0">
                        <thead class="thead-dark"><tr><th>Food</th><th>Image</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead>
                        <tbody>
                        <?php foreach ($food_items as $item):
                            $item_price = (float) $item['food_price'];
                            $quantity = (int) $item['quantity'];
                            $item_total = $item_price * $quantity;
                        ?>
                            <tr>
                                <td class="align-middle font-weight-bold"><?= htmlspecialchars($item['food_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="align-middle p-3"><img src="images/<?= htmlspecialchars($item['food_image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($item['food_name'], ENT_QUOTES, 'UTF-8'); ?>" width="100" height="90" class="rounded shadow-sm" style="object-fit:cover" onerror="this.onerror=null;this.src='images/no-image.png';"></td>
                                <td class="align-middle text-nowrap">₹<?= number_format($item_price, 2); ?></td>
                                <td class="align-middle"><?= $quantity; ?></td>
                                <td class="align-middle font-weight-bold text-nowrap">₹<?= number_format($item_total, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                            <tr class="bg-light">
                                <td colspan="4" class="text-right align-middle p-3"><h5 class="font-weight-bold mb-0">Grand Total</h5></td>
                                <td class="align-middle p-3"><h5 class="text-success font-weight-bold mb-0">₹<?= number_format($grand_total, 2); ?></h5></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-dark text-white py-3"><h5 class="mb-0"><i class="fa fa-user mr-2" aria-hidden="true"></i>Customer &amp; Delivery Details</h5></div>
            <div class="card-body p-4 p-md-5">
                <div class="form-row">
                    <div class="form-group col-md-6 mb-4"><label for="name" class="font-weight-bold">Full Name</label><input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="Enter your full name" maxlength="100" autocomplete="name" required></div>
                    <div class="form-group col-md-6 mb-4"><label for="email" class="font-weight-bold">Email Address</label><input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Enter your email" maxlength="150" autocomplete="email" required></div>
                    <div class="form-group col-md-6 mb-4"><label for="phone" class="font-weight-bold">Mobile Number</label><input type="tel" name="phone" id="phone" class="form-control form-control-lg" placeholder="Enter 10-digit mobile number" maxlength="10" pattern="[0-9]{10}" inputmode="numeric" autocomplete="tel" title="Enter a 10-digit mobile number" required></div>
                    <div class="form-group col-md-6 mb-4"><label for="address" class="font-weight-bold">Delivery Address</label><textarea name="address" id="address" class="form-control" rows="3" maxlength="500" placeholder="Enter your complete delivery address" autocomplete="street-address" required></textarea></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-dark text-white py-3"><h5 class="mb-0"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i>Payment</h5></div>
            <div class="card-body p-4 p-md-5">
                <div class="alert alert-success">
                    <h5 class="mb-2"><i class="fa fa-money mr-2" aria-hidden="true"></i>Cash On Delivery</h5>
                    <p class="mb-0">Pay for your order when it is delivered.</p>
                </div>
                <div class="alert alert-info mt-4 mb-0"><div class="d-flex justify-content-between align-items-center"><span class="font-weight-bold">Amount to Pay</span><h4 class="text-success font-weight-bold mb-0">₹<?= number_format($grand_total, 2); ?></h4></div></div>
                <button type="submit" name="place_order" value="1" class="btn btn-success btn-lg btn-block mt-4"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Place Order</button>
                <div class="text-center mt-3"><a href="menu.php" class="text-primary"><i class="fa fa-arrow-left mr-1" aria-hidden="true"></i>Continue Shopping</a></div>
            </div>
        </div>
    </form>
    <?php else: ?>
        <div class="card shadow-sm text-center my-5"><div class="card-body py-5"><i class="fa fa-shopping-cart fa-3x text-muted mb-4" aria-hidden="true"></i><h3 class="font-weight-bold mb-3">No Food Selected</h3><p class="text-muted mb-4">Your order is empty. Explore our menu to select your favorite food.</p><a href="menu.php" class="btn btn-primary btn-lg"><i class="fa fa-cutlery mr-2" aria-hidden="true"></i>Browse Menu</a></div></div>
    <?php endif; ?>
</div>

<footer class="footer_section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 footer-col"><div class="footer_contact"><h4>Contact Us</h4><div class="contact_link_box"><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>Location</span></a><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>Call +01 1234567890</span></a><a href="mailto:foodzone@gmail.com"><i class="fa fa-envelope" aria-hidden="true"></i><span>foodzone@gmail.com</span></a></div></div></div>
            <div class="col-md-4 footer-col"><div class="footer_detail"><a href="index.php" class="footer-logo">FoodZone</a><p>FoodZone offers delicious fast food made with quality ingredients. Enjoy tasty meals, quick service, and a great food experience for every occasion.</p></div></div>
            <div class="col-md-4 footer-col"><h4>Opening Hours</h4><p>Everyday</p><p>10.00 Am - 10.00 Pm</p></div>
        </div>
        <div class="footer-info"><p>All time yummy food<br><span id="displayYear"></span> Visit Our <a href="index.php">FoodZone</a></p></div>
    </div>
</footer>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
