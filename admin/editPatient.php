<?php
session_start();
include '../code.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get patient ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: patient.php");
    exit();
}

$patientId = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch patient details
$patientQuery = "SELECT * FROM users WHERE id = '$patientId' AND is_admin = 0";
$patientResult = mysqli_query($conn, $patientQuery);

if (mysqli_num_rows($patientResult) == 0) {
    header("Location: patient.php");
    exit();
}

$patient = mysqli_fetch_assoc($patientResult);

// Handle patient update
if (isset($_POST['updatePatient'])) {
    $patientName = mysqli_real_escape_string($conn, $_POST['patientName']);
    $patientEmail = mysqli_real_escape_string($conn, $_POST['patientEmail']);
    $patientPassword = mysqli_real_escape_string($conn, $_POST['patientPassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    
    // Validation
    if (empty($patientName) || empty($patientEmail)) {
        $error = "Name and email are required!";
    } elseif (!empty($patientPassword) && $patientPassword !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists for other users
        $checkEmailQuery = "SELECT id FROM users WHERE email = '$patientEmail' AND id != '$patientId'";
        $checkResult = mysqli_query($conn, $checkEmailQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Email already exists! Please use a different email.";
        } else {
            // Update patient
            if (!empty($patientPassword)) {
                // Update with new password
                $updateQuery = "UPDATE users SET name = '$patientName', email = '$patientEmail', password = '$patientPassword' WHERE id = '$patientId'";
            } else {
                // Update without changing password
                $updateQuery = "UPDATE users SET name = '$patientName', email = '$patientEmail' WHERE id = '$patientId'";
            }
            
            if (mysqli_query($conn, $updateQuery)) {
                $success = "Patient updated successfully!";
                // Refresh patient data
                $patientResult = mysqli_query($conn, $patientQuery);
                $patient = mysqli_fetch_assoc($patientResult);
            } else {
                $error = "Error updating patient: " . mysqli_error($conn);
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
    <title>Edit Patient - Care</title>
    
    <!-- Main CSS file for dashboard layout and component styling -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Remix Icon CDN for UI icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    
    <style>
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #00b8b8;
            box-shadow: 0 0 0 2px rgba(0, 184, 184, 0.1);
        }
        
        .btn {
            background-color: #00b8b8;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: #008a8a;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            margin-right: 10px;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .back-btn {
            text-decoration: none;
            color: #6c757d;
            font-size: 14px;
        }
        
        .back-btn:hover {
            color: #00b8b8;
        }
        
        .patient-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        
        .password-note {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .current-info {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 14px;
        }
    </style>
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
                <h2 class="section--title">Edit Patient</h2>
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
                <div class="current-info">
                    <h4 style="margin: 0 0 10px 0; color: #1976d2;">
                        <i class="ri-user-line"></i> Current Patient Information
                    </h4>
                    <p style="margin: 5px 0;"><strong>ID:</strong> <?php echo $patient['id']; ?></p>
                    <p style="margin: 5px 0;"><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']); ?></p>
                    <p style="margin: 5px 0;"><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
                    <p style="margin: 5px 0;"><strong>Current Password:</strong> <?php echo htmlspecialchars($patient['password']); ?></p>
                </div>
                
                <h3 style="margin-bottom: 1.5rem; color: #333;">Update Patient Information</h3>
                
                <form method="POST" id="editPatientForm">
                    <div class="form-group">
                        <label for="patientName">
                            <i class="ri-user-line"></i> Full Name *
                        </label>
                        <input type="text" id="patientName" name="patientName" 
                               value="<?php echo htmlspecialchars($patient['name']); ?>"
                               placeholder="Enter patient's full name" required>
                    </div>

                    <div class="form-group">
                        <label for="patientEmail">
                            <i class="ri-mail-line"></i> Email Address *
                        </label>
                        <input type="email" id="patientEmail" name="patientEmail" 
                               value="<?php echo htmlspecialchars($patient['email']); ?>"
                               placeholder="Enter patient's email address" required>
                    </div>

                    <div class="form-group">
                        <label for="patientPassword">
                            <i class="ri-lock-line"></i> New Password
                        </label>
                        <input type="password" id="patientPassword" name="patientPassword" 
                               placeholder="Enter new password (leave blank to keep current)">
                        <div class="password-note">Leave blank to keep current password: <?php echo htmlspecialchars($patient['password']); ?></div>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">
                            <i class="ri-lock-line"></i> Confirm New Password
                        </label>
                        <input type="password" id="confirmPassword" name="confirmPassword" 
                               placeholder="Confirm new password">
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" name="updatePatient" class="btn">
                            <i class="ri-save-line"></i> Update Patient
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
            
            if (password && password !== confirmPassword) {
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
        document.getElementById('editPatientForm').addEventListener('submit', function(e) {
            const name = document.getElementById('patientName').value.trim();
            const email = document.getElementById('patientEmail').value.trim();
            const password = document.getElementById('patientPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!name || !email) {
                e.preventDefault();
                alert('Name and email are required');
                return;
            }

            if (password && password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }

            if (password && password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return;
            }
        });
    </script>
</body>
</html>