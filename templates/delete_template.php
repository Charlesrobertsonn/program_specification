<?php
include_once '../db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM activity_template WHERE template_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    echo "Template deleted successfully!";
}

header("Location: list_templates.php");
exit;
?>
