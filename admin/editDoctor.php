<?php
include('../code.php');
session_start();

// Agar GET se doctorId aya hai to details fetch karo
if (isset($_GET['doctorId'])) {
    $doctorId = $_GET['doctorId'];
    $query = "SELECT * FROM doctors WHERE doctorId = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $doctorId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $doctor = $row;
    } else {
        header('Location: doctor.php?error=notfound');
        exit();
    }
} else {
    header('Location: doctor.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctors</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'component/header.php'; ?>

<section class="main">
    <?php include 'component/sidebar.php'; ?>

    <div class="main--content">
        <div class="title">
            <center><h2 class="section--title">Edit Doctor Details</h2></center>
        </div>

        <div class="doctor-form-wrapper">
            <form action="" method="POST" class="form wide-form">
                <h2>Edit Existing Doctor Details</h2>

                <input type="hidden" name="doctorId" value="<?php echo $doctor['doctorId']; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Doctor Name:</label>
                        <input type="text" name="doctorName" value="<?php echo $doctor['doctorName']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Age:</label>
                        <input type="number" name="doctorAge" value="<?php echo $doctor['doctorAge']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address:</label>
                        <input type="email" name="doctorEmail" value="<?php echo $doctor['doctorEmail']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Gender:</label>
                        <select name="doctorGender" required>
                            <option value="Male" <?php if ($doctor['doctorGender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($doctor['doctorGender'] == 'Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if ($doctor['doctorGender'] == 'Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctorCity">City:</label>
                        <input type="text" id="doctorCity" name="doctorCity" value="<?php echo $doctor['doctorCity']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="tel" name="doctorPhoneNumber" value="<?php echo $doctor['doctorPhoneNumber']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="doctorSpecialization">Specialization</label>
                        <input type="text" id="doctorSpecialization" name="doctorSpecialization" value="<?php echo $doctor['doctorSpecialization']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="doctorAvailability">Availability:</label>
                        <select id="doctorAvailability" name="doctorAvailability" required>
                            <option value="Free" <?php if ($doctor['doctorAvailability'] == 'Free') echo 'selected'; ?>>Free</option>
                            <option value="Scheduled" <?php if ($doctor['doctorAvailability'] == 'Scheduled') echo 'selected'; ?>>Scheduled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctorAvailabilityDate">Available Date:</label>
                        <input type="date" id="doctorAvailabilityDate" name="doctorAvailabilityDate" value="<?php echo $doctor['doctorAvailabilityDate']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="doctorAvailabilityTime">Available Time:</label>
                        <input type="time" id="doctorAvailabilityTime" name="doctorAvailabilityTime" value="<?php echo $doctor['doctorAvailabilityTime']; ?>" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn" name="editDoctor">Update Doctor</button>
            </form>
        </div>
    </div>
</section>

<script src="component/sidebar.js"></script>
</body>
</html>
