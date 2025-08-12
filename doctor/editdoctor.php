<?php
session_start();
require_once "../code.php"; // Database connection

// Ensure logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorName = mysqli_real_escape_string($conn, $_POST['doctorName']);
    $doctorAge = mysqli_real_escape_string($conn, $_POST['doctorAge']);
    $doctorEmail = mysqli_real_escape_string($conn, $_POST['doctorEmail']);
    $doctorGender = mysqli_real_escape_string($conn, $_POST['doctorGender']);
    $doctorPhoneNumber = mysqli_real_escape_string($conn, $_POST['doctorPhoneNumber']);
    $doctorSpecialization = mysqli_real_escape_string($conn, $_POST['doctorSpecialization']);
    $doctorCity = mysqli_real_escape_string($conn, $_POST['doctorCity']);

    // Check if email already exists for other doctors
    $checkEmailQuery = "SELECT doctorId FROM doctors WHERE doctorEmail = '$doctorEmail' AND doctorId != '$doctor_id'";
    $checkResult = mysqli_query($conn, $checkEmailQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $error = "Email already exists!";
    } else {
        $updateSql = "UPDATE doctors SET 
            doctorName = ?, doctorAge = ?, doctorEmail = ?, doctorGender = ?, 
            doctorPhoneNumber = ?, doctorSpecialization = ?, doctorCity = ?
            WHERE doctorId = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sisssssi", $doctorName, $doctorAge, $doctorEmail, $doctorGender, 
                               $doctorPhoneNumber, $doctorSpecialization, $doctorCity, $doctor_id);

        if ($updateStmt->execute()) {
            $success = "Profile updated successfully!";
            // Update session name
            $_SESSION['name'] = $doctorName;
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
    }
}

// Fetch current doctor data
$sql = "SELECT * FROM doctors WHERE doctorId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Doctor</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="form.css">
</head>
<body>
  <header class="header">
    <div class="container header-inner">
              <a href="doctor-dashboard.php" class="logo">Care</a>
      <nav class="nav" id="main-nav">
        <a href="doctor-dashboard.php">Dashboard</a>
        <a href="editdoctor.php" class="active">Profile</a>
        <a href="availability.php">Availability</a>
        <a href="appointments.php">Appointments</a>
      </nav>
      <a href="../logout.php" class="link-btn">Logout</a>
    </div>
  </header>

  <main class="page">
    <div class="container">
      <h1 class="mb-4">Edit Profile</h1>
      <p class="text-muted">Update your professional information and personal details.</p>

      <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>
      
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" class="card p-4 shadow-sm">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="doctorName">Full Name</label>
              <input type="text" name="doctorName" id="doctorName" class="form-control" 
                     value="<?php echo htmlspecialchars($doctor['doctorName']); ?>" required>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="doctorAge">Age</label>
              <input type="number" name="doctorAge" id="doctorAge" class="form-control" 
                     value="<?php echo htmlspecialchars($doctor['doctorAge']); ?>" min="27" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="doctorEmail">Email Address</label>
              <input type="email" name="doctorEmail" id="doctorEmail" class="form-control" 
                     value="<?php echo htmlspecialchars($doctor['doctorEmail']); ?>" required>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="doctorGender">Gender</label>
              <select name="doctorGender" id="doctorGender" class="form-control" required>
                <option value="Male" <?php echo ($doctor['doctorGender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($doctor['doctorGender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($doctor['doctorGender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="doctorPhoneNumber">Phone Number</label>
              <input type="tel" name="doctorPhoneNumber" id="doctorPhoneNumber" class="form-control" 
                     value="<?php echo htmlspecialchars($doctor['doctorPhoneNumber']); ?>" required>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="doctorCity">City</label>
              <input type="text" name="doctorCity" id="doctorCity" class="form-control" 
                     value="<?php echo htmlspecialchars($doctor['doctorCity']); ?>" required>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="doctorSpecialization">Specialization</label>
          <input type="text" name="doctorSpecialization" id="doctorSpecialization" class="form-control" 
                 value="<?php echo htmlspecialchars($doctor['doctorSpecialization']); ?>" required>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
        </div>
      </form>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
