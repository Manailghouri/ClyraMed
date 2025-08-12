<?php
session_start();
include '../code.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle appointment booking
if (isset($_POST['bookAppointment'])) {
    $patientId = mysqli_real_escape_string($conn, $_POST['patientId']);
    $patientName = mysqli_real_escape_string($conn, $_POST['patientName']);
    $patientEmail = mysqli_real_escape_string($conn, $_POST['patientEmail']);
    $patientPhone = mysqli_real_escape_string($conn, $_POST['patientPhone']);
    $doctorId = mysqli_real_escape_string($conn, $_POST['doctorId']);
    $appointmentDate = mysqli_real_escape_string($conn, $_POST['appointmentDate']);
    $appointmentTime = mysqli_real_escape_string($conn, $_POST['appointmentTime']);
    
    // Combine date and time
    $fullDateTime = $appointmentDate . ' ' . $appointmentTime;
    
    // Validation
    if (empty($patientId) || empty($doctorId) || empty($patientPhone) || empty($appointmentDate) || empty($appointmentTime)) {
        $error = "All fields are required!";
    } else {
        // Get doctor name for confirmation
        $doctorQuery = "SELECT doctorName FROM doctors WHERE doctorId = '$doctorId'";
        $doctorResult = mysqli_query($conn, $doctorQuery);
        $doctor = mysqli_fetch_assoc($doctorResult);
        $doctorName = $doctor['doctorName'];
        
        // Insert appointment with doctor ID
        $insertAppointmentQuery = "INSERT INTO contact_form (name, email, number, date, doctor_id, status) VALUES ('$patientName', '$patientEmail', '$patientPhone', '$fullDateTime', '$doctorId', 'Confirmed')";
        
        if (mysqli_query($conn, $insertAppointmentQuery)) {
            $success = "Appointment booked successfully for $patientName with Dr. $doctorName!";
            // Clear form data
            $patientId = $doctorId = $patientPhone = $appointmentDate = $appointmentTime = "";
        } else {
            $error = "Error booking appointment: " . mysqli_error($conn);
        }
    }
}

// Check if patient is pre-selected from URL
$selectedPatientId = isset($_GET['patient']) ? mysqli_real_escape_string($conn, $_GET['patient']) : '';

// Fetch all patients for dropdown
$patientsQuery = "SELECT * FROM users WHERE is_admin = 0 ORDER BY name ASC";
$patientsResult = mysqli_query($conn, $patientsQuery);

// Fetch all doctors for reference
$doctorsQuery = "SELECT * FROM doctors ORDER BY doctorName ASC";
$doctorsResult = mysqli_query($conn, $doctorsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Care Admin</title>
    
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
            max-width: 700px;
            margin: 0 auto;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
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
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus, .form-group select:focus {
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
            background: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: none;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stats-card h3 {
            margin: 0;
            color: #00b8b8;
            font-size: 2rem;
        }
        
        .stats-card p {
            margin: 0.5rem 0 0 0;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
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
                <h2 class="section--title">Book Appointment for Patient</h2>
                <a href="appointments.php" class="back-btn">
                    <i class="ri-arrow-left-line"></i> Back to Appointments
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stats-card">
                    <h3><?php echo mysqli_num_rows($patientsResult); ?></h3>
                    <p>Available Patients</p>
                </div>
                <div class="stats-card">
                    <h3><?php echo mysqli_num_rows($doctorsResult); ?></h3>
                    <p>Available Doctors</p>
                </div>
                <div class="stats-card">
                    <h3><?php 
                        $todayCount = mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_form WHERE DATE(date) = CURDATE()");
                        echo mysqli_fetch_assoc($todayCount)['count'];
                    ?></h3>
                    <p>Today's Appointments</p>
                </div>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-container">
                <h3 style="margin-bottom: 1.5rem; color: #333;">
                    <i class="ri-calendar-line"></i> Appointment Booking Form
                </h3>
                
                <form method="POST" id="bookAppointmentForm">
                    <div class="form-group">
                        <label for="patientId">
                            <i class="ri-user-line"></i> Select Patient *
                        </label>
                        <select id="patientId" name="patientId" required onchange="updatePatientInfo()">
                            <option value="">-- Select a Patient --</option>
                            <?php 
                            // Reset the result pointer
                            mysqli_data_seek($patientsResult, 0);
                            while($patient = mysqli_fetch_assoc($patientsResult)): 
                            ?>
                                <option value="<?php echo $patient['id']; ?>" 
                                        data-name="<?php echo htmlspecialchars($patient['name']); ?>"
                                        data-email="<?php echo htmlspecialchars($patient['email']); ?>"
                                        <?php echo ($selectedPatientId == $patient['id'] || (isset($_POST['patientId']) && $_POST['patientId'] == $patient['id'])) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($patient['name']); ?> - <?php echo htmlspecialchars($patient['email']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="patient-info" id="patientInfo">
                        <h4 style="margin: 0 0 10px 0; color: #1976d2;">Selected Patient Information</h4>
                        <p id="patientDetails"></p>
                    </div>

                    <input type="hidden" id="patientName" name="patientName">
                    <input type="hidden" id="patientEmail" name="patientEmail">

                    <div class="form-group">
                        <label for="doctorId">
                            <i class="ri-user-heart-line"></i> Select Doctor *
                        </label>
                        <select id="doctorId" name="doctorId" required onchange="updateDoctorInfo()">
                            <option value="">-- Select a Doctor --</option>
                            <?php 
                            // Reset the result pointer
                            mysqli_data_seek($doctorsResult, 0);
                            while($doctor = mysqli_fetch_assoc($doctorsResult)): 
                            ?>
                                <option value="<?php echo $doctor['doctorId']; ?>" 
                                        data-name="<?php echo htmlspecialchars($doctor['doctorName']); ?>"
                                        data-specialization="<?php echo htmlspecialchars($doctor['doctorSpecialization']); ?>"
                                        data-city="<?php echo htmlspecialchars($doctor['doctorCity']); ?>"
                                        data-availability="<?php echo htmlspecialchars($doctor['doctorAvailability']); ?>"
                                        <?php echo (isset($_POST['doctorId']) && $_POST['doctorId'] == $doctor['doctorId']) ? 'selected' : ''; ?>>
                                    Dr. <?php echo htmlspecialchars($doctor['doctorName']); ?> - <?php echo htmlspecialchars($doctor['doctorSpecialization']); ?> (<?php echo htmlspecialchars($doctor['doctorCity']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="patient-info" id="doctorInfo" style="background: #e8f5e8;">
                        <h4 style="margin: 0 0 10px 0; color: #2e7d32;">Selected Doctor Information</h4>
                        <p id="doctorDetails"></p>
                    </div>

                    <div class="form-group">
                        <label for="patientPhone">
                            <i class="ri-phone-line"></i> Patient Phone Number *
                        </label>
                        <input type="tel" id="patientPhone" name="patientPhone" 
                               value="<?php echo isset($_POST['patientPhone']) ? htmlspecialchars($_POST['patientPhone']) : ''; ?>"
                               placeholder="Enter patient's phone number" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="appointmentDate">
                                <i class="ri-calendar-line"></i> Appointment Date *
                            </label>
                            <input type="date" id="appointmentDate" name="appointmentDate" 
                                   value="<?php echo isset($_POST['appointmentDate']) ? $_POST['appointmentDate'] : ''; ?>"
                                   min="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="appointmentTime">
                                <i class="ri-time-line"></i> Appointment Time *
                            </label>
                            <input type="time" id="appointmentTime" name="appointmentTime" 
                                   value="<?php echo isset($_POST['appointmentTime']) ? $_POST['appointmentTime'] : ''; ?>"
                                   required>
                        </div>
                    </div>



                    <div style="margin-top: 2rem;">
                        <button type="submit" name="bookAppointment" class="btn">
                            <i class="ri-calendar-check-line"></i> Book Appointment
                        </button>
                        <a href="appointments.php" class="btn btn-secondary">
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
        function updatePatientInfo() {
            const select = document.getElementById('patientId');
            const selectedOption = select.options[select.selectedIndex];
            const patientInfo = document.getElementById('patientInfo');
            const patientDetails = document.getElementById('patientDetails');
            const patientNameInput = document.getElementById('patientName');
            const patientEmailInput = document.getElementById('patientEmail');

            if (selectedOption.value) {
                const name = selectedOption.getAttribute('data-name');
                const email = selectedOption.getAttribute('data-email');
                
                patientDetails.innerHTML = `
                    <strong>Name:</strong> ${name}<br>
                    <strong>Email:</strong> ${email}
                `;
                
                patientNameInput.value = name;
                patientEmailInput.value = email;
                patientInfo.style.display = 'block';
            } else {
                patientInfo.style.display = 'none';
                patientNameInput.value = '';
                patientEmailInput.value = '';
            }
        }

        function updateDoctorInfo() {
            const select = document.getElementById('doctorId');
            const selectedOption = select.options[select.selectedIndex];
            const doctorInfo = document.getElementById('doctorInfo');
            const doctorDetails = document.getElementById('doctorDetails');

            if (selectedOption.value) {
                const name = selectedOption.getAttribute('data-name');
                const specialization = selectedOption.getAttribute('data-specialization');
                const city = selectedOption.getAttribute('data-city');
                const availability = selectedOption.getAttribute('data-availability');
                
                doctorDetails.innerHTML = `
                    <strong>Doctor:</strong> Dr. ${name}<br>
                    <strong>Specialization:</strong> ${specialization}<br>
                    <strong>City:</strong> ${city}<br>
                    <strong>Availability:</strong> ${availability}
                `;
                
                doctorInfo.style.display = 'block';
            } else {
                doctorInfo.style.display = 'none';
            }
        }

        // Auto-load patient info if pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            updatePatientInfo();
            updateDoctorInfo();
        });

        // Auto-hide success message
        <?php if (isset($success)): ?>
            setTimeout(function() {
                const alert = document.querySelector('.alert-success');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        <?php endif; ?>

        // Form validation
        document.getElementById('bookAppointmentForm').addEventListener('submit', function(e) {
            const patientId = document.getElementById('patientId').value;
            const doctorId = document.getElementById('doctorId').value;
            const phone = document.getElementById('patientPhone').value.trim();
            const date = document.getElementById('appointmentDate').value;
            const time = document.getElementById('appointmentTime').value;

            if (!patientId) {
                e.preventDefault();
                alert('Please select a patient');
                return;
            }

            if (!doctorId) {
                e.preventDefault();
                alert('Please select a doctor');
                return;
            }

            if (!phone) {
                e.preventDefault();
                alert('Please enter patient phone number');
                return;
            }

            if (!date || !time) {
                e.preventDefault();
                alert('Please select appointment date and time');
                return;
            }

            // Check if selected date is not in the past
            const selectedDate = new Date(date + ' ' + time);
            const now = new Date();
            
            if (selectedDate <= now) {
                e.preventDefault();
                alert('Please select a future date and time');
                return;
            }
        });

        // Set minimum time for today
        document.getElementById('appointmentDate').addEventListener('change', function() {
            const selectedDate = this.value;
            const today = new Date().toISOString().split('T')[0];
            const timeInput = document.getElementById('appointmentTime');
            
            if (selectedDate === today) {
                const now = new Date();
                const currentTime = now.getHours().toString().padStart(2, '0') + ':' + 
                                  now.getMinutes().toString().padStart(2, '0');
                timeInput.min = currentTime;
            } else {
                timeInput.removeAttribute('min');
            }
        });
    </script>
</body>
</html>
