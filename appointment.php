<?php
session_start();
require_once "code.php"; // DB connection

$message = [];
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $number = mysqli_real_escape_string($conn, $_POST['number'] ?? '');
    $date = mysqli_real_escape_string($conn, $_POST['date'] ?? '');

    if ($name && $email && $number && $date) {
        $query = "INSERT INTO contact_form (name, email, number, date) VALUES ('$name', '$email', '$number', '$date')";
        if (mysqli_query($conn, $query)) {
            $message[] = "Appointment made successfully!";
        } else {
            $message[] = "Database error: " . mysqli_error($conn);
        }
    } else {
        $message[] = "Please fill in all fields.";
    }
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


<section class="contact" id="contact" style="padding: 20px;">
 <h1 class="heading">Make Appointment</h1>

 <form action="" method="post" class="appointment-form" style="max-width: 500px;">
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '<p class="alert alert-info">' . htmlspecialchars($msg) . '</p>';
        }
    }
    ?>

    <div class="form-group">
      <label>Your Name:</label>
      <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
    </div>

    <div class="form-group">
      <label>Your Email Address:</label>
      <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
    </div>

    <div class="form-group">
      <label>Your Number:</label>
      <input type="number" name="number" class="form-control" placeholder="Enter your number" required>
    </div>

    <div class="form-group">
      <label>Appointment Date & Time:</label>
      <input type="datetime-local" name="date" class="form-control" required>
    </div>

    <button type="submit" name="submit" class="link-btn">Make Appointment</button>
 </form>
</section>
</body>
</html>