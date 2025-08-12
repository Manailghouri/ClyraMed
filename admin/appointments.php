<?php
session_start();
include '../code.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle status updates
if (isset($_POST['update_status'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $updateSql = "UPDATE contact_form SET status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $new_status, $appointment_id);
    
    if ($updateStmt->execute()) {
        $success = "Appointment status updated successfully!";
    } else {
        $error = "Error updating appointment status.";
    }
}

// Handle appointment deletion
if (isset($_POST['delete_appointment'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    
    $deleteSql = "DELETE FROM contact_form WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $appointment_id);
    
    if ($deleteStmt->execute()) {
        $success = "Appointment deleted successfully!";
    } else {
        $error = "Error deleting appointment.";
    }
}

// Fetch all appointments with doctor names
$sql = "SELECT cf.*, d.doctorName 
        FROM contact_form cf 
        LEFT JOIN doctors d ON cf.doctor_id = d.doctorId 
        ORDER BY cf.date DESC";
$result = $conn->query($sql);

$appointments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

// Count appointments by status
$pendingCount = 0;
$confirmedCount = 0;
$completedCount = 0;
$cancelledCount = 0;

foreach ($appointments as $appt) {
    $status = $appt['status'] ?? 'Pending';
    switch ($status) {
        case 'Confirmed':
            $confirmedCount++;
            break;
        case 'Completed':
            $completedCount++;
            break;
        case 'Cancelled':
            $cancelledCount++;
            break;
        default:
            $pendingCount++;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Appointments Management</title>
    
    <!-- Remix Icon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <!-- Main CSS for dashboard -->
    <link rel="stylesheet" href="style.css">
    
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
        .btn-group .btn {
            margin-right: 5px;
        }
        .stats-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 2rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
        }
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .form-control-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            border: 1px solid #ced4da;
        }
        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
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
            <div class="title">
                <h2 class="section--title">Appointments Management</h2>
                <a href="bookAppointment.php">
                    <button class="add"><i class="ri-add-line"></i>Book Appointment</button>
                </a>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Status Summary Cards -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div style="color: #ffc107; font-size: 2rem; margin-bottom: 1rem;">
                        <i class="ri-time-line"></i>
                    </div>
                    <h3 style="font-size: 2.5rem; margin: 0; color: #333;"><?php echo $pendingCount; ?></h3>
                    <p style="margin: 0.5rem 0 0 0; color: #666;">Pending</p>
                </div>
                <div class="stats-card">
                    <div style="color: #007bff; font-size: 2rem; margin-bottom: 1rem;">
                        <i class="ri-check-line"></i>
                    </div>
                    <h3 style="font-size: 2.5rem; margin: 0; color: #333;"><?php echo $confirmedCount; ?></h3>
                    <p style="margin: 0.5rem 0 0 0; color: #666;">Confirmed</p>
                </div>
                <div class="stats-card">
                    <div style="color: #28a745; font-size: 2rem; margin-bottom: 1rem;">
                        <i class="ri-check-double-line"></i>
                    </div>
                    <h3 style="font-size: 2.5rem; margin: 0; color: #333;"><?php echo $completedCount; ?></h3>
                    <p style="margin: 0.5rem 0 0 0; color: #666;">Completed</p>
                </div>
                <div class="stats-card">
                    <div style="color: #dc3545; font-size: 2rem; margin-bottom: 1rem;">
                        <i class="ri-close-line"></i>
                    </div>
                    <h3 style="font-size: 2.5rem; margin: 0; color: #333;"><?php echo $cancelledCount; ?></h3>
                    <p style="margin: 0.5rem 0 0 0; color: #666;">Cancelled</p>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="recents-appointments">
                <div class="title">
                    <h2 class="section--title">All Appointments</h2>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
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
                            <?php if (!empty($appointments)): ?>
                                <?php foreach ($appointments as $appt): ?>
                                    <tr>
                                        <td><?php echo $appt['id']; ?></td>
                                        <td><?php echo htmlspecialchars($appt['name']); ?></td>
                                        <td><?php echo htmlspecialchars($appt['email']); ?></td>
                                        <td><?php echo htmlspecialchars($appt['number']); ?></td>
                                        <td>
                                            <?php if ($appt['doctorName']): ?>
                                                <span style="background-color: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                    Dr. <?php echo htmlspecialchars($appt['doctorName']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span style="color: #666; font-style: italic;">No doctor assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $dateTime = date_create($appt['date']);
                                                echo $dateTime ? date_format($dateTime, 'd M Y') : 'Invalid Date';
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = $appt['status'] ?? 'Pending';
                                            ?>
                                            <span class="status-badge <?php echo strtolower($status); ?>">
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 5px; align-items: center;">
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
                                                    <select name="status" class="form-control-sm" style="width: auto;">
                                                        <option value="Pending" <?php echo ($status === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Confirmed" <?php echo ($status === 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                                        <option value="Completed" <?php echo ($status === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="Cancelled" <?php echo ($status === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                    <button type="submit" name="update_status" class="btn-sm btn-primary">Update</button>
                                                </form>
                                                
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
                                                    <button type="submit" name="delete_appointment" class="btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #666; padding: 2rem;">No appointments found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Sidebar JS -->
    <script src="component/sidebar.js"></script>
</body>
</html>