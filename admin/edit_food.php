<?php

include("connected.php");


// Get food ID from URL
if (!isset($_GET['f_id']) || empty($_GET['f_id'])) {

    echo "<script>
    alert('Invalid food ID');
    window.location.href='foods.php';
    </script>";

    exit;
}


$f_id = intval($_GET['f_id']);



// Fetch food details

$food_query = "SELECT * FROM food WHERE f_id = $f_id";

$food_result = mysqli_query($conn,$food_query);



if(mysqli_num_rows($food_result) != 1)
{

    echo "<script>
    alert('Food not found');
    window.location.href='foods.php';
    </script>";

    exit;

}


$food = mysqli_fetch_assoc($food_result);




// Fetch categories

$categories_query = "SELECT * FROM categories ORDER BY name ASC";

$categories_result = mysqli_query($conn,$categories_query);




// Handle form submit

if($_SERVER['REQUEST_METHOD']=='POST')
{


    $food_name = mysqli_real_escape_string(
        $conn,
        $_POST['food_name']
    );


    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );


    $c_id = intval($_POST['c_id']);


    $price = floatval($_POST['price']);



    // Keep old image

    $image_name = $food['image'];



    // Image Upload

    if(isset($_FILES['image']) && $_FILES['image']['error']==0)
    {


        $target_dir = "images/";

        $new_image_name = basename($_FILES['image']['name']);

        $target_file = $target_dir.$new_image_name;



        if(move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $target_file
        ))
        {

            $image_name = $new_image_name;

        }

        else
        {

            echo "<script>
            alert('Image upload failed');
            </script>";

        }


    }




    // Update food

    $update_query = "

    UPDATE food SET

    food_name='$food_name',

    description='$description',

    c_id=$c_id,

    price=$price,

    image='$image_name'


    WHERE f_id=$f_id

    ";



    if(mysqli_query($conn,$update_query))
    {

        echo "<script>

        alert('Food updated successfully');

        window.location.href='foods.php';

        </script>";

    }

    else
    {

        echo "<script>

        alert('Failed to update food');

        </script>";

    }



}

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

<!-- Edit Food Section -->
<div class="container py-5 my-5" style="min-height: 70vh;">

    <!-- Page Heading -->
    <div class="text-center pt-3 mb-5">
        <h2 class="font-weight-bold mb-2">Edit Food</h2>
        <p class="text-muted mb-0">
            Update food details and manage your food image
        </p>
    </div>

    <!-- Edit Food Form -->
    <div class="row justify-content-center">

        <div class="col-md-9 col-lg-7">

            <div class="card shadow-sm mb-5">

                <!-- Card Header -->
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0">
                        <i class="fa fa-pencil mr-2" aria-hidden="true"></i>
                        Update Food Information
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

                                <option value="" disabled>
                                    Select Category
                                </option>

                                <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>

                                    <option
                                        value="<?= (int)$cat['c_id']; ?>"
                                        <?= ((string)$food['c_id'] === (string)$cat['c_id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($cat['name']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- Food Name -->
                        <div class="form-group mb-4">

                            <label for="food_name" class="font-weight-bold">
                                Food Name
                            </label>

                            <input type="text"
                                   name="food_name"
                                   id="food_name"
                                   class="form-control form-control-lg"
                                   value="<?= htmlspecialchars($food['food_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                   placeholder="Enter food name"
                                   maxlength="150"
                                   autocomplete="off"
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
                                      required><?= htmlspecialchars($food['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>

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
                                   value="<?= htmlspecialchars((string)$food['price'], ENT_QUOTES, 'UTF-8'); ?>"
                                   placeholder="Enter price"
                                   required>

                        </div>

                        <!-- Food Image -->
                        <div class="form-group mb-4">

                            <label for="image" class="font-weight-bold">
                                Food Image
                            </label>

                            <input type="file"
                                   name="image"
                                   id="image"
                                   class="form-control-file d-block"
                                   accept="image/jpeg,image/png,image/webp,image/gif">

                            
                            <!-- Current Image Preview -->
                            <?php if (!empty($food['image'])): ?>

                                <div class="mt-3">

                                    <p class="font-weight-bold mb-2">
                                        Current Food Image
                                    </p>

                                    <img
                                        src="../images/<?= htmlspecialchars($food['image'], ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?= htmlspecialchars($food['food_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                        class="img-thumbnail rounded shadow-sm"
                                        style="width: 200px; height: 160px; object-fit: cover;"
                                        onerror="this.onerror=null;this.src='../images/no-image.png';">

                                </div>

                            <?php else: ?>

                                <p class="text-muted mt-2 mb-0">
                                    No current image available.
                                </p>

                            <?php endif; ?>

                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center mt-5">

                            <button type="submit"
                                    name="update"
                                    class="btn btn-success btn-lg mr-2 mb-2">

                                <i class="fa fa-save mr-1" aria-hidden="true"></i>
                                Update Food

                            </button>

                            <a href="foods.php"
                               class="btn btn-secondary btn-lg mb-2">

                                <i class="fa fa-times mr-1" aria-hidden="true"></i>
                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Keep your existing Footer Section below --><footer class="footer_section">
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