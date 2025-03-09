<?php
include_once '../db.php';

if (isset($_GET['lesson_activity_id'])) {
    $stmt = $pdo->prepare("DELETE FROM lesson_activity WHERE lesson_activity_id = :lesson_activity_id");
    $stmt->execute(['lesson_activity_id' => $_GET['lesson_activity_id']]);
    echo "Template unlinked from lesson successfully!";
}

header("Location: list_lesson_activities.php");
exit;
?>
