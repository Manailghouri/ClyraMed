<?php include('../code.php');
session_start();
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors - Care</title>

    <!-- External CSS and Remix Icon Library -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Internal Styling for Doctor Cards -->
</head>

<body>
    <!-- Include Header and Sidebar -->
    <?php include 'component/header.php'; ?>
    <section class="main">
        <?php include 'component/sidebar.php'; ?>

        <div class="main--content">
            

<!-- ===== Doctors section ===== -->
<div class="doctors">
    <div class="title">
        <h2 class="section--title">Doctors</h2>
        <div class="doctors--right--btns">
            <select name="availability_filter" id="availability_filter" class="dropdown doctor--filter" onchange="filterDoctors(this.value)">
                <option value="">Filter</option>
                <option value="Free">Free</option>
                <option value="Scheduled">Scheduled</option>
            </select>
            <a href="addDoctor.php">
                <button class="add"><i class="ri-add-line"></i> Add Doctor</button>
            </a>
        </div>
    </div>

    
    <div class="doctors--cards" id="doctorCards">
        <?php while ($doctor = mysqli_fetch_assoc($viewDoctorResult)) { ?>
            <a href="#" class="doctor--card" data-status="<?= htmlspecialchars($doctor['doctorAvailability']) ?>">
                <div class="img--box--cover">
                    <div class="img--box">
                        <img src="../assets/doctor.png" alt="Doctor Image">
                    </div>
                </div>
                <h3><?= htmlspecialchars($doctor['doctorName']) ?></h3>

                <!-- City -->
                <p><strong>City:</strong> <?= htmlspecialchars($doctor['doctorCity']) ?></p>

                <!-- Availability Date -->
                <p><strong>Date:</strong>
                    <?= (!empty($doctor['doctorAvailabilityDate']) && $doctor['doctorAvailabilityDate'] !== '0000-00-00') 
                        ? date('d M Y', strtotime($doctor['doctorAvailabilityDate'])) 
                        : 'N/A' ?>
                </p>

                <!-- Availability Time -->
                <p><strong>Time:</strong>
                    <?= (!empty($doctor['doctorAvailabilityTime']) && $doctor['doctorAvailabilityTime'] !== '00:00:00') 
                        ? date('h:i A', strtotime($doctor['doctorAvailabilityTime'])) 
                        : 'N/A' ?>
                </p>
            </a>
        <?php } ?>
    </div>
</div>

<!-- ===== Recent Doctors Table ===== -->
            <div class="recents-doctors">
    <div class="title">
        <h2 class="section--title">Recent Doctors</h2>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Available Date</th>
                    <th>Available Time</th>
                    <th>Specialty</th>
                    <th>Status</th>
                    <th>Settings</th>
                </tr>
            </thead>
            <tbody>

<?php
$viewDoctorQuery = "SELECT * FROM doctors ORDER BY doctorId DESC";
$viewDoctorResult = mysqli_query($conn, $viewDoctorQuery);

if ($viewDoctorResult && mysqli_num_rows($viewDoctorResult) > 0) {
    while ($doctor = mysqli_fetch_assoc($viewDoctorResult)) {
        $_SESSION['doctorId'] = $doctor['doctorId'];
        $_SESSION['doctorName'] = $doctor['doctorName'];
        $_SESSION['doctorAge'] = $doctor['doctorAge'];
        $_SESSION['doctorEmail'] = $doctor['doctorEmail'];
        $_SESSION['doctorGender'] = $doctor['doctorGender'];
        $_SESSION['doctorCity'] = $doctor['doctorCity'];
        $_SESSION['doctorPhoneNumber'] = $doctor['doctorPhoneNumber'];
        $_SESSION['doctorSpecialization'] = $doctor['doctorSpecialization'];
        $_SESSION['doctorAvailability'] = $doctor['doctorAvailability'];
        $_SESSION['doctorAvailabilityDate'] = $doctor['doctorAvailabilityDate'];
        $_SESSION['doctorAvailabilityTime'] = $doctor['doctorAvailabilityTime'];

        // Format date
        $formattedDate = "Not Scheduled";
        if (!empty($_SESSION['doctorAvailabilityDate']) && $_SESSION['doctorAvailabilityDate'] !== '0000-00-00') {
            $formattedDate = date("d M Y", strtotime($_SESSION['doctorAvailabilityDate']));
        }

        // Format time
        $formattedTime = "Not Scheduled";
        if (!empty($_SESSION['doctorAvailabilityTime']) && $_SESSION['doctorAvailabilityTime'] !== '00:00:00') {
            $formattedTime = date("h:i A", strtotime($_SESSION['doctorAvailabilityTime']));
        }
?>
        <tr>
            <td><?php echo htmlspecialchars($_SESSION['doctorName']); ?></td>
            <td><?php echo htmlspecialchars($formattedDate); ?></td>
            <td><?php echo htmlspecialchars($formattedTime); ?></td>
            <td><?php echo htmlspecialchars($_SESSION['doctorSpecialization']); ?></td>
            <td><?php  if (!empty(isset($_SESSION['doctorAvailability'])) && $_SESSION['doctorAvailability'] == "Scheduled") {
                echo '<span class="status scheduled">Scheduled</span>';
            } else {
                echo '<span class="status free">Free</span>';
                    } ?>
            </td>
            <td>
                <a href="editDoctor.php?doctorId=<?php echo $doctor['doctorId'];?>" class="icon-link">
                    <i class="ri-edit-line edit" title="Edit"></i>
                </a>
                <form action="doctor.php" method="POST" style="display: inline;">
                        <input type="hidden" name="doctorId" value="<?= $doctor['doctorId']; ?>">
                        <button type="submit" name="deleteDoctor" style="background: none; border: none; padding: 0; cursor: pointer; " >
                            <i class="ri-delete-bin-line delete" title="Delete"></i>
                        </button>
                </form>
            </td>
        </tr>
<?php
    }
}
?>

            </tbody>
        </table>
    </div>

        </div>
    </section>

    <!-- Sidebar JS Toggle -->
    <script src="component/sidebar.js"></script>
</body>

</html>
