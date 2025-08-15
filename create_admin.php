<?php
// Simple Admin Creation Script
// No password hashing, very simple

include 'code.php'; // DB connection

$message = "";

if (isset($_POST['create_admin'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

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
            $insertQuery = "INSERT INTO users (name, email, password, is_admin) VALUES ('$name', '$email', '$password', 1)";
            
            if (mysqli_query($conn, $insertQuery)) {
                $message = "✅ Admin user created successfully!";
            } else {
                $message = "❌ Failed to create admin user.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin - Care</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<header class="header fixed-top">
  <div class="container">
    <div class="row align-items-center justify-content-between">
              <a href="#home" class="logo">Care</a>
    </div>
  </div>
</header>

<section class="sign-contact" id="sign-contact">
    <h1 class="heading">Create Admin User</h1>
    <?php if ($message) echo "<p>$message</p>"; ?>
    
    <form method="post">
        <span>Admin Name:</span>
        <input type="text" name="name" placeholder="Enter admin name" class="box" required>

        <span>Admin Email:</span>
        <input type="email" name="email" placeholder="Enter admin email" class="box" required>

        <span>Password:</span>
        <input type="password" name="password" placeholder="Create a password" class="box" required>

        <span>Confirm Password:</span>
        <input type="password" name="cpassword" placeholder="Re-enter password" class="box" required>

        <input type="submit" value="Create Admin" name="create_admin" class="link-btn">

        <p style="margin-top: 10px;">
            <a href="login.php" style="color: #4e9cff; text-decoration: underline;">Back to Login</a>
        </p>
    </form>
</section>

</body>
</html>
