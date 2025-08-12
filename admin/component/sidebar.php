<?php
// sidebar.php
?>

<!-- Sidebar Styles & Icons -->
<link rel="stylesheet" href="sidebar.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

<!-- Sidebar Structure -->
<div class="sidebar">
    <!-- Top navigation links -->
    <ul class="sidebar--items">
        <li>
            <a href="/Care/admin/dashboard.php" id="active--link">
                <span class="icon icon-1"><i class="ri-layout-grid-line"></i></span>
                <span class="sidebar--item">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/Care/admin/doctor.php">
                <span class="icon icon-3"><i class="ri-user-2-line"></i></span>
                <span class="sidebar--item" style="white-space: nowrap;">Reliable Doctor</span>
            </a>
        </li>
        <li>
            <a href="/Care/admin/patient.php">
                <span class="icon icon-4"><i class="ri-user-line"></i></span>
                <span class="sidebar--item">Patients</span>
            </a>
        </li>
        <li>
            <a href="/Care/admin/appointments.php">
                <span class="icon icon-5"><i class="ri-calendar-2-line"></i></span>
                <span class="sidebar--item">Appointments</span>
            </a>
        </li>
    </ul>

    <!-- Bottom section: settings and logout -->
    <ul class="sidebar-bottom-items">
        <li>
            <a href="\Care\logout.php">
                <span class="icon icon-8"><i class="ri-logout-box-r-line"></i></span>
                <span class="sidebar--item">Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Sidebar Toggle Script -->
<script src="./sidebar.js"></script>
