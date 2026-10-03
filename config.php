<?php

mysqli_report(MYSQLI_REPORT_OFF);


$conn = new mysqli('localhost', 'root', '', 'laundry_db');


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>
