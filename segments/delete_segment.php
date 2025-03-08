<?php
include_once '../db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM segment WHERE segment_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}

header("Location: list_segments.php");
exit;
?>
