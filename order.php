<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli('localhost', 'root', '', 'food');
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    http_response_code(500);
    die('Database connection failed. Check that MySQL is running and the database settings are correct.');
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = (int) $_SESSION['user_id'];

$food_items = [];
$is_cart_order = isset($_GET['cart']);

try {
    if (isset($_GET['f_id'])) {
        $f_id = filter_input(INPUT_GET, 'f_id', FILTER_VALIDATE_INT);
        if (!$f_id || $f_id < 1) {
            die('Invalid food ID.');
        }
        $stmt = $conn->prepare('SELECT f_id, food_name, price, image FROM food WHERE f_id = ?');
        $stmt->bind_param('i', $f_id);
        $stmt->execute();
        $food = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$food) {
            die('Food not found.');
        }
        $food_items[] = [
            'f_id' => (int) $food['f_id'],
            'food_name' => $food['food_name'],
            'food_price' => (float) $food['price'],
            'food_image' => $food['image'],
            'quantity' => 1
        ];
    } elseif ($is_cart_order) {
        $stmt = $conn->prepare('SELECT f_id, food_name, food_price, food_image, quality FROM cart WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();
        while ($row = $cart_result->fetch_assoc()) {
            $qty = (int) $row['quality'];
            if ($qty < 1) continue;
            $food_items[] = [
                'f_id' => (int) $row['f_id'],
                'food_name' => $row['food_name'],
                'food_price' => (float) $row['food_price'],
                'food_image' => $row['food_image'],
                'quantity' => $qty
            ];
        }
        $stmt->close();
    } else {
        die('Invalid request. Please select food from the menu or open your cart.');
    }
} catch (Throwable $e) {
    die('Could not load food items. Please check your cart and database column names.');
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    $allowed_payments = ['Cash On Delivery', 'Bank Transfer', 'Google Pay', 'UPI'];

    if (empty($food_items)) {
        $error_message = 'Your cart is empty. Please select food before placing an order.';
    } elseif ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $address === '' || !preg_match('/^[0-9]{10}$/', $phone)) {
        $error_message = 'Please enter a name, valid email, complete address, and 10-digit mobile number.';
    } elseif (!in_array($payment_method, $allowed_payments, true)) {
        $error_message = 'Please select a valid payment method.';
    } else {
        try {
            $conn->begin_transaction();
            $last_order_id = 0;

            $insert_order = $conn->prepare('INSERT INTO orders (user_id, f_id, quantity, food_price, payment_method, food_name, food_image) VALUES (?, ?, ?, ?, ?, ?, ?)');
            foreach ($food_items as $item) {
                $item_f_id = (int) $item['f_id'];
                $quantity = (int) $item['quantity'];
                $price = (float) $item['food_price'];
                $food_name = (string) $item['food_name'];
                $food_image = (string) $item['food_image'];
                $insert_order->bind_param('iiidsss', $user_id, $item_f_id, $quantity, $price, $payment_method, $food_name, $food_image);
                $insert_order->execute();
                $last_order_id = (int) $conn->insert_id;
                if ($last_order_id < 1) {
                    throw new RuntimeException('A valid order ID was not generated.');
                }
            }
            $insert_order->close();

            if ($last_order_id < 1) {
                throw new RuntimeException('No order was created.');
            }

            $insert_shipping = $conn->prepare('INSERT INTO shipping (user_id, order_id, name, email, address, phone) VALUES (?, ?, ?, ?, ?, ?)');
            $insert_shipping->bind_param('iissss', $user_id, $last_order_id, $name, $email, $address, $phone);
            $insert_shipping->execute();
            $insert_shipping->close();

            if ($is_cart_order) {
                $clear_cart = $conn->prepare('DELETE FROM cart WHERE user_id = ?');
                $clear_cart->bind_param('i', $user_id);
                $clear_cart->execute();
                $clear_cart->close();
            }

            $conn->commit();
            header('Location: thankyou.php');
            exit;
        } catch (Throwable $e) {
            try { $conn->rollback(); } catch (Throwable $ignored) {}
            $error_message = 'Order could not be placed. Details: ' . $e->getMessage();
        }
    }
}

$grand_total = 0.0;
$total_quantity = 0;
foreach ($food_items as $item) {
    $grand_total += (float) $item['food_price'] * (int) $item['quantity'];
    $total_quantity += (int) $item['quantity'];
}
function h($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FoodZone - Your Orders</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/responsive.css">
  <style>
    .order-page { min-height:70vh; }
    .food-thumb { width:90px; height:80px; object-fit:cover; border-radius:8px; }
    .payment-form { display:none; }
  </style>
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

<div class="container py-5 order-page">
  <div class="text-center mb-5">
    <h2 class="font-weight-bold">Your Orders</h2>
    <p class="text-muted">Review your food and enter your delivery details.</p>
  </div>

  <?php if ($error_message !== ''): ?>
    <div class="alert alert-danger"><?= h($error_message) ?></div>
  <?php endif; ?>

  <?php if (!empty($food_items)): ?>
    <div class="card shadow-sm mb-5">
      <div class="card-header bg-dark text-white"><h5 class="mb-0">Order Summary</h5></div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-hover text-center mb-0">
          <thead class="thead-dark"><tr><th>Food</th><th>Image</th><th>Price (₹)</th><th>Qty</th><th>Total (₹)</th></tr></thead>
          <tbody>
          <?php foreach ($food_items as $item): ?>
            <tr>
              <td class="align-middle font-weight-bold"><?= h($item['food_name']) ?></td>
              <td class="align-middle"><img class="food-thumb" src="images/<?= h($item['food_image']) ?>" alt="<?= h($item['food_name']) ?>" onerror="this.onerror=null;this.src='images/no-image.png';"></td>
              <td class="align-middle">₹<?= number_format((float) $item['food_price'], 2) ?></td>
              <td class="align-middle"><?= (int) $item['quantity'] ?></td>
              <td class="align-middle">₹<?= number_format((float) $item['food_price'] * (int) $item['quantity'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
            <tr class="bg-light font-weight-bold"><td colspan="3" class="text-right">Grand Total</td><td><?= $total_quantity ?></td><td>₹<?= number_format($grand_total, 2) ?></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <form method="post" action="" class="mb-5" id="orderForm">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white"><h5 class="mb-0">Customer &amp; Delivery Details</h5></div>
        <div class="card-body p-4">
          <div class="form-row">
            <div class="form-group col-md-6"><label for="name">Full Name</label><input class="form-control" type="text" id="name" name="name" maxlength="100" autocomplete="name" required value="<?= h($_POST['name'] ?? '') ?>"></div>
            <div class="form-group col-md-6"><label for="email">Email Address</label><input class="form-control" type="email" id="email" name="email" maxlength="150" autocomplete="email" required value="<?= h($_POST['email'] ?? '') ?>"></div>
            <div class="form-group col-md-6"><label for="phone">Mobile Number</label><input class="form-control" type="tel" id="phone" name="phone" pattern="[0-9]{10}" maxlength="10" inputmode="numeric" autocomplete="tel" required value="<?= h($_POST['phone'] ?? '') ?>"></div>
            <div class="form-group col-md-6"><label for="address">Delivery Address</label><textarea class="form-control" id="address" name="address" rows="3" maxlength="500" autocomplete="street-address" required><?= h($_POST['address'] ?? '') ?></textarea></div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white"><h5 class="mb-0">Payment Information</h5></div>
        <div class="card-body p-4">
          <div class="form-group"><label for="payment_method">Payment Method</label>
            <select class="form-control" name="payment_method" id="payment_method" onchange="togglePaymentForms()" required>
              <option value="">Select Payment Method</option>
              <?php foreach (['Cash On Delivery','Bank Transfer','Google Pay','UPI'] as $method): ?>
                <option value="<?= h($method) ?>" <?= (($_POST['payment_method'] ?? '') === $method) ? 'selected' : '' ?>><?= h($method) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div id="bank_form" class="payment-form alert alert-light"><strong>Bank Transfer</strong><p class="mb-0">Follow the payment instructions provided by FoodZone. This demo form does not process online payments.</p></div>
          <div id="gpay_form" class="payment-form text-center"><h5>Google Pay</h5><?php if (file_exists(__DIR__ . '/images/gpay.png')): ?><img src="images/gpay.png" width="180" height="180" alt="Google Pay QR code"><?php endif; ?><p>Only pay using verified FoodZone payment details.</p></div>
          <div id="upi_form" class="payment-form"><label for="upi_id">UPI ID (optional)</label><input type="text" id="upi_id" name="upi_id" class="form-control" placeholder="Enter UPI ID"></div>
          <div class="alert alert-info mt-4 mb-0 d-flex justify-content-between"><strong>Amount to Pay</strong><strong>₹<?= number_format($grand_total, 2) ?></strong></div>
          <button type="submit" name="place_order" value="1" class="btn btn-success btn-lg btn-block mt-4"><i class="fa fa-check-circle mr-2"></i>Place Order</button>
          <div class="text-center mt-3"><a href="menu.php">Continue Shopping</a></div>
        </div>
      </div>
    </form>
  <?php else: ?>
    <div class="card shadow-sm text-center"><div class="card-body py-5"><h3>No Food Selected</h3><p class="text-muted">Your cart is empty. Explore the menu to select food.</p><a href="menu.php" class="btn btn-primary">Browse Menu</a></div></div>
  <?php endif; ?>
</div>

<footer class="footer_section"><div class="container"><div class="footer-info text-center"><p>FoodZone — All time yummy food<br><span id="displayYear"></span></p></div></div></footer>
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<script>
function togglePaymentForms() {
  var method = document.getElementById('payment_method').value;
  document.getElementById('bank_form').style.display = method === 'Bank Transfer' ? 'block' : 'none';
  document.getElementById('gpay_form').style.display = method === 'Google Pay' ? 'block' : 'none';
  document.getElementById('upi_form').style.display = method === 'UPI' ? 'block' : 'none';
}
togglePaymentForms();
var year = document.getElementById('displayYear');
if (year) year.textContent = new Date().getFullYear();
</script>
</body>
</html>
