<?php
include("connected.php");


// Get food id

$f_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if($f_id <= 0){

    echo "Invalid food item.";
    exit();

}


// Fetch food details

$query = "SELECT * FROM food WHERE f_id=$f_id LIMIT 1";

$result = mysqli_query($conn,$query);



if(!$result || mysqli_num_rows($result)==0){

    echo "Food not found.";
    exit();

}


$food = mysqli_fetch_assoc($result);


// Prepare values

$safeName = htmlspecialchars($food['food_name']);


$imgFile = !empty($food['image']) 
        ? "images/".$food['image'] 
        : "images/no-image.png";


$price = number_format($food['price'],2);



$description = !empty($food['description']) 
        ? nl2br(htmlspecialchars($food['description']))
        : "<em>No description available.</em>";

?>
<!DOCTYPE html>
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

  <!-- Header Section -->
  <div class="hero_area">
    <div class="bg-box">
      <img src="images/hero-bg.jpg" alt="">
    </div>

    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container">

          <a class="navbar-brand" href="index.php">
            <span>FoodZone</span>
          </a>

          <button class="navbar-toggler"
                  type="button"
                  data-toggle="collapse"
                  data-target="#navbarSupportedContent"
                  aria-controls="navbarSupportedContent"
                  aria-expanded="false"
                  aria-label="Toggle navigation">
            <span></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mx-auto">

              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="menu.php">Menu</a>
              </li>

              <li class="nav-item active">
                <a class="nav-link" href="about.php">
                  About <span class="sr-only">(current)</span>
                </a>
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
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 456.029 456.029"
                     width="20"
                     height="20"
                     fill="currentColor"
                     aria-label="Shopping cart">
                  <path d="M345.6 338.862c-29.184 0-53.248 23.552-53.248 53.248
                  0 29.184 23.552 53.248 53.248 53.248
                  29.184 0 53.248-23.552 53.248-53.248
                  0-29.184-23.552-53.248-53.248-53.248z"/>
                  <path d="M439.296 84.91c-1.024 0-2.56-.512-4.096-.512H112.64
                  l-5.12-34.304C104.448 27.566 84.992 10.67
                  61.952 10.67H20.48C9.216 10.67 0 19.886 0 31.15
                  c0 11.264 9.216 20.48 20.48 20.48h41.472
                  c2.56 0 4.608 2.048 5.12 4.608l31.744 216.064
                  c4.096 27.136 27.648 47.616 55.296 47.616h212.992
                  c26.624 0 49.664-18.944 55.296-45.056l33.28-166.4
                  c2.048-10.752-5.12-21.504-16.384-23.552z"/>
                  <path d="M215.04 389.55c-1.024-28.16-24.576-50.688-52.736-50.688
                  -29.696 1.536-52.224 26.112-51.2 55.296
                  1.024 28.16 24.064 50.688 52.224 50.688h1.024
                  c29.184-1.536 51.2-26.112 50.688-55.296z"/>
                </svg>
              </a>

            </div>
          </div>
        </nav>
      </div>
    </header>
  </div>


<!-- Food Details Section -->

<!-- Food Details Section -->
<section class="container my-5 py-5" style="min-height: 70vh;">

    <!-- Go Back Button -->
    <div class="mb-5">
        <a href="javascript:history.back()"
           class="btn btn-secondary">

            <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>
            Go Back

        </a>
    </div>

    <!-- Food Details Card -->
    <div class="card shadow-sm border-0 mb-5">

        <div class="card-body p-4 p-lg-5">

            <div class="row align-items-center">

                <!-- Food Image -->
                <div class="col-lg-6 col-md-6 text-center mb-5 mb-md-0">

                    <img
                        src="<?= htmlspecialchars($imgFile, ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?= $safeName; ?>"
                        class="img-fluid rounded shadow"
                        style="width: 100%; max-width: 500px; height: 500px; object-fit: cover;"
                        onerror="this.onerror=null;this.src='images/no-image.png';">

                </div>

                <!-- Food Information -->
                <div class="col-lg-6 col-md-6 px-lg-5">

                    <h2 class="font-weight-bold mb-4">
                        <?= $safeName; ?>
                    </h2>

                    <h3 class="text-success font-weight-bold mb-4">
                        ₹<?= $price; ?>
                    </h3>

                    <!-- Description -->
                    <div class="mb-5">

                       
                        <p class="text-muted lead">
                            <?= $description; ?>
                        </p>

                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap align-items-center">

                        <form action="addtocart.php"
                              method="GET"
                              class="mr-3 mb-3">

                            <input type="hidden"
                                   name="id"
                                   value="<?= (int)$food['f_id']; ?>">

                            <button type="submit"
                                    class="btn btn-primary btn-lg">

                                <i class="fa fa-shopping-cart mr-2"
                                   aria-hidden="true"></i>
                                Add to Cart

                            </button>

                        </form>

                        <a href="order_now.php?f_id=<?= (int)$food['f_id']; ?>"
                           class="btn btn-success btn-lg mb-3">

                            <i class="fa fa-bolt mr-2"
                               aria-hidden="true"></i>
                            Buy Now

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- Keep your existing Footer Section here -->
<!-- footer section -->
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