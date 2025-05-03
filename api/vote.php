<?php
session_start();
include("connect.php");

// Check if votes and gid are set and valid
if (isset($_POST['gid'])) {
    $gid = intval($_POST['gid']); // Convert gid to integer for safety
    $userid = $_SESSION['userdata']['id'];

    // Check if the users table exists
    $tableCheckQuery = "SHOW TABLES LIKE 'user'";
    $tableExists = mysqli_query($connect, $tableCheckQuery);
    if (mysqli_num_rows($tableExists) == 0) {
        die("The table 'users' does not exist in the database.");
    }

    // Update the votes for the selected group
    $updateVotesQuery = "UPDATE groups SET votes = votes + 1 WHERE id = '$gid'";
    $updateStatusQuery = "UPDATE user SET status = 1 WHERE id = '$userid'";

    // Execute queries and handle success or failure
    if (mysqli_query($connect, $updateVotesQuery) && mysqli_query($connect, $updateStatusQuery)) {
        // Update session data
        $_SESSION['userdata']['status'] = 1;
        foreach ($_SESSION['groupsdata'] as &$group) {
            if ($group['id'] == $gid) {
                $group['votes'] += 1;
                break;
            }
        }
        // Redirect back to dashboard with success message
        echo '<script>
                alert("Vote cast successfully!");
                window.location="../routes/dashboard.php";
              </script>';
    } else {
        // Redirect back to dashboard with error message
        echo '<script>
                alert("Failed to cast vote. Please try again.");
                window.location="../routes/dashboard.php";
              </script>';
    }
} else {
    // Debugging output to understand why the request is invalid
    echo '<script>
            alert("Invalid request: ' . json_encode($_POST) . '");
            window.location="../routes/dashboard.php";
          </script>';
    exit();
}
?>
