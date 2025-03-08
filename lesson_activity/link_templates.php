<?php
include_once '../db.php';

// Fetch lessons
$lessons = $pdo->query("SELECT * FROM lesson")->fetchAll(PDO::FETCH_ASSOC);

// Fetch templates
$templates = $pdo->query("SELECT * FROM activity_template")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO lesson_activity (lesson_id, template_id) VALUES (:lesson_id, :template_id)");
    $stmt->execute([
        'lesson_id' => $_POST['lesson_id'],
        'template_id' => $_POST['template_id']
    ]);
    echo "Template linked to lesson successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Link Template to Lesson</title>
</head>
<body>
    <h2>Link Template to Lesson</h2>
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

        <input type="submit" value="Link Template">
    </form>
    <a href="../lesson/list_lessons.php">Back to Lessons</a>
</body>
</html>
