<?php
include_once '../db.php';

if (isset($_GET['id'])) {
    $lesson_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($lesson_id) {
        $stmt = $pdo->prepare("DELETE FROM lesson WHERE lesson_id = :id");
        $stmt->execute(['id' => $lesson_id]);
        echo "Lesson deleted successfully!";
    } else {
        echo "Invalid lesson ID!";
    }
}

header("Location: list_lessons.php");
exit();
