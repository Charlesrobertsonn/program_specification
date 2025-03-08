<?php
include_once '../db.php';

$sql = "
    SELECT ls.lesson_sequence_id, ls.sequence_name, ls.sequence_description, ls.sequence_data, s.segment_name 
    FROM lesson_sequence ls
    JOIN segment s ON ls.segment_id = s.segment_id
    ORDER BY s.segment_name, ls.sequence_name
";
$sequences = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lesson Sequence List</title>
</head>
<body>
    <h2>Lesson Sequence List</h2>
    <a href="add_lesson_sequence.php">Add New Lesson Sequence</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Segment</th>
                <th>Sequence Name</th>
                <th>Description</th>
                <th>Sequence Data</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sequences as $sequence): ?>
                <tr>
                    <td><?= htmlspecialchars($sequence['segment_name']) ?></td>
                    <td><?= htmlspecialchars($sequence['sequence_name']) ?></td>
                    <td><?= htmlspecialchars($sequence['sequence_description']) ?></td>
                    <td><?= htmlspecialchars($sequence['sequence_data']) ?></td>
                    <td>
                        <a href="edit_lesson_sequence.php?id=<?= $sequence['lesson_sequence_id'] ?>">Edit</a> |
                        <a href="delete_lesson_sequence.php?id=<?= $sequence['lesson_sequence_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
