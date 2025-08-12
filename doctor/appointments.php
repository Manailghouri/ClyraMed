<?php
session_start();
require_once "../code.php"; // Database connection

// Ensure logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

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

// Fetch appointments from the `contact_form` table
$sql = "SELECT id, name, email, number, date, status FROM contact_form ORDER BY date DESC LIMIT 20";
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Appointments</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="form.css">
</head>
<body>
    <!-- Header -->
  <header class="header" role="banner">
    <div class="container header-inner">
      <a href="doctor-dashboard.php" class="logo" aria-label="ClyraMed Home">
        Care
      </a>

      <nav class="nav" id="main-nav" aria-label="Primary">
        <a href="doctor-dashboard.php">Dashboard</a>
        <a href="editdoctor.php">Profile</a>
        <a href="availability.php">Availability</a>
        <a href="appointments.php" class="active">Appointments</a>
      </nav>

      <div class="header-actions">
        <a href="../logout.php" class="link-btn">Logout</a>
      </div>
    </div>
  </header>

   <main class="page">
  <!-- Appointments Section -->
  <div class="container">
    <!-- Status Summary Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card text-white bg-warning">
          <div class="card-body">
            <h5 class="card-title">Pending</h5>
            <h2><?php echo $pendingCount; ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-info">
          <div class="card-body">
            <h5 class="card-title">Confirmed</h5>
            <h2><?php echo $confirmedCount; ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-success">
          <div class="card-body">
            <h5 class="card-title">Completed</h5>
            <h2><?php echo $completedCount; ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-danger">
          <div class="card-body">
            <h5 class="card-title">Cancelled</h5>
            <h2><?php echo $cancelledCount; ?></h2>
          </div>
        </div>
      </div>
    </div>

    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <section class="card-appointments">
      <h3 class="section-heading"><i class="bi bi-journal-text"></i> Patient Appointments</h3>

      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>Patient Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Appointment Date & Time</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)): ?>
              <?php foreach ($appointments as $appt): ?>
                <tr>
                  <td><?php echo htmlspecialchars($appt['name']); ?></td>
                  <td><?php echo htmlspecialchars($appt['email']); ?></td>
                  <td><?php echo htmlspecialchars($appt['number']); ?></td>
                  <td>
                    <?php
                      $dateTime = date_create($appt['date']);
                      echo $dateTime ? date_format($dateTime, 'l, F j, Y \a\t g:i A') : 'Invalid Date';
                    ?>
                  </td>
                                     <td>
                     <?php 
                     $status = $appt['status'] ?? 'Pending';
                     $badgeClass = '';
                     switch ($status) {
                         case 'Confirmed':
                             $badgeClass = 'badge-info';
                             break;
                         case 'Completed':
                             $badgeClass = 'badge-success';
                             break;
                         case 'Cancelled':
                             $badgeClass = 'badge-danger';
                             break;
                         default:
                             $badgeClass = 'badge-warning';
                             break;
                     }
                     ?>
                     <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                   </td>
                   <td>
                     <div class="btn-group" role="group">
                       <form method="POST" style="display: inline;">
                         <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
                         <select name="status" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                           <option value="Pending" <?php echo ($status === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                           <option value="Confirmed" <?php echo ($status === 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                           <option value="Completed" <?php echo ($status === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                           <option value="Cancelled" <?php echo ($status === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                         </select>
                         <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                       </form>
                       
                       <form method="POST" style="display: inline; margin-left: 5px;" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                         <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
                         <button type="submit" name="delete_appointment" class="btn btn-sm btn-danger">Delete</button>
                       </form>
                     </div>
                   </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-3">No appointments found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
   </main> 

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
