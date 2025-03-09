<?php
include_once '../db.php';
$content_id = $_GET['id'];
$pdo->prepare("DELETE FROM lesson_content WHERE content_id = :content_id")->execute(['content_id' => $content_id]);
echo "Content deleted successfully!";
header("Location: list_lesson_content.php");
?>