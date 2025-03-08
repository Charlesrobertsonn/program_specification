<?php
include_once '../db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM program WHERE program_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}

header("Location: list_programs.php");
exit;
