<?php
include_once '../db.php';

// Fetch current data
if (isset($_GET['lesson_activity_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM lesson_activity WHERE lesson_activity_id = :lesson_activity_id");
    $stmt->execute(['lesson_activity_id' => $_GET['lesson_activity_id']]);
    $lesson_activity = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch available templates
$templates = $pdo->query("SELECT * FROM activity_template")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE lesson_activity SET template_id = :template_id WHERE lesson_activity_id = :lesson_activity_id");
    $stmt->execute([
        'template_id' => $_POST['template_id'],
        'lesson_activity_id' => $_POST['lesson_activity_id']
    ]);
    echo "Lesson activity updated successfully!";
    header("Location: list_lesson_activities.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lesson Activity</title>
</head>
<body>
    <h2>Edit Lesson Activity</h2>
    <form method="POST">
        <input type="hidden" name="lesson_activity_id" value="<?= $lesson_activity['lesson_activity_id'] ?>">

        <label>Template:</label>
        <select name="template_id">
            <?php foreach ($templates as $template): ?>
                <option value="<?= $template['template_id'] ?>" <?= $lesson_activity['template_id'] == $template['template_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($template['template_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" value="Update Lesson Activity">
    </form>
    <a href="list_lesson_activities.php">Back to Lesson Activities</a>
</body>
</html>
