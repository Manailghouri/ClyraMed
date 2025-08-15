<?php include('code.php'); 
?>
<?php
session_start();
$isLoggedIn = isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true;
?>



<?php

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
    <meta http-equiv="X-UA-Compatile" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClyraMed – Clarity in Care</title>

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
            <a href="#home" class="logo">Clyra<span>Med</span></a>

            <!-- Navigation links -->
            <nav class="nav">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#reviews">Reviews</a>
                <a href="#contact">Contact</a>
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




<!-- Home section start -->
<section class="home" id="home">
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            <div class="content text-center text-md-left">
                <h3>Your Health. Our Priority</h3>
                <p>
                    ClyraMed bridges the gap between patients and doctors with effortless scheduling, secure
                    profiles, and a seamless healthcare journey.
                </p>
<a href="#contact" class="link-btn">Make an Appointment</a>

            </div>
        </div>
    </div>
</section>
<!-- Home section ends -->

    <!----about section start-->
    <section class="about" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 image">
                    <img src="./assets/pexels-polina-tankilevitch-3873193.jpg" alt="" class="w-100 mb-5 mb-md-0">
                </div>
                <div class="col-md-6 content">
                    <span>about us</span>
                    <h3>True Healthcare For Your Family</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, ea dignissimos! Similique deserunt
                        natus culpa fuga dolorem velit est quod itaque, impedit praesentium iste obcaecati dicta amet.
                        Perspiciatis, nihil quaerat?</p>
                    <a href="#" class="link-btn">Learn More</a>
                </div>
            </div>
        </div>
    </section>
    <!-----about section ends-->

    <!-----services section start-->
    <section class="services" id="services">
        <div class="heading">Our services</div>
        <div class="box-container container">
            <div class="box">
                <img src="./assets/service1.svg" alt="">
                <h3>Patient Profile Management</h3>
                <p>Store and access medical history, prescriptions, and reports securely.</p>
                </h3>
            </div>
            <div class="box">
                <img src="./assets/service2.svg" alt="">
                <h3>Find Doctors</h3>
                <p>Search and connect with trusted doctors by specialty, location, or rating.</p>
                </h3>
            </div>
            <div class="box">
                <img src="./assets/service3.svg" alt="">
                <h3>Doctor Appointment Booking</h3>
                <p>Schedule appointments quickly with verified doctors and specialists.</p>
                </h3>
            </div>
            <div class="box">
                <img src="./assets/service4.svg" alt="">
                <h3>Admin Dashboard</h3>
                <p>Manage doctors, patients, and appointments with an easy-to-use control panel.</p>
                </h3>
            </div>
            <div class="box">
                <img src="./assets/service5.svg" alt="">
                <h3>Real-Time Notifications</h3>
                <p>Stay updated with appointment reminders, confirmations, and health alerts.</p>
                </h3>
            </div>
            <div class="box">
                <img src="./assets/service6.svg" alt="">
                <h3>Health Tips & Resources </h3>
                <p>Get personalized health tips and wellness guidance.</p>
                </h3>
            </div>
        </div>
    </section>

    <!---service sectionn end-->



    <!-------process section start----->
    <section class="process">
        <h1 class="heading">Work Process</h1>
        <div class="box-container container">
            <div class="box">
                <img src="./assets/Doctors-pana.png" alt="">
                <h3>General Practitioners</h3>
                <p>Your first step for everyday health concerns, checkups, and prescriptions.</p>

                </p>
            </div>
            <div class="box">
                <img src="./assets/Pediatrician-cuate.png" alt="">
                <h3>Pediatricians</h3>
                <p>Specialized care for infants, children, and teenagers.</p>
            </div>

            <div class="box">
                <img src="./assets/Cardiologist-amico.png" alt="">
                <h3>Cardiologists</h3>
                <p>Advanced care for heart and circulatory health.</p>
            </div>
        </div>

        </div>
    </section>


    <!-------- review sectiom start----->
    <section class="reviews" id="reviews">
        <h1 class="heading">Satisfied Clients </h1>
        <div class="box-container container">
            <div class="box">
                <img src="./assets/c2.jpg" alt="">

                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sequi qui dolores quos placeat magnam
                    consequuntur ab delectus tenetur</p>
                <h3>Alex Dustin</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half"></i>


                </div>
            </div>
            <div class="box">
                <img src="./assets/c1.jpg" alt="">

                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sequi qui dolores quos placeat magnam
                    consequuntur ab delectus tenetur.</p>
                <h3>Elise Michael</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half"></i>



                </div>
            </div>
            <div class="box">
                <img src="./assets/c3.jpg" alt="">

                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sequi qui dolores quos placeat magnam
                    consequuntur ab delectus tenetur</p>
                <h3>John Smith</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half"></i>


                </div>
            </div>
        </div>

    </section>

    <!---------reviews section ends-->

   

<!-- ===== Doctors section ===== -->
    <section class="contact" id="contact">
<div class="doctors">
   <div class="title">
    <h2 class="section--title">Doctors Available</h2>        
    <div class="doctors--right--btns">
        <select name="availability_filter" id="availability_filter" 
            class="dropdown doctor--filter" onchange="filterDoctors(this.value)">
            <option value="">Filter</option>
            <option value="Free">Free</option>
            <option value="Scheduled">Scheduled</option>
        </select>
    </div>
</div>

    <div class="doctors--cards" id="doctorCards">
        <?php while ($doctor = mysqli_fetch_assoc($viewDoctorResult)) { ?>
    <div class="doctor--card" data-status="<?= htmlspecialchars($doctor['doctorAvailability']) ?>">
        <div class="img--box--cover">
            <div class="img--box">
                <img src="./assets/doctor.png" alt="Doctor Image" />
            </div>
        </div>
        <h3><?= htmlspecialchars($doctor['doctorName']) ?></h3>

        <p><strong>City:</strong> <?= htmlspecialchars($doctor['doctorCity'] ?? 'N/A') ?></p>

        <p><strong>Date:</strong>
            <?= (!empty($doctor['doctorAvailabilityDate']) && $doctor['doctorAvailabilityDate'] !== '0000-00-00')
                ? date('d M Y', strtotime($doctor['doctorAvailabilityDate']))
                : 'N/A' ?>
        </p>

        <p><strong>Time:</strong>
            <?= (!empty($doctor['doctorAvailabilityTime']) && $doctor['doctorAvailabilityTime'] !== '00:00:00')
                ? date('h:i A', strtotime($doctor['doctorAvailabilityTime']))
                : 'N/A' ?>
        </p>

        <a href="appointment.php" class="btn">Book Now</a>
    </div>
<?php } ?>

    </div>
    
</div>
    </section>

    <!-------------contact section ends here-->

   <!-------------footer section starts---------->
<section class="footer">
    <div class="box-container container">
        <div class="box">
            <i class="fas fa-phone"></i>
            <h3>Phone Number</h3>
            <p>+123-456-76890</p>
            <p>+111-456-7890</p>
        </div>

        <div class="box">
            <i class="fas fa-map-marker-alt"></i>
            <h3>Your Address</h3>
            <p>Karachi, Pakistan - 12039</p>
        </div>

        <div class="box">
            <i class="fas fa-clock"></i>
            <h3>Opening Hours</h3>
            <p>24/7 Hours Daily</p>
        </div>

        <div class="box">
            <i class="fas fa-envelope"></i>
            <h3>Email Address</h3>
            <p>ClyraMed@gmail.com</p>
            <p>healthcentre@gmail.com</p>
        </div>
    </div>
    <p class="credit">&copy; copyright <?php echo date('Y'); ?> by <span>Clyra Med</span></p>
</section>
<!-------------footer section ends---------->






    <!----------script custo link ---- -->
    <script src="./assets/js/script.js"></script>

</body>

</html>
