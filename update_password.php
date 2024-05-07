<?php
session_start();

if (isset($_POST['email']) && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // Step 1: Establish a database connection
    $connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Step 2: Hash the new password for security
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Step 3: Update the user's password in the database
    $query = "UPDATE voters SET password = ? WHERE email = ?";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare statement failed: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute statement failed: " . mysqli_error($connection));
    }

    // Step 4: Close database connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    // Step 5: Return success response
    $_SESSION['success_message'] = "Password updated successfully";

    // Step 6: Redirect to voters_login.php
    header('Location: voters_login.php');
    exit(); // Make sure to exit after redirecting
} else {
    // If email or new password parameter is missing
    die('Missing email or new password parameter');
}
?>
