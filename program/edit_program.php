<?php
include_once '../db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM program WHERE program_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $program = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$program) {
        die("Program not found!");
    }
} else {
    die("Invalid Request");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE program SET program_name = :name, program_description = :description WHERE program_id = :id");
    $stmt->execute([
        'name' => $_POST['program_name'],
        'description' => $_POST['program_description'],
        'id' => $_GET['id']
    ]);
    header("Location: list_programs.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Program</title>
</head>
<body>
    <h2>Edit Program</h2>
    <form method="POST">
        Program Name: <input type="text" name="program_name" value="<?=htmlspecialchars($program['program_name'])?>" required><br><br>
        Description: <textarea name="program_description"><?=htmlspecialchars($program['program_description'])?></textarea><br><br>
        <input type="submit" value="Update Program">
    </form>
    <a href="list_programs.php">Back to Program List</a>
</body>
</html>
