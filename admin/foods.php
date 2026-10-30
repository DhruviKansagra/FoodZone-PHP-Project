<?php
include("connected.php");

/* FETCH CATEGORIES */
$categories_query = "SELECT c_id, name FROM categories ORDER BY name ASC";
$categories_result = mysqli_query($conn, $categories_query);

if (!$categories_result) {
    die("Category Query Error: " . mysqli_error($conn));
}


/* FILTER */
$filter_category = isset($_GET['c_id']) ? intval($_GET['c_id']) : 0;


/* FOOD QUERY */
$sql = "SELECT
            f.f_id,
            f.food_name,
            f.description,
            f.price,
            f.image,
            c.name AS category_name
        FROM food AS f
        INNER JOIN categories AS c 
            ON f.c_id = c.c_id";


if ($filter_category > 0) {
    $sql .= " WHERE f.c_id = $filter_category";
}


$sql .= " ORDER BY f.f_id DESC";


$result = mysqli_query($conn, $sql);


if (!$result) {
    die("Food Query Error: " . mysqli_error($conn));
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


<!-- Foods Management Section -->
<div class="container py-5 my-5" style="min-height: 70vh;">

    <!-- Page Heading -->
    <div class="text-center pt-3 mb-5">
        <h2 class="font-weight-bold mb-2">Foods Management</h2>
        <p class="text-muted mb-0">
            Add, view, edit and manage your food items
        </p>
    </div>

    <!-- Top Actions -->
    <div class="d-flex justify-content-end mb-4">

        <a href="add_foods.php" class="btn btn-dark btn-lg">
            <i class="fa fa-plus mr-2" aria-hidden="true"></i>
            Add Food
        </a>

    </div>

    <!-- Category Filter -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">

            <form method="GET" class="form-row align-items-end">

                <div class="col-md-6 col-lg-7 mb-3 mb-md-0">


                    <select name="c_id"
                            id="categoryFilter"
                            class="form-control">

                        <option value="">All Categories</option>

                        <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>

                            <option
                                value="<?= (int)$cat['c_id']; ?>"
                                <?= ((string)$filter_category === (string)$cat['c_id']) ? 'selected' : ''; ?>>

                                <?= htmlspecialchars($cat['name']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="col-md-6 col-lg-5">

                    <div class="d-flex flex-wrap">

                        <button type="submit"
                                class="btn btn-primary mr-2 mb-2 mb-md-0">

                            <i class="fa fa-search mr-1" aria-hidden="true"></i>
                            Search

                        </button>

                        <a href="foods.php"
                           class="btn btn-secondary mb-2 mb-md-0">

                            <i class="fa fa-refresh mr-1" aria-hidden="true"></i>
                            Clear

                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <!-- Food Table -->
    <div class="card shadow-sm mb-5">

        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0">Food Items List</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center mb-0">

                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Image</th>
                            <th scope="col">Category</th>
                            <th scope="col">Food Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col" style="min-width: 150px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (mysqli_num_rows($result) > 0): ?>

                            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                <tr>

                                    <!-- Food ID -->
                                    <td class="align-middle">
                                        <?= (int)$row['f_id']; ?>
                                    </td>

                                    <!-- Food Image -->
                                    <td class="align-middle">

                                        <?php if (!empty($row['image'])): ?>

                                            <img
                                                src="../images/<?= htmlspecialchars($row['image']); ?>"
                                                alt="<?= htmlspecialchars($row['food_name']); ?>"
                                                width="100"
                                                height="90"
                                                class="rounded"
                                                style="object-fit: cover;"
                                                onerror="this.onerror=null;this.src='../images/no-image.png';">

                                        <?php else: ?>

                                            <img
                                                src="../images/no-image.png"
                                                alt="No image available"
                                                width="100"
                                                height="90"
                                                class="rounded"
                                                style="object-fit: cover;">

                                        <?php endif; ?>

                                    </td>

                                    <!-- Category -->
                                    <td class="align-middle">
                                        <?= htmlspecialchars($row['category_name']); ?>
                                    </td>

                                    <!-- Food Name -->
                                    <td class="align-middle font-weight-bold">
                                        <?= htmlspecialchars($row['food_name']); ?>
                                    </td>

                                    <!-- Description -->
                                    <td class="align-middle text-left"
                                        style="min-width: 200px; max-width: 300px;">

                                        <?= !empty($row['description'])
                                            ? nl2br(htmlspecialchars($row['description']))
                                            : '<span class="text-muted">No description</span>'; ?>

                                    </td>

                                    <!-- Price -->
                                    <td class="align-middle text-nowrap">
                                        ₹<?= number_format($row['price'], 2); ?>
                                    </td>

                                    <!-- Actions -->
                                    <td class="align-middle">

                                        <div class="d-flex justify-content-center align-items-center flex-wrap">

                                            <a href="edit_food.php?f_id=<?= (int)$row['f_id']; ?>"
                                               class="btn btn-primary btn-sm m-1">

                                                <i class="fa fa-pencil mr-1"
                                                   aria-hidden="true"></i>
                                                Edit

                                            </a>

                                            <a href="delete_food.php?f_id=<?= (int)$row['f_id']; ?>"
                                               class="btn btn-danger btn-sm m-1"
                                               onclick="return confirm('Are you sure you want to delete this food?');">

                                                <i class="fa fa-trash mr-1"
                                                   aria-hidden="true"></i>
                                                Delete

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center py-5">

                                    <i class="fa fa-cutlery fa-2x text-muted mb-3"
                                       aria-hidden="true"></i>

                                    <h5 class="mt-2">No Food Items Found</h5>

                                    <p class="text-muted mb-3">
                                        No food items match your selection.
                                    </p>

                                    <a href="add_foods.php"
                                       class="btn btn-primary">

                                        <i class="fa fa-plus mr-1"
                                           aria-hidden="true"></i>
                                        Add Food

                                    </a>

                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- Keep your existing footer section below --><footer class="footer_section">
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