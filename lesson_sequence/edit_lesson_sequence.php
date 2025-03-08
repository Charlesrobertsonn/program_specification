<?php
include_once '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$stmt = $pdo->prepare("SELECT * FROM lesson_sequence WHERE lesson_sequence_id = :id");
$stmt->execute(['id' => $_GET['id']]);
$sequence = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$sequence) {
    die("Sequence not found.");
}

// Fetch segments for dropdown
$segments = $pdo->query("SELECT * FROM segment")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE lesson_sequence 
        SET segment_id = :segment_id, sequence_name = :sequence_name, sequence_description = :sequence_description, sequence_data = :sequence_data
        WHERE lesson_sequence_id = :id
    ");
    $stmt->execute([
        'segment_id' => $_POST['segment_id'],
        'sequence_name' => $_POST['sequence_name'],
        'sequence_description' => $_POST['sequence_description'],
        'sequence_data' => $_POST['sequence_data'],
        'id' => $_GET['id']
    ]);
    header("Location: list_lesson_sequences.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lesson Sequence</title>
</head>
<body>
    <h2>Edit Lesson Sequence</h2>
    <form method="POST">
        Segment:
        <select name="segment_id" required>
            <?php foreach ($segments as $segment): ?>
                <option value="<?= $segment['segment_id'] ?>" <?= $segment['segment_id'] == $sequence['segment_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($segment['segment_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        Sequence Name: <input type="text" name="sequence_name" value="<?= htmlspecialchars($sequence['sequence_name']) ?>" required><br><br>
        Description: <textarea name="sequence_description"><?= htmlspecialchars($sequence['sequence_description']) ?></textarea><br><br>
        Sequence Data: <textarea name="sequence_data"><?= htmlspecialchars($sequence['sequence_data']) ?></textarea><br><br>
        <input type="submit" value="Update Sequence">
    </form>
    <a href="list_lesson_sequences.php">Back to Sequence List</a>
</body>
</html>
