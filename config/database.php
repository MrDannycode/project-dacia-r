<?php
$host = "localhost";
$dbname = "dacia_website";
$user = "postgres";
$pass = "psql98dan5";

try {
    $conn  = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
