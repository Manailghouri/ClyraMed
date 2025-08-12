<?php
session_start();
include 'code.php'; // DB connection

$message = "";

if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Basic validation
    if ($password !== $cpassword) {
        $message = "❌ Passwords do not match!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format!";
    } else {
        // Check if email exists
        $checkQuery = "SELECT id FROM users WHERE email = '$email'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $message = "❌ Email already registered!";
        } else {
            // Simple insert without password hashing
            $insertQuery = "INSERT INTO users (name, email, password, is_admin) VALUES ('$name', '$email', '$password', 0)";
            
            if (mysqli_query($conn, $insertQuery)) {
                header("Location: login.php");
                exit();
            } else {
                $message = "❌ Failed to create account.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatile" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClyraMed – Clarity in Care</title>

    <!------------Font Awesome cdn link ------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!--------bootstrap cdn link-------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css">

    <!---------Custom css link------>
    <link rel="stylesheet" href="./assets/css/output.css">

</head>

<body>
 <!-- Header section start -->
<header class="header fixed-top">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            
            <!-- Logo -->
            <a href="#home" class="logo">Clyra<span>Med</span></a>

            <!-- Navigation links -->
            <nav class="nav">
                <a href="index.php#home">Home</a>
                <a href="index.php#about">About</a>
                <a href="index.php#services">Services</a>
                <a href="index.php#reviews">Reviews</a>
                <a href="index.php#contact">Contact</a>
            </nav>

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
    
    <style>
        .sign-contact {
            padding: 50px 0;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sign-contact .heading {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 40px;
            color: #333;
            font-weight: bold;
        }
        
        .sign-contact form {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        
        .sign-contact form span {
            display: block;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .sign-contact form .box {
            width: 100%;
            padding: 15px 20px;
            font-size: 1.1rem;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .sign-contact form .box:focus {
            border-color: #00B8B8;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 184, 184, 0.3);
        }
        
        .sign-contact form .link-btn {
            width: 100%;
            padding: 18px;
            font-size: 1.2rem;
            font-weight: 600;
            background: #00B8B8;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .sign-contact form .link-btn:hover {
            background: #00B8B8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .sign-contact form p {
            text-align: center;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .sign-contact form p a {
            color: #00B8B8;
            text-decoration: none;
            font-weight: 600;
        }
        
        .sign-contact form p a:hover {
            text-decoration: underline;
        }
        
        .message {
            background: #ffe6e6;
            color: #d63031;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 20px;
            border: 1px solid #fab1a0;
        }
        
        .success-message {
            background: #e6ffe6;
            color: #27ae60;
            border: 1px solid #a0fab1;
        }
        
        /* Fix header alignment */
        .header {
            background: white;
            z-index: 1000;
        }
        
        body {
            padding-top: 0;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .sign-contact form {
                padding: 30px 20px;
                margin: 20px;
            }
            
            .sign-contact .heading {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
<br><br> <br><br> 

    <section class="sign-contact" id="sign-contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <h1 class="heading">Sign Up</h1>
                    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
                    
                    <form method="post">
                        <span>Your Name:</span>
                        <input type="text" name="name" placeholder="Enter your full name" class="box" required>

                        <span>Your Email Address:</span>
                        <input type="text" name="email" placeholder="Enter your email address" class="box" required>

                        <span>Password:</span>
                        <input type="password" name="password" placeholder="Create a password" class="box" required>

                        <span>Confirm Password:</span>
                        <input type="password" name="cpassword" placeholder="Re-enter your password" class="box" required>

                        <input type="submit" value="Sign Up" name="signup" class="link-btn">

                        <p>Already have an account?
                            <a href="login.php">Login here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="./assets/js/script.js"></script>
</body>
</html>