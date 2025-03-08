<?php
include_once '../db.php';

try {
    // Fetch all lessons and link them to their lesson sequence
    $stmt = $pdo->prepare("
        SELECT lesson.*, lesson_sequence.sequence_name 
        FROM lesson 
        LEFT JOIN lesson_sequence ON lesson.lesson_sequence_id = lesson_sequence.lesson_sequence_id
        ORDER BY lesson.sequence_order ASC
    ");
    $stmt->execute();
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching lessons: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Lessons</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            text-decoration: none;
            color: blue;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Lessons</h2>
    <a href="add_lesson.php"><strong>Add New Lesson</strong></a>
    <table>
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
        <?php if (empty($lessons)): ?>
            <tr><td colspan="9">No lessons available.</td></tr>
        <?php else: ?>
            <?php foreach ($lessons as $lesson): ?>
            <tr>
                <td><?= htmlspecialchars($lesson['lesson_id']) ?></td>
                <td><?= htmlspecialchars($lesson['sequence_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($lesson['lesson_title']) ?></td>
                <td><?= htmlspecialchars($lesson['lesson_description'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($lesson['lesson_procedure'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($lesson['sequence_order']) ?></td>
                <td><?= htmlspecialchars($lesson['lesson_type'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($lesson['lesson_objective'] ?? 'N/A') ?></td>
                <td>
                    <a href="edit_lesson.php?id=<?= htmlspecialchars($lesson['lesson_id']) ?>">Edit</a> | 
                    <a href="delete_lesson.php?id=<?= htmlspecialchars($lesson['lesson_id']) ?>" onclick="return confirm('Are you sure you want to delete this lesson?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
