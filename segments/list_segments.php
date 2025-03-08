<?php
include_once '../db.php';

// Fetch segments with unit names (JOIN query)
$sql = "
    SELECT s.segment_id, s.segment_name, s.segment_description, u.unit_name
    FROM segment s
    JOIN unit u ON s.unit_id = u.unit_id
";
$segments = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Segment List</title>
</head>
<body>
    <h2>Segment List</h2>
    <a href="add_segment.php">Add New Segment</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Unit</th>
                <th>Segment Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($segments as $segment): ?>
                <tr>
                    <td><?=htmlspecialchars($segment['unit_name'])?></td>
                    <td><?=htmlspecialchars($segment['segment_name'])?></td>
                    <td><?=htmlspecialchars($segment['segment_description'])?></td>
                    <td>
                        <a href="edit_segment.php?id=<?=$segment['segment_id']?>">Edit</a> |
                        <a href="delete_segment.php?id=<?=$segment['segment_id']?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
