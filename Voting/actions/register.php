<?php
session_start(); // Start the session at the beginning of the script
include('connect.php');

// Retrieve user input from the form
$username = $_POST['username'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$image = $_FILES['photo']['name'];
$tmp_name = $_FILES['photo']['tmp_name'];
$std = $_POST['std'];

// Check if the passwords match
if ($password != $cpassword) {
      echo '<script>
        alert("Passwords do not match");
        window.location="../partials/registration.php";
        </script>';
      exit();
}

// Hash the password before storing it
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Move the uploaded file to the 'uploads' directory
move_uploaded_file($tmp_name, "../uploads/$image");

// Insert the new user into the database with the hashed password
$sql = "INSERT INTO userdata (username, mobile, password, photo, standard, status, votes) 
        VALUES ('$username', '$mobile', '$hashed_password', '$image', '$std', 0, 0)";

$result = mysqli_query($con, $sql);

if ($result) {
      // Initialize the session variable as an array
      $_SESSION['group'] = [];
      // Fetch the group data from the userdata table where the standard is 'group'
      $groupQuery = "SELECT * FROM userdata WHERE standard = 'group'";
      $groupResult = mysqli_query($con, $groupQuery);
      if ($groupResult) {
            while ($groupRow = mysqli_fetch_assoc($groupResult)) {
                  $_SESSION['group'][] = $groupRow; // Populate the session variable with group data
            }
      }

      echo '<script>
        alert("Registration successful");
        window.location="../"; // Redirect to the log in page
        </script>';
} else {
      die(mysqli_error($con));
}