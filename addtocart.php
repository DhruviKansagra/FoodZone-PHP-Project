<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}


$conn = new mysqli("localhost","root","","food");

if($conn->connect_error){
    die("Database Connection Failed : ".$conn->connect_error);
}


$user_id = $_SESSION['user_id'];


// =======================
// ADD TO CART
// =======================

if(isset($_GET['id'])){


    $f_id = intval($_GET['id']);


    if($f_id <= 0){
        echo "<script>
        alert('Invalid Food ID');
        window.location='menu.php';
        </script>";
        exit();
    }



    // Get food details

    $stmt = $conn->prepare(
        "SELECT f_id, food_name, price, image 
         FROM food 
         WHERE f_id=?"
    );


    $stmt->bind_param("i",$f_id);

    $stmt->execute();


    $result = $stmt->get_result();



    if($result->num_rows == 0){

        echo "<script>
        alert('Food item not found');
        window.location='menu.php';
        </script>";

        exit();

    }



    $food = $result->fetch_assoc();



    $food_name  = $food['food_name'];
    $food_price = $food['price'];
    $food_image = $food['image'];


    $quality = 1;
    $total_price = $food_price;



    // Check already in cart

    $check = $conn->prepare(
        "SELECT cart_id 
         FROM cart 
         WHERE user_id=? AND f_id=?"
    );


    $check->bind_param(
        "ii",
        $user_id,
        $f_id
    );


    $check->execute();


    $exist = $check->get_result();



    if($exist->num_rows > 0){


        // Increase quantity

        $update = $conn->prepare(
            "UPDATE cart
             SET 
             quality = quality + 1,
             total_price = food_price * quality
             WHERE user_id=? AND f_id=?"
        );


        $update->bind_param(
            "ii",
            $user_id,
            $f_id
        );


        $update->execute();



    }else{


        // Insert new cart item

        $insert = $conn->prepare(
            "INSERT INTO cart
            (
                user_id,
                f_id,
                food_name,
                food_price,
                quality,
                total_price,
                food_image
            )
            VALUES(?,?,?,?,?,?,?)"
        );


        $insert->bind_param(
            "iisdiis",
            $user_id,
            $f_id,
            $food_name,
            $food_price,
            $quality,
            $total_price,
            $food_image
        );


        $insert->execute();


    }



    echo "
    <script>
    alert('Food added to cart successfully');
    window.location='addtocart.php';
    </script>";

    exit();

}





// =======================
// CHECK CART EMPTY
// =======================


$cart_check = $conn->prepare(
    "SELECT * FROM cart WHERE user_id=?"
);


$cart_check->bind_param(
    "i",
    $user_id
);


$cart_check->execute();


$result = $cart_check->get_result();



if($result->num_rows == 0){


    echo "
    <script>
    alert('Your cart is empty');
    window.location='menu.php';
    </script>";

    exit();

}



// =======================
// INCREASE QUANTITY
// =======================

if(isset($_GET['action']) && $_GET['action']=="inc"){


    $cart_id = intval($_GET['cart_id']);


    $update = $conn->prepare(
        "UPDATE cart
         SET 
         quality = quality + 1,
         total_price = food_price * quality
         WHERE cart_id=? AND user_id=?"
    );


    $update->bind_param(
        "ii",
        $cart_id,
        $user_id
    );


    $update->execute();


    header("Location:addtocart.php");
    exit();

}




// =======================
// DECREASE QUANTITY
// =======================


if(isset($_GET['action']) && $_GET['action']=="dec"){


    $cart_id = intval($_GET['cart_id']);



    $check = $conn->prepare(
        "SELECT quality, food_price 
         FROM cart 
         WHERE cart_id=? AND user_id=?"
    );


    $check->bind_param(
        "ii",
        $cart_id,
        $user_id
    );


    $check->execute();


    $result = $check->get_result();



    if($result->num_rows > 0){


        $row = $result->fetch_assoc();



        if($row['quality'] > 1){


            $new_quality = $row['quality'] - 1;

            $new_total = $row['food_price'] * $new_quality;



            $update = $conn->prepare(
                "UPDATE cart
                 SET quality=?, total_price=?
                 WHERE cart_id=? AND user_id=?"
            );


            $update->bind_param(
                "idii",
                $new_quality,
                $new_total,
                $cart_id,
                $user_id
            );


            $update->execute();



        }else{


            $delete = $conn->prepare(
                "DELETE FROM cart 
                 WHERE cart_id=? AND user_id=?"
            );


            $delete->bind_param(
                "ii",
                $cart_id,
                $user_id
            );


            $delete->execute();


        }


    }


    header("Location:addtocart.php");
    exit();

}




// =======================
// DELETE CART ITEM
// =======================


if(isset($_GET['delete_id'])){


    $cart_id = intval($_GET['delete_id']);


    $delete = $conn->prepare(
        "DELETE FROM cart
         WHERE cart_id=? AND user_id=?"
    );


    $delete->bind_param(
        "ii",
        $cart_id,
        $user_id
    );


    $delete->execute();


    header("Location:addtocart.php");
    exit();

}
// =======================
// CART CHECKOUT
// =======================

if(isset($_GET['checkout'])){

    $_SESSION['order_type'] = "cart";

    header("Location: order_now.php");
    exit();

}

?><!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> FoodZone </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
      <img src="images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              FoodZone
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  mx-auto ">
              <li class="nav-item ">
                <a class="nav-link" href="index.php">Home </a>
              </li>
                </li> <li class="nav-item">
                <a class="nav-link" href="menu.php">Menu</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="about.php">About <span class="sr-only">(current)</span> </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a>
              </li>
            </ul>
            <div class="user_option">
              <a href="login.php" class="user_link">
                <i class="fa fa-user" aria-hidden="true"></i>
              </a>
              <a class="cart_link" href="addtocart.php">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                  <g>
                    <g>
                      <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                   c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                   C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                   c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                   C457.728,97.71,450.56,86.958,439.296,84.91z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                   c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                    </g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                </svg>
              </a>
              <form class="form-inline">
                </form>
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>

<!-- Shopping Cart Section -->
<div class="container py-5 my-5" style="min-height: 70vh;">

    <!-- Page Heading -->
    <div class="text-center pt-3 mb-5">
        <h2 class="font-weight-bold mb-2">Your Food Cart</h2>
        <p class="text-muted mb-0">
            Review your items before placing your order
        </p>
    </div>

    <?php

    $sql = "SELECT * FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $grand_total = 0;

    if ($result->num_rows > 0):

    ?>

    <!-- Cart Table Card -->
    <div class="card shadow-sm mb-5">

        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0">
                <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i>
                Cart Items
            </h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center mb-0">

                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Food Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($row = $result->fetch_assoc()):

                            $quantity = (int)$row['quality'];
                            $item_price = (float)$row['food_price'];
                            $total = $item_price * $quantity;
                            $grand_total += $total;

                        ?>

                        <tr>

                            <!-- Food Image -->
                            <td class="align-middle p-3">

                                <img
                                    src="images/<?= htmlspecialchars($row['food_image'], ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?= htmlspecialchars($row['food_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                    width="110"
                                    height="100"
                                    class="rounded shadow-sm"
                                    style="object-fit: cover;"
                                    onerror="this.onerror=null;this.src='images/no-image.png';">

                            </td>

                            <!-- Food Name -->
                            <td class="align-middle font-weight-bold">
                                <?= htmlspecialchars($row['food_name']); ?>
                            </td>

                            <!-- Price -->
                            <td class="align-middle text-nowrap">
                                ₹<?= number_format($item_price, 2); ?>
                            </td>

                            <!-- Quantity Controls -->
                            <td class="align-middle text-nowrap">

                                <a
                                    href="addtocart.php?action=dec&amp;cart_id=<?= (int)$row['cart_id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    aria-label="Decrease quantity"
                                    title="Decrease quantity">
                                    &minus;
                                </a>

                                <span class="d-inline-block mx-2 font-weight-bold">
                                    <?= $quantity; ?>
                                </span>

                                <a
                                    href="addtocart.php?action=inc&amp;cart_id=<?= (int)$row['cart_id']; ?>"
                                    class="btn btn-success btn-sm"
                                    aria-label="Increase quantity"
                                    title="Increase quantity">
                                    +
                                </a>

                            </td>

                            <!-- Item Total -->
                            <td class="align-middle font-weight-bold text-nowrap">
                                ₹<?= number_format($total, 2); ?>
                            </td>

                            <!-- Delete Item -->
                            <td class="align-middle">

                                <a
                                    href="addtocart.php?delete_id=<?= (int)$row['cart_id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to remove this item from your cart?');">

                                    <i class="fa fa-trash mr-1" aria-hidden="true"></i>
                                    Delete

                                </a>

                            </td>

                        </tr>

                        <?php endwhile; ?>

                        <!-- Grand Total -->
                        <tr class="bg-light">

                            <td colspan="4" class="text-right align-middle p-4">
                                <h5 class="font-weight-bold mb-0">
                                    Grand Total
                                </h5>
                            </td>

                            <td colspan="2" class="text-center align-middle p-4">
                                <h5 class="text-success font-weight-bold mb-0">
                                    ₹<?= number_format($grand_total, 2); ?>
                                </h5>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Cart Action Buttons -->
    <div class="text-center mt-5 mb-4">

        <a href="menu.php" class="btn btn-primary btn-lg mr-2 mb-3">

            <i class="fa fa-arrow-left mr-1" aria-hidden="true"></i>
            Continue Shopping

        </a>

        <a href="order_now.php?cart=1" class="btn btn-success btn-lg mb-3">

            <i class="fa fa-shopping-bag mr-1" aria-hidden="true"></i>
            Proceed to Order

        </a>

    </div>

    <?php else: ?>

    <!-- Empty Cart -->
    <div class="card shadow-sm text-center my-5">

        <div class="card-body py-5">

            <i class="fa fa-shopping-cart fa-3x text-muted mb-4"
               aria-hidden="true"></i>

            <h3 class="font-weight-bold mb-3">
                Your Cart Is Empty
            </h3>

            <p class="text-muted mb-4">
                You haven't added any delicious food to your cart yet.
            </p>

            <a href="menu.php" class="btn btn-primary btn-lg">

                <i class="fa fa-cutlery mr-2" aria-hidden="true"></i>
                Explore Our Menu

            </a>

        </div>

    </div>

    <?php endif; ?>

</div>

<!-- Keep your existing Footer Section below -->
<!-- Keep your existing Footer Section below --></div>
<footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>
              Contact Us
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Location
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call +01 1234567890
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  foodzone@gmail.com
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="" class="footer-logo">
              FoodZone
            </a>
            <p>
FoodZone offers delicious fast food made with quality ingredients. Enjoy tasty meals, quick service, and a great food experience for every occasion.          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>
            Opening Hours
          </h4>
          <p>
            Everyday
          </p>
          <p>
            10.00 Am -10.00 Pm
          </p>
        </div>
      </div>
      <div class="footer-info">
        <p>
           <span id=""></span> All time yummy food
          <a href="https://html.design/">by FoodZone</a><br><br>
          <span id="displayYear"></span> Visit Our
          <a href="" target="_blank">FoodZone</a>
        </p>
      </div>
    </div>
  </footer>
  <!-- footer section -->
  <!-- jQery -->
  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->

</body>

</html>
