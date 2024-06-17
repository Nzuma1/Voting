<?php
session_start();
include('connect.php');

// Check if the user has already voted
if ($_SESSION['status'] == 1) {
    echo '<script>
        alert("You have already voted. You cannot vote more than once.");
        window.location="../partials/dashboard.php";
    </script>';
    exit(); // Stop the script here if the user has already voted
}

$votes = $_POST['groupvotes'];
$totalvotes = $votes + 1;
$gid = $_POST['groupid'];
$uid = $_SESSION['id'];

// Begin the transaction
mysqli_begin_transaction($con);

try {
    // Update the group's vote count
    $updateGroupVotes = mysqli_query($con, "UPDATE userdata SET votes = '$totalvotes' WHERE id = '$gid'");
    if (!$updateGroupVotes) {
        throw new Exception('Failed to update group votes.');
    }

    // Update the user's voting status
    $updateUserStatus = mysqli_query($con, "UPDATE userdata SET status = 1 WHERE id = '$uid'");
    if (!$updateUserStatus) {
        throw new Exception('Failed to update user status.');
    }

    // Commit the transaction
    mysqli_commit($con);

    // Refresh session data
    $_SESSION['status'] = 1;
    $groupDataQuery = mysqli_query($con, "SELECT username, photo, votes, id FROM userdata WHERE standard = 'group'");
    $_SESSION['group'] = mysqli_fetch_all($groupDataQuery, MYSQLI_ASSOC);

    // Calculate the new total votes and update percentages
    $totalVotes = array_sum(array_column($_SESSION['group'], 'votes'));
    foreach ($_SESSION['group'] as $index => $group) {
        $votePercentage = $totalVotes > 0 ? ($group['votes'] / $totalVotes) * 100 : 0;
        $_SESSION['group'][$index]['votePercentage'] = $votePercentage;
    }

    // Redirect to the dashboard with a success message
    echo '<script>
        alert("Voting successful");
        window.location = "../partials/dashboard.php";
    </script>';
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    mysqli_rollback($con);

    // Redirect to the dashboard with an error message
    echo '<script>
        alert("Technical error! Please try voting again later.");
        window.location = "../partials/dashboard.php";
    </script>';
}