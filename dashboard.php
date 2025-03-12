<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

echo "<h2>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h2>";
echo "<p>Role: " . htmlspecialchars($_SESSION['role']) . "</p>";
?>

<!-- Logout Button -->
<a href="logout.php">Logout</a>