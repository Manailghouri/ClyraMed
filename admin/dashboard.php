<?php 
session_start();
include('../code.php'); 

// Handle user updates and deletions
if (isset($_POST['update_user'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);
    $userName = mysqli_real_escape_string($conn, $_POST['user_name']);
    $userEmail = mysqli_real_escape_string($conn, $_POST['user_email']);
    
    $updateUserQuery = "UPDATE users SET name = '$userName', email = '$userEmail' WHERE id = '$userId'";
    if (mysqli_query($conn, $updateUserQuery)) {
        $success = "User updated successfully!";
    } else {
        $error = "Error updating user: " . mysqli_error($conn);
    }
}

if (isset($_POST['delete_user_dashboard'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    $deleteUserQuery = "DELETE FROM users WHERE id = '$userId'";
    if (mysqli_query($conn, $deleteUserQuery)) {
        $success = "User deleted successfully!";
    } else {
        $error = "Error deleting user: " . mysqli_error($conn);
    }
}

// Handle appointment updates and deletions
if (isset($_POST['update_appointment_status'])) {
    $appointmentId = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['status']);
    
    $updateAppointmentQuery = "UPDATE contact_form SET status = '$newStatus' WHERE id = '$appointmentId'";
    if (mysqli_query($conn, $updateAppointmentQuery)) {
        $success = "Appointment status updated successfully!";
    } else {
        $error = "Error updating appointment: " . mysqli_error($conn);
    }
}

if (isset($_POST['delete_appointment_dashboard'])) {
    $appointmentId = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    
    $deleteAppointmentQuery = "DELETE FROM contact_form WHERE id = '$appointmentId'";
    if (mysqli_query($conn, $deleteAppointmentQuery)) {
        $success = "Appointment deleted successfully!";
    } else {
        $error = "Error deleting appointment: " . mysqli_error($conn);
    }
}

// Get doctor count for dashboard
$doctorCountQuery = "SELECT COUNT(*) as total_doctors FROM doctors";
$doctorCountResult = mysqli_query($conn, $doctorCountQuery);
$doctorCount = mysqli_fetch_assoc($doctorCountResult)['total_doctors'];

// Get patient count for dashboard (registered users who are not admins)
$patientCountQuery = "SELECT COUNT(*) as total_patients FROM users WHERE is_admin = 0";
$patientCountResult = mysqli_query($conn, $patientCountQuery);
$patientCount = mysqli_fetch_assoc($patientCountResult)['total_patients'];

// Get appointment count for dashboard
$appointmentCountQuery = "SELECT COUNT(*) as total_appointments FROM contact_form";
$appointmentCountResult = mysqli_query($conn, $appointmentCountQuery);
$appointmentCount = mysqli_fetch_assoc($appointmentCountResult)['total_appointments'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Main CSS for dashboard -->
    <link rel="stylesheet" href="style.css">
    <!-- Remix Icon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <title>Dashboard</title>
</head>
<body>
    <!-- ================= HEADER SECTION ================= -->
    <?php include 'component/header.php'; ?>

    <!-- ================= MAIN SECTION ================= -->
    <section class="main">
        <!-- Sidebar included -->
        <?php include 'component/sidebar.php'; ?>

        <!-- ========== Main dashboard content ========== -->
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

            <!-- Overview Section -->
            <div class="overview">
                <div class="title">
                    <h2 class="section-title">Overview</h2>
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Today</option>
                        <option value="lastweek">Last week</option>
                        <option value="lastmonth">Last Month</option>
                        <option value="lastyear">Last Year</option>
                        <option value="alltime">All Time</option>
                    </select>
                </div>

                <div class="cards">
                    <div class="card card-1">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Doctors</h5>
                                <h1><?php echo $doctorCount; ?></h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>Active</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i><?php echo $doctorCount; ?></span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>0</span>
                        </div>
                    </div>

                    <div class="card card-2">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Patients</h5>
                                <h1><?php echo $patientCount; ?></h1>
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>Active</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i><?php echo $patientCount; ?></span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>0</span>
                        </div>
                    </div>

                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Appointments</h5>
                                <h1><?php echo $appointmentCount; ?></h1>
                            </div>
                            <i class="ri-calendar-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>Active</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i><?php echo $appointmentCount; ?></span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>0</span>
                        </div>
                    </div>
                </div>
            </div>

         



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

            <!-- ===== Patients (Registered Users) Section ===== -->
            <div class="recents-patients">
                <div class="title">
                    <h2 class="section--title">Patients (Registered Users)</h2>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registration Date</th>
                                <th>Role</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $viewPatientQuery = "SELECT * FROM users WHERE is_admin = 0 ORDER BY id DESC";
                            $viewPatientResult = mysqli_query($conn, $viewPatientQuery);

                            if (mysqli_num_rows($viewPatientResult) > 0) {
                                while ($patient = mysqli_fetch_assoc($viewPatientResult)) {
                                    $patientId = $patient['id'];
                                    $patientName = $patient['name'];
                                    $patientEmail = $patient['email'];
                            ?>
                                <tr data-user-id="<?php echo $patientId; ?>">
                                    <td class="user-name"><?php echo htmlspecialchars($patientName); ?></td>
                                    <td class="user-email"><?php echo htmlspecialchars($patientEmail); ?></td>
                                    <td>Registered User</td>
                                    <td>
                                        <span class="role-badge regular-user">Patient</span>
                                    </td>
                                    <td>
                                        <span>
                                            <i class="ri-edit-line edit" onclick="toggleEdit(<?php echo $patientId; ?>)" style="cursor: pointer;" title="Edit User"></i>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="user_id" value="<?php echo $patientId; ?>">
                                                <button type="submit" name="delete_user_dashboard" style="background: none; border: none; color: red; cursor: pointer;" title="Delete User">
                                                    <i class="ri-delete-bin-line delete"></i>
                                                </button>
                                            </form>
                                        </span>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align:center'>No patients found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>



            <!-- ===== Recent Appointments Section ===== -->
            <div class="recents-appointments">
                <div class="title">
                    <h2 class="section--title">Recent Appointments</h2>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Doctor</th>
                                <th>Appointment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $viewAppointmentQuery = "SELECT cf.*, d.doctorName 
                                                     FROM contact_form cf 
                                                     LEFT JOIN doctors d ON cf.doctor_id = d.doctorId 
                                                     ORDER BY cf.date DESC LIMIT 10";
                            $viewAppointmentResult = mysqli_query($conn, $viewAppointmentQuery);

                            if (mysqli_num_rows($viewAppointmentResult) > 0) {
                                while ($appointment = mysqli_fetch_assoc($viewAppointmentResult)) {
                                    $appointmentId = $appointment['id'];
                                    $patientName = $appointment['name'];
                                    $patientEmail = $appointment['email'];
                                    $patientPhone = $appointment['number'];
                                    $appointmentDate = $appointment['date'];
                                    $doctorName = $appointment['doctorName'];
                                    $status = $appointment['status'] ?? 'Pending';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($patientName); ?></td>
                                    <td><?php echo htmlspecialchars($patientEmail); ?></td>
                                    <td><?php echo htmlspecialchars($patientPhone); ?></td>
                                    <td>
                                        <?php if ($doctorName): ?>
                                            <span style="background-color: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                Dr. <?php echo htmlspecialchars($doctorName); ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #666; font-style: italic;">No doctor</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date("d-m-Y", strtotime($appointmentDate)); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($status); ?>">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px; align-items: center;">
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="appointment_id" value="<?php echo $appointmentId; ?>">
                                                <select name="status" onchange="this.form.submit()" style="font-size: 12px; padding: 2px;">
                                                    <option value="Pending" <?php echo ($status === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Confirmed" <?php echo ($status === 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                                    <option value="Completed" <?php echo ($status === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="Cancelled" <?php echo ($status === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                                <input type="hidden" name="update_appointment_status" value="1">
                                            </form>
                                            
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                                <input type="hidden" name="appointment_id" value="<?php echo $appointmentId; ?>">
                                                <button type="submit" name="delete_appointment_dashboard" style="background: none; border: none; color: red; cursor: pointer;" title="Delete Appointment">
                                                    <i class="ri-delete-bin-line delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align:center'>No appointments found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <style>
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-badge.confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-badge.completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-badge.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .role-badge.admin {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .role-badge.regular-user {
            background-color: #d4edda;
            color: #155724;
        }
    </style>

    <!-- Sidebar JS -->
    <script src="component/sidebar.js"></script>
    
    <script>
        // Toggle edit mode for users
        function toggleEdit(userId) {
            const row = document.querySelector(`[data-user-id="${userId}"]`);
            if (row) {
                const isEditing = row.classList.contains('editing');
                if (isEditing) {
                    // Cancel edit mode
                    row.classList.remove('editing');
                    location.reload(); // Refresh to show original data
                } else {
                    // Enter edit mode
                    row.classList.add('editing');
                    const nameCell = row.querySelector('.user-name');
                    const emailCell = row.querySelector('.user-email');
                    
                    const currentName = nameCell.textContent;
                    const currentEmail = emailCell.textContent;
                    
                    nameCell.innerHTML = `<input type="text" value="${currentName}" id="name_${userId}" style="width: 100%; padding: 5px;">`;
                    emailCell.innerHTML = `<input type="email" value="${currentEmail}" id="email_${userId}" style="width: 100%; padding: 5px;">`;
                    
                    // Change edit icon to save
                    const editIcon = row.querySelector('.ri-edit-line');
                    editIcon.className = 'ri-save-line';
                    editIcon.setAttribute('onclick', `saveUser(${userId})`);
                    editIcon.setAttribute('title', 'Save Changes');
                }
            }
        }
        
        // Save user changes
        function saveUser(userId) {
            const name = document.getElementById(`name_${userId}`).value;
            const email = document.getElementById(`email_${userId}`).value;
            
            if (name.trim() === '' || email.trim() === '') {
                alert('Please fill in all fields');
                return;
            }
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="user_id" value="${userId}">
                <input type="hidden" name="user_name" value="${name}">
                <input type="hidden" name="user_email" value="${email}">
                <input type="hidden" name="update_user" value="1">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        
        // Auto-refresh after form submissions
        <?php if (isset($success) || isset($error)): ?>
            setTimeout(function() {
                window.location.href = window.location.href.split('?')[0];
            }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>
