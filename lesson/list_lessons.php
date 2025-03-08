<?php
include_once '../db.php';

// Fetch all lessons and link them to their lesson sequence
$lessons = $pdo->query("
    SELECT lesson.*, lesson_sequence.sequence_name 
    FROM lesson 
    LEFT JOIN lesson_sequence ON lesson.lesson_sequence_id = lesson_sequence.lesson_sequence_id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>List of Lessons</title>
</head>
<body>
    <h2>Lessons</h2>
    <a href="add_lesson.php">Add New Lesson</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Sequence</th>
            <th>Title</th>
            <th>Description</th>
            <th>Procedure</th>
            <th>Sequence Order</th>
            <th>Type</th>
            <th>Objective</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($lessons as $lesson): ?>
        <tr>
            <td><?= htmlspecialchars($lesson['lesson_id']) ?></td>
            <td><?= htmlspecialchars($lesson['sequence_name']) ?></td>
            <td><?= htmlspecialchars($lesson['lesson_title']) ?></td>
            <td><?= htmlspecialchars($lesson['lesson_description']) ?></td>
            <td><?= htmlspecialchars($lesson['lesson_procedure']) ?></td>
            <td><?= htmlspecialchars($lesson['sequence_order']) ?></td>
            <td><?= htmlspecialchars($lesson['lesson_type']) ?></td>
            <td><?= htmlspecialchars($lesson['lesson_objective']) ?></td>
            <td>
                <a href="edit_lesson.php?id=<?= htmlspecialchars($lesson['lesson_id']) ?>">Edit</a> | 
                <a href="delete_lesson.php?id=<?= htmlspecialchars($lesson['lesson_id']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
