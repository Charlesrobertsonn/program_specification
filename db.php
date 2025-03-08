<?php
$host = 'localhost';                // XAMPP default
$dbname = 'program_specification';   // Your new database
$username = 'root';                  // XAMPP default user
$password = '';                      // XAMPP default password (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>