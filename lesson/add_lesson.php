<?php
include_once '../db.php';

// Fetch lesson sequences for dropdown
$lesson_sequences = $pdo->query("SELECT * FROM lesson_sequence")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $lesson_sequence_id = filter_input(INPUT_POST, 'lesson_sequence_id', FILTER_VALIDATE_INT);
    $lesson_title = filter_input(INPUT_POST, 'lesson_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lesson_description = filter_input(INPUT_POST, 'lesson_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lesson_procedure = filter_input(INPUT_POST, 'lesson_procedure', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sequence_order = filter_input(INPUT_POST, 'sequence_order', FILTER_VALIDATE_INT);
    $lesson_type = filter_input(INPUT_POST, 'lesson_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lesson_objective = filter_input(INPUT_POST, 'lesson_objective', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Check required fields
    if ($lesson_sequence_id && $lesson_title && $sequence_order) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO lesson (lesson_sequence_id, lesson_title, lesson_description, lesson_procedure, sequence_order, lesson_type, lesson_objective)
                VALUES (:lesson_sequence_id, :lesson_title, :lesson_description, :lesson_procedure, :sequence_order, :lesson_type, :lesson_objective)
            ");
            $stmt->execute([
                'lesson_sequence_id' => $lesson_sequence_id,
                'lesson_title' => $lesson_title,
                'lesson_description' => $lesson_description,
                'lesson_procedure' => $lesson_procedure,
                'sequence_order' => $sequence_order,
                'lesson_type' => $lesson_type,
                'lesson_objective' => $lesson_objective
            ]);
            echo "<p style='color: green;'>Lesson added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Invalid input! Please check required fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson</title>
</head>
<body>
    <h2>Add New Lesson</h2>
    <form method="POST">
        <label>Lesson Sequence:</label> 
        <select name="lesson_sequence_id" required>
            <option value="">Select Lesson Sequence</option>
            <?php foreach ($lesson_sequences as $sequence): ?>
                <option value="<?= htmlspecialchars($sequence['lesson_sequence_id']) ?>">
                    <?= htmlspecialchars($sequence['sequence_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Title:</label> 
        <input type="text" name="lesson_title" required><br><br>

        <label>Description:</label> 
        <textarea name="lesson_description"></textarea><br><br>

        <label>Procedure:</label> 
        <textarea name="lesson_procedure"></textarea><br><br>

        <label>Sequence Order:</label> 
        <input type="number" name="sequence_order" required><br><br>

        <label>Type:</label> 
        <input type="text" name="lesson_type"><br><br>

        <label>Objective:</label> 
        <textarea name="lesson_objective"></textarea><br><br>

        <input type="submit" value="Add Lesson">
    </form>

    <a href="list_lessons.php">Back to Lessons List</a>
</body>
</html>
