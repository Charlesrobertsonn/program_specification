<?php
include_once '../db.php';
$content_id = $_GET['id'];
$content = $pdo->prepare("SELECT * FROM lesson_content WHERE content_id = :content_id");
$content->execute(['content_id' => $content_id]);
$data = $content->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE lesson_content SET content_key = :content_key, content_value = :content_value WHERE content_id = :content_id");
    $stmt->execute([
        'content_key' => $_POST['content_key'],
        'content_value' => $_POST['content_value'],
        'content_id' => $content_id
    ]);
    echo "Content updated successfully!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Lesson Content</title></head>
<body>
    <h2>Edit Lesson Content</h2>
    <form method="POST">
        Content Key: <input type="text" name="content_key" value="<?= htmlspecialchars($data['content_key']) ?>" required><br><br>
        Content Value: <textarea name="content_value" required><?= htmlspecialchars($data['content_value']) ?></textarea><br><br>
        <input type="submit" value="Update Content">
    </form>
    <a href="list_lesson_content.php">Back to Content List</a>
</body>
</html>