<?php
session_start();
require_once "../code.php"; // Database connection

// Ensure the user is logged in as a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

// Doctor ID from login session
$doctor_id = $_SESSION['user_id'];

// Get doctor basic info and availability from DB
$sql = "SELECT doctorName, doctorEmail, doctorSpecialization, doctorPhoneNumber, 
               doctorAvailability, doctorAvailabilityDate, doctorAvailabilityTime, doctorCity
        FROM doctors WHERE doctorId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

$availabilities = [];
if (!empty($doctor['doctorAvailabilityDate']) && !empty($doctor['doctorAvailabilityTime'])) {
    $availabilities[] = [
        'doctorAvailabilityDays' => 'Available',
        'doctorAvailabilityDate' => $doctor['doctorAvailabilityDate'],
        'doctorAvailabilityTime' => $doctor['doctorAvailabilityTime'],
    ];
}

// Get real statistics from database
$today = date('Y-m-d');

// Count appointments for today
$todayApptSql = "SELECT COUNT(*) as today_count FROM contact_form WHERE DATE(date) = ?";
$todayStmt = $conn->prepare($todayApptSql);
$todayStmt->bind_param("s", $today);
$todayStmt->execute();
$todayResult = $todayStmt->get_result();
$patientsToday = $todayResult->fetch_assoc()['today_count'];

// Count total appointments
$totalApptSql = "SELECT COUNT(*) as total_count FROM contact_form";
$totalStmt = $conn->prepare($totalApptSql);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalAppointments = $totalResult->fetch_assoc()['total_count'];

// Count pending appointments
$pendingSql = "SELECT COUNT(*) as pending_count FROM contact_form WHERE status = 'Pending' OR status IS NULL";
$pendingStmt = $conn->prepare($pendingSql);
$pendingStmt->execute();
$pendingResult = $pendingStmt->get_result();
$pendingRequests = $pendingResult->fetch_assoc()['pending_count'];

// Fetch recent appointments
$apptSql = "SELECT name, email, number, date, status FROM contact_form ORDER BY date DESC LIMIT 4";
$apptStmt = $conn->prepare($apptSql);
$apptStmt->execute();
$apptResult = $apptStmt->get_result();

$recentAppointments = [];
if ($apptResult && $apptResult->num_rows > 0) {
    while ($row = $apptResult->fetch_assoc()) {
        $recentAppointments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Dashboard</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Your styling -->
  <link rel="stylesheet" href="form.css" />
</head>
<body>
  <!-- Header -->
  <header class="header" role="banner">
    <div class="container header-inner">
      <a href="#" class="logo" aria-label="ClyraMed Home">
        Clyra<span>Med</span>
      </a>

      <nav class="nav" id="main-nav" aria-label="Primary">
        <a href="#" class="active">Dashboard</a>
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
      <!-- Profile Banner -->
      <section class="profile-banner" aria-label="Doctor Profile">
        <div class="profile-banner-inner">
          <div class="avatar" aria-hidden="true">
            <?php echo strtoupper(substr($doctor['doctorName'], 0, 2)); ?>
          </div>
          <div class="profile-info">
            <h1 class="name">
              Dr. <?php echo htmlspecialchars($doctor['doctorName']); ?>
              <span class="verified-icon" title="Verified" aria-label="Verified"><i class="bi bi-patch-check-fill text-primary"></i></span>
            </h1>
            <p class="email"><?php echo htmlspecialchars($doctor['doctorEmail']); ?></p>
            <p class="specialty"><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor['doctorSpecialization']); ?></p>
<p class="phone"><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['doctorPhoneNumber']); ?></p>


            <div class="btn-group" role="group" aria-label="Quick profile actions">
              <a href="editdoctor.php" class="btn-chip"><i class="bi bi-person-fill"></i> Edit Profile</a>
            </div>
          </div>
        </div>
      </section>

      <!-- Stats -->
      <section class="stats-grid" aria-label="Statistics">
        <article class="stats-card">
          <div class="stats-icon" aria-hidden="true"><i class="bi bi-people-fill"></i></div>
          <div class="stats-value"><?php echo $patientsToday; ?></div>
          <p class="stats-label">Patients Today</p>
        </article>
        <article class="stats-card">
          <div class="stats-icon success" aria-hidden="true"><i class="bi bi-calendar-check-fill"></i></div>
          <div class="stats-value"><?php echo $totalAppointments; ?></div>
          <p class="stats-label">Total Appointments</p>
        </article>
        <article class="stats-card">
          <div class="stats-icon warning" aria-hidden="true"><i class="bi bi-clock-fill"></i></div>
          <div class="stats-value"><?php echo $pendingRequests; ?></div>
          <p class="stats-label">Pending Requests</p>
        </article>
      </section>

      <!-- Quick Actions -->
      <h2 class="dashboard-title">Quick Actions</h2>
      <section class="cards-grid" aria-label="Quick Actions">
        <article class="section card">
          <h3 class="card-title"><i class="bi bi-calendar-event"></i> Appointments</h3>
          <p>Check your upcoming patient appointments and manage bookings.</p>
          <a href="appointments.php" class="link-btn">View</a>
        </article>
        <article class="section card">
          <h3 class="card-title"><i class="bi bi-clock-history"></i> Availability</h3>
          <p>Update your available days and hours for patient consultations.</p>
          <a href="availability.php" class="link-btn">Update</a>
        </article>
        <article class="section card">
          <h3 class="card-title"><i class="bi bi-person-circle"></i> Profile</h3>
          <p>Edit your professional information and personal details.</p>
          <a href="editdoctor.php" class="link-btn">Edit</a>
        </article>
      </section>
     <!-- Doctor Availability -->
<h2 class="dashboard-title">Upcoming Availability</h2>
<section class="section mb-4">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th>Day</th>
          <th>Date</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($availabilities)): ?>
          <?php foreach ($availabilities as $slot): ?>
            <tr>
              <td><?php echo htmlspecialchars($slot['doctorAvailabilityDays']); ?></td>
              <td><?php echo htmlspecialchars($slot['doctorAvailabilityDate']); ?></td>
              <td><?php echo htmlspecialchars($slot['doctorAvailabilityTime']); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-muted text-center">No availability slots added yet.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
   


     <!-- Recent appointments -->
      <section class="section mb-5" aria-label="Recent Appointments">
        <h3 class="section-heading"><i class="bi bi-journal-text"></i> Recent Appointments</h3>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-light">
              <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
              </tr>
            </thead>
           <tbody>
<?php if (!empty($recentAppointments)): ?>
    <?php foreach ($recentAppointments as $appt): 
        $status = $appt['status'] ?? 'Pending';
        $badge = 'warning';  // default badge color
        
        switch ($status) {
            case 'Confirmed':
                $badge = 'info';
                break;
            case 'Completed':
                $badge = 'success';
                break;
            case 'Cancelled':
                $badge = 'danger';
                break;
            default:
                $badge = 'warning';
                break;
        }

        // Assuming 'date' includes datetime, extract date and time parts
        $dateTime = date_create($appt['date']);
        $dateFormatted = $dateTime ? date_format($dateTime, 'l, F j, Y') : htmlspecialchars($appt['date']);
        $timeFormatted = $dateTime ? date_format($dateTime, 'g:i A') : 'N/A';
    ?>
    <tr>
        <td><?php echo htmlspecialchars($appt['name']); ?></td>
        <td><?php echo $dateFormatted; ?></td>
        <td><?php echo $timeFormatted; ?></td>
        <td><span class="badge badge-<?php echo $badge; ?>"><?php echo $status; ?></span></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-muted text-center">No appointments found.</td>
    </tr>
<?php endif; ?>
</tbody>


          </table>
        </div>
      </section>

    </div>
  </main>

  <script>
    // Mobile menu toggle
    const menuBtn = document.getElementById('menu-btn');
    const nav = document.getElementById('main-nav');
    menuBtn?.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('active');
      menuBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  </script>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
