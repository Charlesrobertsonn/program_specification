<?php
include_once '../db.php';

// Fetch lessons and templates
$lessons = $pdo->query("SELECT * FROM lesson")->fetchAll(PDO::FETCH_ASSOC);
$templates = $pdo->query("SELECT * FROM activity_template")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO lesson_content (lesson_id, template_id, content_key, content_value) 
        VALUES (:lesson_id, :template_id, :content_key, :content_value)
    ");
    $stmt->execute([
        'lesson_id' => $_POST['lesson_id'],
        'template_id' => $_POST['template_id'],
        'content_key' => $_POST['content_key'],
        'content_value' => $_POST['content_value']
    ]);
    echo "Lesson content added!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lesson Content</title>
</head>
<body>
    <h2>Add Lesson Content</h2>
    <form method="POST">
        Lesson:
        <select name="lesson_id" required>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= $lesson['lesson_id'] ?>"><?= htmlspecialchars($lesson['lesson_title']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        Template:
        <select name="template_id" required>
            <?php foreach ($templates as $template): ?>
                <option value="<?= $template['template_id'] ?>"><?= htmlspecialchars($template['template_name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        Content Key: <input type="text" name="content_key" required><br><br>
        Content Value: <textarea name="content_value"></textarea><br><br>

        <input type="submit" value="Add Content">
    </form>
    <a href="list_lesson_content.php">Back to Content List</a>
</body>
</html>
