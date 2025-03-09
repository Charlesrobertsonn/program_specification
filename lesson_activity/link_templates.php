<?php
include_once '../db.php';

// Fetch lessons
$lessons = $pdo->query("SELECT * FROM lesson")->fetchAll(PDO::FETCH_ASSOC);

// Fetch templates
$templates = $pdo->query("SELECT * FROM activity_template")->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $lesson_id = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);
    $template_id = filter_input(INPUT_POST, 'template_id', FILTER_VALIDATE_INT);

    if ($lesson_id === false || $template_id === false) {
        $message = "Invalid input! Please select valid options.";
    } else {
        // Check if this lesson already has the template linked
        $stmt = $pdo->prepare("SELECT * FROM lesson_activity WHERE lesson_id = :lesson_id AND template_id = :template_id");
        $stmt->execute(['lesson_id' => $lesson_id, 'template_id' => $template_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            $message = "This template is already linked to the selected lesson!";
        } else {
            // Insert new link
            $stmt = $pdo->prepare("INSERT INTO lesson_activity (lesson_id, template_id) VALUES (:lesson_id, :template_id)");
            $stmt->execute(['lesson_id' => $lesson_id, 'template_id' => $template_id]);
            header("Location: list_lesson_activities.php"); // Redirect after success
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Link Template to Lesson</title>
</head>
<body>
    <h2>Link Template to Lesson</h2>

    <?php if (!empty($message)): ?>
        <p style="color: red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Lesson:</label>
        <select name="lesson_id" required>
            <option value="">Select a Lesson</option>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= htmlspecialchars($lesson['lesson_id']) ?>"><?= htmlspecialchars($lesson['lesson_title']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Template:</label>
        <select name="template_id" required>
            <option value="">Select a Template</option>
            <?php foreach ($templates as $template): ?>
                <option value="<?= htmlspecialchars($template['template_id']) ?>"><?= htmlspecialchars($template['template_name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" value="Link Template">
    </form>

    <a href="list_lesson_activities.php">View Linked Templates</a>
</body>
</html>

