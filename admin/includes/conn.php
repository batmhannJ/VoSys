<?php
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    try {
        // Enable MySQLi exceptions
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Create a connection to the database
		$conn = new mysqli('localhost', 'u247141684_vosys', 'vosysOlshco5', 'u247141684_votesystem');

        echo "Connected successfully"; // Display success message if no exceptions are thrown
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage(); // Display exception message if an exception occurs
    }
?>
