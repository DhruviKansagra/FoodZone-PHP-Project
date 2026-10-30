

<?php
include("connected.php");

/* FETCH CATEGORIES */
$categories_query = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = mysqli_query($conn, $categories_query);

if (!$categories_result) {
    die("Category Error: " . mysqli_error($conn));
}


/* ADD FOOD */
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $c_id = intval($_POST['c_id']);
    $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);

    $image_name = "";


    /* IMAGE UPLOAD */
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $image_name = time() . "_" . basename($_FILES['image']['name']);

        // image folder path
        $upload_path = "../images/";

        // create folder if not exists
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }


        $target = $upload_path . $image_name;


        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("Image Upload Failed");
        }
    }


    /* INSERT FOOD DATA */
    $query = "INSERT INTO food
              (c_id, food_name, description, price, image)
              VALUES
              ('$c_id',
               '$food_name',
               '$description',
               '$price',
               '$image_name')";


    $result = mysqli_query($conn, $query);


    if ($result) {

        echo "
        <script>
            alert('Food Added Successfully');
            window.location.href='foods.php';
        </script>";

    } else {

        die("Database Insert Error: " . mysqli_error($conn));

    }

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
               <li class="nav-item active">
                <a class="nav-link" href="admin.php">Home </a>
              </li>
               <li class="nav-item">
                <a class="nav-link" href="user.php">User</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="category.php">Category</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="foods.php">Foods<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="shipping.php">Orders</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="feedback.php">Feedback</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-danger" href="../login.php">Logout</a>
              </li>

            </ul>
            <div class="user_option">
              
              <a class="cart_link" href="#">
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


<!-- Add Foods Section -->
<div class="container py-5 my-5" style="min-height: 70vh;">

    <!-- Page Heading -->
    <div class="text-center pt-3 mb-5">
        <h2 class="font-weight-bold mb-2">Add Foods</h2>
        <p class="text-muted mb-0">
            Add a new delicious food item to FoodZone
        </p>
    </div>

    <!-- Food Form -->
    <div class="row justify-content-center">

        <div class="col-md-9 col-lg-7">

            <div class="card shadow-sm mb-5">

                <!-- Card Header -->
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0">
                        <i class="fa fa-cutlery mr-2" aria-hidden="true"></i>
                        Food Information
                    </h5>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4 p-md-5">

                    <form action="" method="POST" enctype="multipart/form-data">

                        <!-- Category -->
                        <div class="form-group mb-4">

                            <label for="category" class="font-weight-bold">
                                Category
                            </label>

                            <select name="c_id"
                                    id="category"
                                    class="form-control form-control-lg"
                                    required>

                                <option value="" selected disabled>
                                    Select category
                                </option>

                                <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>

                                    <option value="<?= (int)$cat['c_id']; ?>">
                                        <?= htmlspecialchars($cat['name']); ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- Food Name -->
                        <div class="form-group mb-4">

                            <label for="foodName" class="font-weight-bold">
                                Food Name
                            </label>

                            <input type="text"
                                   name="food_name"
                                   id="foodName"
                                   class="form-control form-control-lg"
                                   placeholder="Enter food name"
                                   maxlength="150"
                                   required>

                        </div>

                        <!-- Description -->
                        <div class="form-group mb-4">

                            <label for="description" class="font-weight-bold">
                                Description
                            </label>

                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Enter food description"
                                      required></textarea>

                        </div>

                        <!-- Price -->
                        <div class="form-group mb-4">

                            <label for="price" class="font-weight-bold">
                                Price (₹)
                            </label>

                            <input type="number"
                                   step="0.01"
                                   min="0.01"
                                   name="price"
                                   id="price"
                                   class="form-control form-control-lg"
                                   placeholder="Enter food price"
                                   required>

                        </div>

                        <!-- Food Image -->
                        <div class="form-group mb-4">

                            <label for="foodImage" class="font-weight-bold">
                                Food Image
                            </label>

                            <input type="file"
                                   name="image"
                                   id="foodImage"
                                   class="form-control-file d-block"
                                   accept="image/jpeg,image/png,image/webp,image/gif">

                          
                        </div>

                        <!-- Buttons -->
                        <div class="text-center mt-5">

                            <button type="submit"
                                    name="submit"
                                    class="btn btn-success btn-lg mr-2 mb-2">

                                <i class="fa fa-plus-circle mr-1"
                                   aria-hidden="true"></i>
                                Add Food

                            </button>

                            <a href="foods.php"
                               class="btn btn-secondary btn-lg mb-2">

                                <i class="fa fa-times mr-1"
                                   aria-hidden="true"></i>
                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Keep your existing Footer Section below -->
<footer class="footer_section">
  <div class="container">
    <div class="footer-info text-center">
      <p>
        Developed by <strong>Dhruvi Kansagra</strong><br>
        © <span id="displayYear"></span> FoodZone |
        <a href="https://www.foodzone.com" target="_blank">
          www.foodzone.com
        </a>
      </p>
    </div>
  </div>
</footer>
<!-- End Admin Footer -->
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