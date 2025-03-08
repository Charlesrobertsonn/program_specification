<?php
include_once '../db.php';

// Fetch units for the dropdown
$units = $pdo->query("SELECT * FROM unit")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO segment (unit_id, segment_name, segment_description) VALUES (:unit_id, :segment_name, :segment_description)");
    $stmt->execute([
        'unit_id' => $_POST['unit_id'],
        'segment_name' => $_POST['segment_name'],
        'segment_description' => $_POST['segment_description']
    ]);
    echo "Segment added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Segment</title>
</head>
<body>
    <h2>Add New Segment</h2>
    <form method="POST">
        Unit:
        <select name="unit_id" required>
            <option value="">Select Unit</option>
            <?php foreach ($units as $unit): ?>
                <option value="<?=$unit['unit_id']?>"><?=htmlspecialchars($unit['unit_name'])?></option>
            <?php endforeach; ?>
        </select><br><br>

        Segment Name: <input type="text" name="segment_name" required><br><br>
        Description: <textarea name="segment_description" required></textarea><br><br>
        <input type="submit" value="Add Segment">
    </form>
    <a href="list_segments.php">Back to Segment List</a>
</body>
</html>
