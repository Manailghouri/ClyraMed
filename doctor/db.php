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
    $doctorName = $_POST['doctorName'];
    $doctorAge = $_POST['doctorAge'];
    $doctorEmail = $_POST['doctorEmail'];
    $doctorGender = $_POST['doctorGender'];
    $doctorPhoneNumber = $_POST['doctorPhoneNumber'];
    $doctorSpecialization = $_POST['doctorSpecialization']; // FIXED HERE
    $doctorAvailability = $_POST['doctorAvailability'];
    $doctorAvailabilityDate = $_POST['doctorAvailabilityDate'];
    $doctorAvailabilityTime = $_POST['doctorAvailabilityTime'];
    $doctorCity = $_POST['doctorCity'];

   $addDoctorQuery = "INSERT INTO doctors (
    doctorName, doctorAge, doctorEmail, doctorGender, doctorPhoneNumber,
    doctorSpecialization, doctorAvailability, doctorAvailabilityDate, doctorAvailabilityTime, doctorCity
) VALUES (
    '$doctorName', '$doctorAge', '$doctorEmail', '$doctorGender', '$doctorPhoneNumber',
    '$doctorSpecialization', '$doctorAvailability', '$doctorAvailabilityDate', '$doctorAvailabilityTime', '$doctorCity'
)";



    $result = mysqli_query($conn, $addDoctorQuery);

    if ($result) {
        echo "<script>window.location.href = 'dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
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
// Edit Doctor Details
if (isset($_POST['editDoctor'])) {
    $doctorId = intval($_POST['doctorId']);

    // Sanitize inputs
    $doctorName = mysqli_real_escape_string($conn, $_POST['doctorName']);
    $doctorAge = isset($_POST['doctorAge']) && is_numeric($_POST['doctorAge']) ? intval($_POST['doctorAge']) : "NULL";
    $doctorEmail = mysqli_real_escape_string($conn, $_POST['doctorEmail']);
    $doctorGender = mysqli_real_escape_string($conn, $_POST['doctorGender']);
    $doctorPhoneNumber = mysqli_real_escape_string($conn, $_POST['doctorPhoneNumber']);
    $doctorSpecialization = mysqli_real_escape_string($conn, $_POST['doctorSpecialization']);
    $doctorCity = mysqli_real_escape_string($conn, $_POST['doctorCity']);
    $doctorAddress1 = mysqli_real_escape_string($conn, $_POST['doctorAddress1']);
    $doctorAddress2 = mysqli_real_escape_string($conn, $_POST['doctorAddress2']);
    $doctorState = mysqli_real_escape_string($conn, $_POST['doctorState']);
    $doctorCountry = mysqli_real_escape_string($conn, $_POST['doctorCountry']);
    $doctorExperience = isset($_POST['doctorExperience']) && is_numeric($_POST['doctorExperience']) ? intval($_POST['doctorExperience']) : "NULL";
    $doctorLocation = mysqli_real_escape_string($conn, $_POST['doctorLocation']);
    $doctorHospital = mysqli_real_escape_string($conn, $_POST['doctorHospital']);
    $doctorDOB = mysqli_real_escape_string($conn, $_POST['doctorDOB']); // Expected format YYYY-MM-DD


    $result =  mysqli_query($conn, "UPDATE doctors SET  
         doctorName = '$doctorName', 
            doctorAge = $doctorAge,
            doctorEmail = '$doctorEmail',
            doctorGender = '$doctorGender',
            doctorPhoneNumber = '$doctorPhoneNumber',
            doctorSpecialization = '$doctorSpecialization',
            doctorAddress1 = '$doctorAddress1',
            doctorAddress2 = '$doctorAddress2',
            doctorState = '$doctorState',
            doctorCountry = '$doctorCountry',
            doctorExperience = $doctorExperience,
            doctorLocation = '$doctorLocation',
            doctorHospital = '$doctorHospital',
            doctorDOB = '$doctorDOB'        WHERE doctorId = $doctorId");
    if ($result) {
        echo "<script>
        alert('Doctor Updated Successfully!');
        window.location.href='doctor-dashboard.php';
        </script>";
    } else {
        echo "Update Error: " . mysqli_error($conn);
    }

}

//Delete Doctor Details
if(isset($_POST['deleteDoctor'])){
    $id = $_POST['doctorId'];
    
    $deleteDoctorQuery = mysqli_query($conn,"DELETE From doctors Where doctorId = '$id'");
    
    if($deleteDoctorQuery){
      echo "<script>
      alert('Doctor Deleted Successfully!')
      location.assign('doctor.php')
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




// Delete Doctor Details
if (isset($_POST['deleteDoctor'])) {
    $id = intval($_POST['doctorId']);
    $deleteDoctorQuery = "DELETE FROM doctors WHERE doctorId = $id";

    if (mysqli_query($conn, $deleteDoctorQuery)) {
        echo "<script>
        alert('Doctor Deleted Successfully!');
        location.assign('doctor.php');
        </script>";
    } else {
        echo "Delete Error: " . mysqli_error($conn);
    }
}


?>
