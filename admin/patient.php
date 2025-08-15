<?php 
    include('../code.php');
    session_start();

    // Check if user is admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }

    // Handle patient deletion
    if (isset($_POST['deletePatient'])) {
        $patientId = mysqli_real_escape_string($conn, $_POST['patientId']);
        
        $deletePatientQuery = "DELETE FROM users WHERE id = '$patientId' AND is_admin = 0";
        if (mysqli_query($conn, $deletePatientQuery)) {
            $success = "Patient deleted successfully!";
        } else {
            $error = "Error deleting patient: " . mysqli_error($conn);
        }
    }

    // Handle patient update
    if (isset($_POST['updatePatient'])) {
        $patientId = mysqli_real_escape_string($conn, $_POST['patientId']);
        $patientName = mysqli_real_escape_string($conn, $_POST['patientName']);
        $patientEmail = mysqli_real_escape_string($conn, $_POST['patientEmail']);
        
        $updatePatientQuery = "UPDATE users SET name = '$patientName', email = '$patientEmail' WHERE id = '$patientId' AND is_admin = 0";
        if (mysqli_query($conn, $updatePatientQuery)) {
            $success = "Patient updated successfully!";
        } else {
            $error = "Error updating patient: " . mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Management - Care</title>

    <!-- Main CSS file for dashboard layout and component styling -->
    <link rel="stylesheet" href="style.css">

    <!-- Remix Icon CDN for UI icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body>

    <!-- ================== HEADER SECTION ================== -->
    <?php include 'component/header.php'; ?>

    <!-- ================== MAIN SECTION ================== -->
    <section class="main">

        <!-- ========== SIDEBAR SECTION (Navigation links) ========== -->
        <?php include 'component/sidebar.php'; ?>

        <!-- ========== MAIN CONTENT AREA ========== -->
        <div class="main--content">

            <?php if (isset($success)): ?>
                <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- ===== PATIENTS TABLE ===== -->
            <div class="recents-patients">

                <!-- Section Title -->
                <div class="title">
                    <h2 class="section--title">Patients (Registered Users)</h2>
                    <div style="display: flex; gap: 10px;">
                        <a href="addPatient.php">
                            <button class="add"><i class="ri-add-line"></i>Add Patient</button>
                        </a>
                        <a href="bookAppointment.php">
                            <button class="add" style="background-color: #28a745;"><i class="ri-calendar-line"></i>Book Appointment</button>
                        </a>
                    </div>
                </div>
                <p style="color: #666; font-size: 14px; margin-bottom: 1rem;">Showing all registered users who can book appointments</p>

                <!-- === Patients Table === -->
                 
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registration Status</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch all patients (registered users who are not admins) from database
                            $viewPatientQuery = "SELECT * FROM users WHERE is_admin = 0 ORDER BY id DESC";
                            $viewPatientResult = mysqli_query($conn, $viewPatientQuery);
                            
                            // Check if there are any patients
                            if(mysqli_num_rows($viewPatientResult) > 0) {
                                // Loop through each patient record
                                while($patient = mysqli_fetch_assoc($viewPatientResult)) {
                                    $patientId = $patient['id'];
                                    $patientName = $patient['name'];
                                    $patientEmail = $patient['email'];
                            ?>
                            <tr data-patient-id="<?php echo $patientId; ?>">
                                <td><?php echo $patientId; ?></td>
                                <td class="patient-name"><?php echo htmlspecialchars($patientName); ?></td>
                                <td class="patient-email"><?php echo htmlspecialchars($patientEmail); ?></td>
                                <td>
                                    <span style="background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                        Registered User
                                    </span>
                                </td>
                                <td>
                                    <span style="background-color: #d1ecf1; color: #0c5460; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                        Patient
                                    </span>
                                </td>
                                <td>
                                    <a href="editPatient.php?id=<?php echo $patientId; ?>"  style="background: none; border: none; color: green; cursor: pointer;" title="Edit Patient"><i class="ri-edit-line edit"></i>
        
                                    </a>
                                    
                                    <a href="bookAppointment.php?patient=<?php echo $patientId; ?>" style="color: #28a745; margin-right: 10px; text-decoration: none;" title="Book Appointment for this Patient">
                                        <i class="ri-calendar-line"></i>
                                    </a>
                                    
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this patient? This will also delete all their appointments.');">
                                        <input type="hidden" name="patientId" value="<?php echo $patientId; ?>">
                                        <button type="submit" name="deletePatient" style="background: none; border: none; color: red; cursor: pointer;" title="Delete Patient">
                                            <i class="ri-delete-bin-line delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                // Display message if no patients found
                                echo "<tr><td colspan='6' style='text-align:center; color: #666; padding: 2rem;'>No patients found. Users need to register to become patients.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </section>

    <!-- Sidebar JavaScript logic for toggle/collapse -->
    <script src="component/sidebar.js"></script>
    
    <script>
        // Auto-refresh after form submissions
        <?php if (isset($success) || isset($error)): ?>
            setTimeout(function() {
                window.location.href = window.location.href.split('?')[0];
            }, 2000);
        <?php endif; ?>
    </script>
</body>

</html>
