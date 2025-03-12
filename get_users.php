<?php
include 'db.php';

try {
    $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>