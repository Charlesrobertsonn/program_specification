<?php
include_once '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$stmt = $pdo->prepare("SELECT * FROM lesson WHERE lesson_id = :id");
$stmt->execute(['id' => $_GET['id']]);
$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) {
    die("Lesson not found.");
}

// Fetch segments for dropdown
$segments = $pdo->query("SELECT * FROM segment")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $lesson_id = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);
    $lesson_title = filter_input(INPUT_POST, 'lesson_title', FILTER_SANITIZE_STRING);
    $lesson_description = filter_input(INPUT_POST, 'lesson_description', FILTER_SANITIZE_STRING);
    $lesson_procedure = filter_input(INPUT_POST, 'lesson_procedure', FILTER_SANITIZE_STRING);
    $sequence_order = filter_input(INPUT_POST, 'sequence_order', FILTER_VALIDATE_INT);
    $lesson_type = filter_input(INPUT_POST, 'lesson_type', FILTER_SANITIZE_STRING);
    $lesson_objective = filter_input(INPUT_POST, 'lesson_objective', FILTER_SANITIZE_STRING);

    if ($lesson_id && $lesson_title && $sequence_order) {
        $stmt = $pdo->prepare("
            UPDATE lesson 
            SET lesson_title = :lesson_title, lesson_description = :lesson_description,
                lesson_procedure = :lesson_procedure, sequence_order = :sequence_order, 
                lesson_type = :lesson_type, lesson_objective = :lesson_objective
            WHERE lesson_id = :lesson_id
        ");
        $stmt->execute([
            'lesson_title' => $lesson_title,
            'lesson_description' => $lesson_description,
            'lesson_procedure' => $lesson_procedure,
            'sequence_order' => $sequence_order,
            'lesson_type' => $lesson_type,
            'lesson_objective' => $lesson_objective,
            'lesson_id' => $lesson_id
        ]);
        echo "Lesson updated successfully!";
    } else {
        echo "Invalid input!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lesson</title>
</head>
<body>
    <h2>Edit Lesson</h2>
    <form method="POST">
        <input type="hidden" name="lesson_id" value="<?= htmlspecialchars($lesson['lesson_id']) ?>">
        Segment:
        <select name="segment_id" required>
            <?php foreach ($segments as $segment): ?>
                <option value="<?= $segment['segment_id'] ?>" <?= $segment['segment_id'] == $lesson['segment_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($segment['segment_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        Title: <input type="text" name="lesson_title" value="<?=htmlspecialchars($lesson['lesson_title'])?>" required><br><br>
        Description: <textarea name="lesson_description"><?=htmlspecialchars($lesson['lesson_description'])?></textarea><br><br>
        Procedure: <textarea name="lesson_procedure"><?=htmlspecialchars($lesson['lesson_procedure'])?></textarea><br><br>
        Order: <input type="number" name="sequence_order" value="<?= $lesson['sequence_order'] ?>" required><br><br>
        Type: <input type="text" name="lesson_type" value="<?=htmlspecialchars($lesson['lesson_type'])?>" required><br><br>
        Objective: <textarea name="lesson_objective"><?=htmlspecialchars($lesson['lesson_objective'])?></textarea><br><br>

        <input type="submit" value="Update Lesson">
    </form>
    <a href="list_lessons.php">Back to Lesson List</a>
</body>
</html>
