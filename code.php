<?php
// db Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "care";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


//Add Doctor Details
if (isset($_POST['addDoctor'])) {
    $doctorName = mysqli_real_escape_string($conn, $_POST['doctorName']);
    $doctorAge = mysqli_real_escape_string($conn, $_POST['doctorAge']);
    $doctorEmail = mysqli_real_escape_string($conn, $_POST['doctorEmail']);
    $doctorPassword = mysqli_real_escape_string($conn, $_POST['doctorPassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    $doctorGender = mysqli_real_escape_string($conn, $_POST['doctorGender']);
    $doctorPhoneNumber = mysqli_real_escape_string($conn, $_POST['doctorPhoneNumber']);
    $doctorSpecialization = mysqli_real_escape_string($conn, $_POST['doctorSpecialization']);
    $doctorAvailability = mysqli_real_escape_string($conn, $_POST['doctorAvailability']);
    $doctorAvailabilityDate = mysqli_real_escape_string($conn, $_POST['doctorAvailabilityDate']);
    $doctorAvailabilityTime = mysqli_real_escape_string($conn, $_POST['doctorAvailabilityTime']);
    $doctorCity = mysqli_real_escape_string($conn, $_POST['doctorCity']);

    // Check if passwords match
    if ($doctorPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT doctorId FROM doctors WHERE doctorEmail = '$doctorEmail'";
    $checkResult = mysqli_query($conn, $checkEmailQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Email already exists!'); window.history.back();</script>";
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($doctorPassword, PASSWORD_DEFAULT);

    $addDoctorQuery = "INSERT INTO doctors (
        doctorName, doctorAge, doctorEmail, doctorPassword, doctorGender, doctorPhoneNumber,
        doctorSpecialization, doctorAvailability, doctorAvailabilityDate, doctorAvailabilityTime, doctorCity
    ) VALUES (
        '$doctorName', '$doctorAge', '$doctorEmail', '$hashedPassword', '$doctorGender', '$doctorPhoneNumber',
        '$doctorSpecialization', '$doctorAvailability', '$doctorAvailabilityDate', '$doctorAvailabilityTime', '$doctorCity'
    )";

    $result = mysqli_query($conn, $addDoctorQuery);

    if ($result) {
        echo "<script>alert('Doctor added successfully!')
        location.assign('doctor.php');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}

//view doctor details
$viewDoctorQuery = "SELECT * FROM doctors";
$viewDoctorResult = mysqli_query($conn, $viewDoctorQuery);
// Error check
if (!$viewDoctorResult) {
    die("Query failed: " . mysqli_error($conn));

}

// Edit Doctor Details
if(isset($_POST['editDoctor'])){
    $doctorId = mysqli_real_escape_string($conn, $_POST['doctorId']);
    $doctorName = mysqli_real_escape_string($conn, $_POST['doctorName']);
    $doctorAge = mysqli_real_escape_string($conn, $_POST['doctorAge']);
    $doctorEmail = mysqli_real_escape_string($conn, $_POST['doctorEmail']);
    $doctorGender = mysqli_real_escape_string($conn, $_POST['doctorGender']);
    $doctorPhoneNumber = mysqli_real_escape_string($conn, $_POST['doctorPhoneNumber']);
    $doctorSpecialization = mysqli_real_escape_string($conn, $_POST['doctorSpecialization']);
    $doctorAvailability = mysqli_real_escape_string($conn, $_POST['doctorAvailability']);
    $doctorAvailabilityDate = mysqli_real_escape_string($conn, $_POST['doctorAvailabilityDate']);
    $doctorAvailabilityTime = mysqli_real_escape_string($conn, $_POST['doctorAvailabilityTime']);
    $doctorCity = mysqli_real_escape_string($conn, $_POST['doctorCity']);

    // Check if email already exists for other doctors
    $checkEmailQuery = "SELECT doctorId FROM doctors WHERE doctorEmail = '$doctorEmail' AND doctorId != '$doctorId'";
    $checkResult = mysqli_query($conn, $checkEmailQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Email already exists!'); window.history.back();</script>";
        exit();
    }

    $result = mysqli_query($conn, "UPDATE doctors SET  
        doctorName='$doctorName', 
        doctorAge='$doctorAge' , 
        doctorEmail='$doctorEmail' , 
        doctorGender='$doctorGender' , 
        doctorPhoneNumber='$doctorPhoneNumber' , 
        doctorSpecialization='$doctorSpecialization' , 
        doctorAvailability='$doctorAvailability' , 
        doctorAvailabilityDate ='$doctorAvailabilityDate' , 
        doctorAvailabilityTime = '$doctorAvailabilityTime'  , 
        doctorCity='$doctorCity' WHERE 
        doctorId=$doctorId");

    if ($result) {
        echo "<script>
        alert('Doctor Updated Successfully!');
        location.assign('doctor.php');';
        </script>";
    } else {
        echo "<script>alert('Update Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}

//Delete Doctor Details
if(isset($_POST['deleteDoctor'])){
    $id = mysqli_real_escape_string($conn, $_POST['doctorId']);
    
    $deleteDoctorQuery = mysqli_query($conn,"DELETE From doctors Where doctorId = '$id'");
    
    if($deleteDoctorQuery){
      echo "<script>
      alert('Doctor Deleted Successfully!')
      location.assign('admin/doctor.php')
      </script>";
    } else {
      echo "<script>
      alert('Error deleting doctor: " . mysqli_error($conn) . "')
      location.assign('doctor.php');
      </script>";
    }
}


// Add Patient Details
if(isset($_POST['addPatient'])){
    $patientName = mysqli_real_escape_string($conn, $_POST['patientName']);
    $patientAge = mysqli_real_escape_string($conn, $_POST['patientAge']);
    $patientEmail = mysqli_real_escape_string($conn, $_POST['patientEmail']);
    $patientPhone = mysqli_real_escape_string($conn, $_POST['patientPhone']);
    $patientGender = mysqli_real_escape_string($conn, $_POST['patientGender']);

    $addPatientQuery = "INSERT INTO patients (patientName, patientAge, patientEmail, patientPhone, patientGender)
                        VALUES ('$patientName', '$patientAge', '$patientEmail', '$patientPhone', '$patientGender')";

    $result = mysqli_query($conn, $addPatientQuery);

    if($result){
        header('location:dashboard.php');
        exit;
    } else {
        echo "Query Error: " . mysqli_error($conn);
    }
}

// Edit Patient Details
if(isset($_POST['editPatient'])){
    $patientId = $_POST['patientId'];
    $patientName = $_POST['patientName'];
    $patientAge = $_POST['patientAge'];
    $patientEmail = $_POST['patientEmail'];
    $patientPhone = $_POST['patientPhone'];
    $patientGender = $_POST['patientGender'];

    $editPatientQuery = "UPDATE patients SET patientName ='$patientName', patientAge = '$patientAge', patientEmail ='$patientEmail', patientPhone ='$patientPhone', patientGender ='$patientGender' WHERE patientId = '$patientId'";
    $result = mysqli_query($conn, $editPatientQuery);
    if($result){
      header('location:patient.php');
    }
}

//Delete Patient Details
if(isset($_POST['deletePatient'])){
    $patientId = $_POST['patientId'];
    
    $deletePatientQuery = mysqli_query($conn,"DELETE From patients Where patientId = '$patientId'");
    
    if($deletePatientQuery){
      echo "<script>
      alert('Patient Deleted Successfully!')
      location.assign('patient.php')
      </script>";
    }
}


// Handle Appointment Form Submission
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $query = "INSERT INTO contact_form (name, email, number, date) VALUES ('$name', '$email', '$number', '$date')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $message[] = "Appointment made successfully!";
    } else {
        $message[] = "Error: " . mysqli_error($conn);
    }
}

// Delete User
if (isset($_POST['deleteUser'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    
    $deleteUserQuery = "DELETE FROM users WHERE id = '$userId'";
    $result = mysqli_query($conn, $deleteUserQuery);
    
    if ($result) {
        echo "User deleted successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete Appointment
if (isset($_POST['deleteAppointment'])) {
    $appointmentId = mysqli_real_escape_string($conn, $_POST['appointmentId']);
    
    $deleteAppointmentQuery = "DELETE FROM contact_form WHERE id = '$appointmentId'";
    $result = mysqli_query($conn, $deleteAppointmentQuery);
    
    if ($result) {
        echo "Appointment deleted successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>