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