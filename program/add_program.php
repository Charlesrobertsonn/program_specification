<?php
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO program (program_name, program_description) VALUES (:name, :description)");
    $stmt->execute([
        'name' => $_POST['program_name'],
        'description' => $_POST['program_description']
    ]);
    echo "Program added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Program</title>
</head>
<body>
    <h2>Add New Program</h2>
    <form method="POST">
        Program Name: <input type="text" name="program_name" required><br><br>
        Description: <textarea name="program_description" required></textarea><br><br>
        <input type="submit" value="Add Program">
    </form>
    <a href="list_programs.php">Back to Program List</a>
</body>
</html>
