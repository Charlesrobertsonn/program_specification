<?php
include_once '../db.php';

// Fetch units with program names (JOIN query)
$sql = "
    SELECT u.unit_id, u.unit_name, u.unit_description, p.program_name
    FROM unit u
    JOIN program p ON u.program_id = p.program_id
";
$units = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unit List</title>
</head>
<body>
    <h2>Unit List</h2>
    <a href="add_unit.php">Add New Unit</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Program</th>
                <th>Unit Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($units as $unit): ?>
                <tr>
                    <td><?=htmlspecialchars($unit['program_name'])?></td>
                    <td><?=htmlspecialchars($unit['unit_name'])?></td>
                    <td><?=htmlspecialchars($unit['unit_description'])?></td>
                    <td>
                        <a href="edit_unit.php?id=<?=$unit['unit_id']?>">Edit</a> |
                        <a href="delete_unit.php?id=<?=$unit['unit_id']?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
