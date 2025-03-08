<?php
include_once '../db.php';

// Fetch segments for the dropdown
$segments = $pdo->query("SELECT * FROM segment")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $segment_id = filter_input(INPUT_POST, 'segment_id', FILTER_VALIDATE_INT);
    $sequence_name = filter_input(INPUT_POST, 'sequence_name', FILTER_SANITIZE_STRING);
    $sequence_description = filter_input(INPUT_POST, 'sequence_description', FILTER_SANITIZE_STRING);
    $sequence_data = filter_input(INPUT_POST, 'sequence_data', FILTER_SANITIZE_STRING);

    if ($segment_id && $sequence_name) {
        $stmt = $pdo->prepare("
            INSERT INTO lesson_sequence (segment_id, sequence_name, sequence_description, sequence_data)
            VALUES (:segment_id, :sequence_name, :sequence_description, :sequence_data)
        ");
        $stmt->execute([
            'segment_id' => $segment_id,
            'sequence_name' => $sequence_name,
            'sequence_description' => $sequence_description,
            'sequence_data' => $sequence_data
        ]);
        echo "<p style='color: green;'>Lesson Sequence added successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: Segment and Sequence Name are required!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson Sequence</title>
</head>
<body>
    <h2>Add New Lesson Sequence</h2>
    <form method="POST">
        <label>Segment:</label>
        <select name="segment_id" required>
            <option value="">Select Segment</option>
            <?php foreach ($segments as $segment): ?>
                <option value="<?= htmlspecialchars($segment['segment_id']) ?>">
                    <?= htmlspecialchars($segment['segment_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Sequence Name:</label> 
        <input type="text" name="sequence_name" required><br><br>

        <label>Description:</label> 
        <textarea name="sequence_description"></textarea><br><br>

        <label>Sequence Data:</label> 
        <textarea name="sequence_data"></textarea><br><br>

        <input type="submit" value="Add Sequence">
    </form>

    <a href="list_lesson_sequences.php">Back to Sequence List</a>
</body>
</html>
