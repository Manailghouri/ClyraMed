<?php include('code.php'); 

$conn = mysqli_connect('localhost', 'root', '', 'care') or die("connection failed");

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $date = $_POST['date'];

    $insert = mysqli_query($conn, "INSERT INTO contact_form (name, email, number, date) 
                VALUES ('$name', '$email', '$number', '$date')") or die('query failed');

    $message[] = $insert ? 'Appointment made successfully' : 'Appointment failed';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care – Healthcare Management</title>

    <!------------Font Awesome cdn link ------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!--------bootstrap cdn link-------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css">

    <!---------Custom css link------>
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
 <!-- Header section start -->
<header class="header fixed-top">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            
            <!-- Logo -->
            <a href="index.php" class="logo">Care</a>

            <!-- Navigation links -->
            <nav class="nav">
                <a href="index.php#home">Home</a>
                <a href="index.php#about">About</a>
                <a href="index.php#services">Services</a>
                <a href="index.php#reviews">Reviews</a>
                <a href="index.php#contact">Contact</a>
                
                <!-- Login/Logout button inside nav -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="link-btn">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="link-btn">Login</a>
                <?php endif; ?>
            </nav>

            <!-- Mobile menu icon -->
            <div id="menu-btn" class="fas fa-bars"></div>

        </div>
    </div>
</header>
<!-- Header section ends -->