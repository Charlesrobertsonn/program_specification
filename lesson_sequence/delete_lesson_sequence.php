<?php
include_once '../db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM lesson_sequence WHERE lesson_sequence_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}

header("Location: list_lesson_sequences.php");
exit;
?>
