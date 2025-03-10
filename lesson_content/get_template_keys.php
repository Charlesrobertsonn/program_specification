<?php
header("Content-Type: application/json");
require '../db.php'; // Ensure database connection

$template_id = isset($_GET['template_id']) ? intval($_GET['template_id']) : 0;

if ($template_id) {
    // Fetch distinct content keys from the lesson_content table
    $stmt = $pdo->prepare("SELECT DISTINCT content_key FROM lesson_content WHERE template_id = ?");
    $stmt->execute([$template_id]);
    $keys = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Debugging: Log result to error_log
    error_log("Template ID: $template_id, Keys Found: " . json_encode($keys));

    if (!empty($keys)) {
        echo json_encode($keys);
    } else {
        echo json_encode(["error" => "No content keys found"]);
    }
} else {
    echo json_encode(["error" => "Invalid template ID"]);
}
?>
