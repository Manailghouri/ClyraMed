<?php
session_start();
include '../code.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle patient addition
if (isset($_POST['addPatient'])) {
    $patientName = mysqli_real_escape_string($conn, $_POST['patientName']);
    $patientEmail = mysqli_real_escape_string($conn, $_POST['patientEmail']);
    $patientPassword = mysqli_real_escape_string($conn, $_POST['patientPassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    
    // Validation
    if (empty($patientName) || empty($patientEmail) || empty($patientPassword)) {
        $error = "All fields are required!";
    } elseif ($patientPassword !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $checkEmailQuery = "SELECT id FROM users WHERE email = '$patientEmail'";
        $checkResult = mysqli_query($conn, $checkEmailQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Email already exists! Please use a different email.";
        } else {
            // Insert new patient (user with is_admin = 0)
            $insertPatientQuery = "INSERT INTO users (name, email, password, is_admin) VALUES ('$patientName', '$patientEmail', '$patientPassword', 0)";
            
            if (mysqli_query($conn, $insertPatientQuery)) {
                $success = "Patient added successfully!";
                // Clear form data
                $patientName = $patientEmail = $patientPassword = $confirmPassword = "";
            } else {
                $error = "Error adding patient: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient - Care</title>
    
    <!-- Main CSS file for dashboard layout and component styling -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Remix Icon CDN for UI icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <!-- ================= HEADER SECTION ================= -->
    <?php include 'component/header.php'; ?>

    <!-- ================= MAIN SECTION ================= -->
    <section class="main">
        <!-- Sidebar included -->
        <?php include 'component/sidebar.php'; ?>

        <!-- ========== Main content ========== -->
        <div class="main--content">
            <div class="page-header">
                <h2 class="section--title">Add New Patient</h2>
                <a href="patient.php" class="back-btn">
                    <i class="ri-arrow-left-line"></i> Back to Patients
                </a>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Patient Information</h3>
                
                <form method="POST" id="addPatientForm">
                    <div class="form-group">
                        <label for="patientName">
                            <i class="ri-user-line"></i> Full Name *
                        </label>
                        <input type="text" id="patientName" name="patientName" 
                               value="<?php echo isset($patientName) ? htmlspecialchars($patientName) : ''; ?>"
                               placeholder="Enter patient's full name" required>
                    </div>

                    <div class="form-group">
                        <label for="patientEmail">
                            <i class="ri-mail-line"></i> Email Address *
                        </label>
                        <input type="email" id="patientEmail" name="patientEmail" 
                               value="<?php echo isset($patientEmail) ? htmlspecialchars($patientEmail) : ''; ?>"
                               placeholder="Enter patient's email address" required>
                    </div>

                    <div class="form-group">
                        <label for="patientPassword">
                            <i class="ri-lock-line"></i> Password *
                        </label>
                        <input type="password" id="patientPassword" name="patientPassword" 
                               placeholder="Enter password for patient login" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">
                            <i class="ri-lock-line"></i> Confirm Password *
                        </label>
                        <input type="password" id="confirmPassword" name="confirmPassword" 
                               placeholder="Confirm password" required>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" name="addPatient" class="btn">
                            <i class="ri-add-line"></i> Add Patient
                        </button>
                        <a href="patient.php" class="btn btn-secondary">
                            <i class="ri-close-line"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Sidebar JS -->
    <script src="component/sidebar.js"></script>
    
    <script>
        // Password confirmation validation
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('patientPassword').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Auto-hide success message
        <?php if (isset($success)): ?>
            setTimeout(function() {
                const alert = document.querySelector('.alert-success');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 3000);
        <?php endif; ?>

        // Form validation
        document.getElementById('addPatientForm').addEventListener('submit', function(e) {
            const name = document.getElementById('patientName').value.trim();
            const email = document.getElementById('patientEmail').value.trim();
            const password = document.getElementById('patientPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!name || !email || !password || !confirmPassword) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return;
            }
        });
    </script>
</body>
</html>