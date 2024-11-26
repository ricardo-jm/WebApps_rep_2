<?php
session_start();
if($_SESSION['Active'] == false){
    header("location:login.php");
    exit;
}else {
    $hidden_when_login = 'd-none';
    if($_SESSION['Username'] != 'admin') {
        $hidden_when_not_admin = 'd-none';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="images/title-img.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $title;?></title>
</head>

<body>
<!-- Header -->
<header>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php">SVDNS</a>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Products</a>
                    <div class="dropdown-menu bg-dark">
                        <a class="dropdown-item bg-dark" href="products.php">View All Products</a>
                        <a class="dropdown-item bg-dark" href="search_products.php">Search Products</a>
                        <a class="dropdown-item bg-dark <?php echo $hidden_when_not_admin; ?>" href="add_product.php">Add Product</a>
                        <a class="dropdown-item bg-dark <?php echo $hidden_when_not_admin; ?>" href="remove_product.php">Remove Product</a>
                        <a class="dropdown-item bg-dark <?php echo $hidden_when_not_admin; ?>" href="edit_product.php">Edit Product</a>
                    </div><!-- End Dropdown -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="links.php">Links/Downloads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gallery.php">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item float-right">
                    <a class="nav-link <?php echo $hidden_when_login; ?>" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $hidden_when_login; ?>" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a href="shopping_cart.php"><i class="fa fa-shopping-cart fa-2x text-warning mx-3"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->


</header>
<!-- End of Header -->

<?php
