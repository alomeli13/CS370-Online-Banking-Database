<?php
// =================================================================
// DATABASE CONNECTION CONFIGURATION
// Teammates: If your local database has a different password
// or username (like 'root'), change these four variables.
// =================================================================
$db_host = "localhost";
$db_user = "banking_user";
$db_pass = "banking_user";
$db_name = "bank_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>