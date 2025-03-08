<?php
include_once '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

// Fetch unit
$stmt = $pdo->prepare("SELECT * FROM unit WHERE unit_id = :id");
$stmt->execute(['id' => $_GET['id']]);
$unit = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$unit) {
    die("Unit not found.");
}

// Fetch programs for dropdown
$programs = $pdo->query("SELECT * FROM program")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE unit SET program_id = :program_id, unit_name = :unit_name, unit_description = :unit_description WHERE unit_id = :id");
    $stmt->execute([
        'program_id' => $_POST['program_id'],
        'unit_name' => $_POST['unit_name'],
        'unit_description' => $_POST['unit_description'],
        'id' => $_GET['id']
    ]);
    header("Location: list_units.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Unit</title>
</head>
<body>
    <h2>Edit Unit</h2>
    <form method="POST">
        Program:
        <select name="program_id" required>
            <?php foreach ($programs as $program): ?>
                <option value="<?=$program['program_id']?>" <?= $program['program_id'] == $unit['program_id'] ? 'selected' : '' ?>>
                    <?=htmlspecialchars($program['program_name'])?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        Unit Name: <input type="text" name="unit_name" value="<?=htmlspecialchars($unit['unit_name'])?>" required><br><br>
        Description: <textarea name="unit_description" required><?=htmlspecialchars($unit['unit_description'])?></textarea><br><br>
        <input type="submit" value="Update Unit">
    </form>
    <a href="list_units.php">Back to Unit List</a>
</body>
</html>
