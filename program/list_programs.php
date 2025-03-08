<?php
include_once '../db.php';
$programs = $pdo->query("SELECT * FROM program")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Program List</title>
</head>
<body>
    <h2>Program List</h2>
    <a href="add_program.php">Add New Program</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Program Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($programs as $program): ?>
                <tr>
                    <td><?=htmlspecialchars($program['program_name'])?></td>
                    <td><?=htmlspecialchars($program['program_description'])?></td>
                    <td>
                        <a href="edit_program.php?id=<?=$program['program_id']?>">Edit</a> | 
                        <a href="delete_program.php?id=<?=$program['program_id']?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
