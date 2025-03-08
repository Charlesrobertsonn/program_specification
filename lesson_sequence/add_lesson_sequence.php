<?php
include_once '../db.php';

// Fetch segments for the dropdown
$segments = $pdo->query("SELECT * FROM segment")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO lesson_sequence (segment_id, sequence_name, sequence_description, sequence_data)
        VALUES (:segment_id, :sequence_name, :sequence_description, :sequence_data)
    ");
    $stmt->execute([
        'segment_id' => $_POST['segment_id'],
        'sequence_name' => $_POST['sequence_name'],
        'sequence_description' => $_POST['sequence_description'],
        'sequence_data' => $_POST['sequence_data']
    ]);
    echo "Lesson Sequence added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lesson Sequence</title>
</head>
<body>
    <h2>Add New Lesson Sequence</h2>
    <form method="POST">
        Segment:
        <select name="segment_id" required>
            <option value="">Select Segment</option>
            <?php foreach ($segments as $segment): ?>
                <option value="<?= $segment['segment_id'] ?>"><?= htmlspecialchars($segment['segment_name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        Sequence Name: <input type="text" name="sequence_name" required><br><br>
        Description: <textarea name="sequence_description"></textarea><br><br>
        Sequence Data: <textarea name="sequence_data"></textarea><br><br>
        <input type="submit" value="Add Sequence">
    </form>

    <a href="list_lesson_sequences.php">Back to Sequence List</a>
</body>
</html>
