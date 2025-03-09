<?php
include_once '../db.php';

// Fetch lessons and templates
$lessons = $pdo->query("SELECT * FROM lesson")->fetchAll(PDO::FETCH_ASSOC);
$templates = $pdo->query("SELECT * FROM activity_template")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $lesson_id = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);
    $template_id = filter_input(INPUT_POST, 'template_id', FILTER_VALIDATE_INT);
    $content_key = filter_input(INPUT_POST, 'content_key', FILTER_SANITIZE_STRING);
    $content_value = filter_input(INPUT_POST, 'content_value', FILTER_SANITIZE_STRING);

    if ($lesson_id && $template_id && $content_key && $content_value) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO lesson_content (lesson_id, template_id, content_key, content_value) 
                VALUES (:lesson_id, :template_id, :content_key, :content_value)
            ");
            $stmt->execute([
                'lesson_id' => $lesson_id,
                'template_id' => $template_id,
                'content_key' => $content_key,
                'content_value' => $content_value
            ]);
            echo "Lesson content added successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid input! Please check all fields.";
    }
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
        <label>Lesson:</label>
        <select name="lesson_id" required>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= htmlspecialchars($lesson['lesson_id']) ?>"><?= htmlspecialchars($lesson['lesson_title']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Template:</label>
        <select name="template_id" required>
            <?php foreach ($templates as $template): ?>
                <option value="<?= htmlspecialchars($template['template_id']) ?>"><?= htmlspecialchars($template['template_name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Content Key:</label>
        <input type="text" name="content_key" required><br><br>

        <label>Content Value:</label>
        <textarea name="content_value" required></textarea><br><br>

        <input type="submit" value="Add Content">
    </form>
    <a href="list_lesson_content.php">Back to Content List</a>
</body>
</html>
