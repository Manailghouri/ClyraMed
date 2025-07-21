<?php
// db Connection

$servername = "localhost";
$username = "root";
$password = "";
$database = "care";

// Create connection
$conn = new mysqli($servername , $username , $password , $database );

// Check connection
if ($conn->connect_error) {
  echo("Connection failed: " . $conn->connect_error);
}
// echo("Connected successfully"); 


//Add Doctor
if(isset($_POST['addDoctorBtn'])){
  $userName = $_POST['userName'];
  $age = $_POST['age'];
  $email = $_POST['email'];
  $gender = $_POST['gender'];
  $phoneNumber = $_POST['phoneNumber'];

  $addPatientQuery = "INSERT INTO users (userName, age , email , gender , phoneNumber) VALUES ('$userName', '$age', '$email' , '$gender' , '$phoneNumber')";
  $result =  mysqli_query($conn, $addPatientQuery);

}

//Delete Doctor
if(isset($Post['deleteDoctorBtn'])){
    $id = $_POST['id'];

    $deleteQuery = mysqli_query($connection,"delete from doctor where id = '$id'");

    if($deleteQuery){
        echo "<script>
        alert('Doctor Deleted Successfully!')
        location.assign('doctor.php')
        </script>";
    }
}



?>