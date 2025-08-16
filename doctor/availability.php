<?php
session_start();
require_once "../code.php"; // Database connection

// Ensure logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

// Fetch current availability from DB
$sql = "SELECT doctorAvailability, doctorAvailabilityDate, doctorAvailabilityTime 
        FROM doctors WHERE doctorId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$availability = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = mysqli_real_escape_string($conn, $_POST['doctorAvailability']);
    $date   = mysqli_real_escape_string($conn, $_POST['doctorAvailabilityDate']);
    $time   = mysqli_real_escape_string($conn, $_POST['doctorAvailabilityTime']);

    $updateSql = "UPDATE doctors 
                  SET doctorAvailability = ?, doctorAvailabilityDate = ?, doctorAvailabilityTime = ?
                  WHERE doctorId = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssi", $status, $date, $time, $doctor_id);

    if ($updateStmt->execute()) {
        // Redirect directly to dashboard after saving
        header("Location: doctor-dashboard.php?success=1");
        exit();
    } else {
        $error = "Error updating availability: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Availability</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
  <header class="header" role="banner">
    <div class="container header-inner">
      <a href="#" class="logo" aria-label="ClyraMed Home">
        Clyra<span>Med</span>
      </a>

      <nav class="nav" id="main-nav" aria-label="Primary">
        <a href="doctor-dashboard.php" class="active">Dashboard</a>
        <a href="editdoctor.php">Profile</a>
        <a href="availability.php">Availability</a>
        <a href="appointments.php">Appointments</a>
      </nav>

      <div class="header-actions">
        <button id="menu-btn" class="menu-btn" aria-label="Toggle menu" aria-controls="main-nav" aria-expanded="false">
          <i class="bi bi-list"></i>
        </button>
        <a href="../logout.php" class="link-btn">Logout</a>
      </div>
    </div>
  </header>

  <main class="page">
    <div class="container">
      <h1 class="mb-4">Update Your Availability</h1>
      <p class="availability-info">Keep your availability details up-to-date so patients can easily book appointments at the right time.</p>

      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" class="card p-4 shadow-sm availability-form">
        <!-- Availability Status -->
        <div class="form-group">
          <label for="doctorAvailability">Availability Status</label>
          <select name="doctorAvailability" id="doctorAvailability" class="form-control" required>
            <option value="Free" <?php echo ($availability['doctorAvailability'] ?? '') === 'Free' ? 'selected' : ''; ?>>Free</option>
            <option value="Scheduled" <?php echo ($availability['doctorAvailability'] ?? '') === 'Scheduled' ? 'selected' : ''; ?>>Scheduled</option>
            <option value="Not Available" <?php echo ($availability['doctorAvailability'] ?? '') === 'Not Available' ? 'selected' : ''; ?>>Not Available</option>
          </select>
        </div>

        <!-- Date -->
        <div class="form-group">
          <label for="doctorAvailabilityDate">Available Date</label>
          <input type="date" name="doctorAvailabilityDate" id="doctorAvailabilityDate" class="form-control"
                 value="<?php echo htmlspecialchars($availability['doctorAvailabilityDate'] ?? ''); ?>" required>
        </div>

        <!-- Time -->
        <div class="form-group">
          <label for="doctorAvailabilityTime">Available Time</label>
          <input type="time" name="doctorAvailabilityTime" id="doctorAvailabilityTime" class="form-control"
                 value="<?php echo htmlspecialchars($availability['doctorAvailabilityTime'] ?? ''); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
      </form>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
