<?php
header("Content-Type: application/json");
require '../db.php'; // Ensure database connection

$template_id = isset($_GET['template_id']) ? intval($_GET['template_id']) : 0;
$content_key = isset($_GET['content_key']) ? $_GET['content_key'] : '';

if ($template_id && $content_key) {
    $stmt = $pdo->prepare("SELECT content_value FROM lesson_content WHERE template_id = ? AND content_key = ? LIMIT 1");
    $stmt->execute([$template_id, $content_key]);
    $content_value = $stmt->fetchColumn();
    
    if ($content_value !== false) {
        echo json_encode(["content_value" => $content_value]);
    } else {
        echo json_encode(["error" => "No default value found"]);
    }
} else {
    echo json_encode(["error" => "Invalid template ID or content key"]);
}
?>
