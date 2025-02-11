<?php
session_start();

// Database configuration
$host     = 'localhost';
$user     = 'root';
$password = '';
$dbname   = 'toko';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>