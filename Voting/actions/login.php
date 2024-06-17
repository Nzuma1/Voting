<?php
session_start();
include('connect.php');

$username = $_POST['username'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$std = $_POST['std'];

// Fetch the hashed password from the database
$sql = "SELECT * FROM userdata WHERE username='$username' AND mobile='$mobile' AND standard='$std'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_array($result);

    // Verify the password
    if (password_verify($password, $data['password'])) {
        // Password is correct
        // Continue with your session code
        $_SESSION['id'] = $data['id'];
        $_SESSION['status'] = $data['status'];
        $_SESSION['data'] = $data;

        // Redirect to dashboard
        echo '<script>
            window.location="../partials/dashboard.php";
            </script>';
    } else {
        // Password is incorrect
        echo '<script>
            alert("Invalid Credentials");
            window.location="../";
            </script>';
    }
} else {
    // No user found
    echo '<script>
        alert("Invalid Credentials");
        window.location="../";
        </script>';
}
