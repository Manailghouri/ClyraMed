<?php
session_start();
include 'code.php'; // DB connection

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // First check if it's an admin user (is_admin = 1)
    $adminQuery = "SELECT * FROM users WHERE email = '$email' AND is_admin = 1";
    $adminResult = mysqli_query($conn, $adminQuery);

    if (mysqli_num_rows($adminResult) > 0) {
        $user = mysqli_fetch_assoc($adminResult);
        
        // For admin, use simple password check (as per existing system)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['role'] = 'admin';

            // Redirect admin to admin dashboard
            header("Location: admin/dashboard.php");
            exit();
        }
    }

    // If not admin, check if it's a doctor
    $doctorQuery = "SELECT * FROM doctors WHERE doctorEmail = '$email'";
    $doctorResult = mysqli_query($conn, $doctorQuery);

    if (mysqli_num_rows($doctorResult) > 0) {
        $doctor = mysqli_fetch_assoc($doctorResult);
        
        // For doctor, verify hashed password
        if (password_verify($password, $doctor['doctorPassword'])) {
            $_SESSION['user_id'] = $doctor['doctorId'];
            $_SESSION['name'] = $doctor['doctorName'];
            $_SESSION['is_admin'] = 0;
            $_SESSION['role'] = 'doctor';
            $_SESSION['doctor_email'] = $doctor['doctorEmail'];

            // Redirect doctor to doctor dashboard
            header("Location: doctor/doctor-dashboard.php");
            exit();
        }
    }

    // If not admin or doctor, check if it's a regular user (patient)
    $userQuery = "SELECT * FROM users WHERE email = '$email' AND is_admin = 0";
    $userResult = mysqli_query($conn, $userQuery);

    if (mysqli_num_rows($userResult) > 0) {
        $user = mysqli_fetch_assoc($userResult);
        
        // For regular users, use simple password check (as per existing system)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['is_admin'] = 0;
            $_SESSION['role'] = 'user';

            // Redirect regular user to main page
            header("Location: index.php");
            exit();
        }
    }

    // If no user found or password incorrect
    $error = "❌ Invalid email or password.";
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
        /* Override some styles for login page */
        .login-contact {
            padding: 50px 0;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0;
        }
        
        .login-contact .heading {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 40px;
            color: #333;
            font-weight: bold;
        }
        
        .login-contact form {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        
        .login-contact form span {
            display: block;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .login-contact form .box {
            width: 100%;
            padding: 15px 20px;
            font-size: 1.1rem;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .login-contact form .box:focus {
            border-color: #00B8B8;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 184, 184, 0.3);
        }
        
        .login-contact form .link-btn {
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
        
        .login-contact form .link-btn:hover {
            background: #00B8B8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .login-contact form p {
            text-align: center;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .login-contact form p a {
            color: #00B8B8;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-contact form p a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background: #ffe6e6;
            color: #d63031;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 20px;
            border: 1px solid #fab1a0;
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
            .login-contact form {
                padding: 30px 20px;
                margin: 20px;
            }
            
            .login-contact .heading {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
<br><br> <br><br> 

    <section class="login-contact" id="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 ">
                    <h2 class="heading ">Welcome to Care</h2>
                    <p style="text-align: center; color: #666; ; font-size: 1.1rem;">
                        Login for Patients, Doctors & Administrators
                    </p>
                    <?php if ($error) echo "<div class='error-message'>$error</div>"; ?>
                    
                    <form method="post">
                        <span>Email Address:</span>
                        <input type="email" name="email" placeholder="Enter your email address" class="box" required>

                        <span>Password:</span>
                        <input type="password" name="password" placeholder="Enter your password" class="box" required>

                        <input type="submit" value="Login to Care" name="login" class="link-btn">

                        <p>
                            Don't have an account? 
                            <a href="signup.php">Sign up here</a>
                        </p>
                        <p style="font-size: 0.9rem; color: #666; margin-top: 10px;">
                            <i class="fas fa-info-circle"></i> 
                            This login works for patients, doctors, and administrators.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="./assets/js/script.js"></script>
</body>
</html>


