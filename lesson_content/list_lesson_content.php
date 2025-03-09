<?php
include_once '../db.php';

try {
    // Fetch lesson contents with linked lesson and template details
    $stmt = $pdo->prepare("
        SELECT lesson_content.*, lesson.lesson_title, activity_template.template_name 
        FROM lesson_content 
        JOIN lesson ON lesson_content.lesson_id = lesson.lesson_id 
        JOIN activity_template ON lesson_content.template_id = activity_template.template_id
    ");
    $stmt->execute();
    $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching lesson contents: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lesson Content List</title>
</head>
<body>
    <h2>Lesson Content List</h2>
    <a href="add_lesson_content.php">Add New Content</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Lesson</th>
                <th>Template</th>
                <th>Content Key</th>
                <th>Content Value</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($contents)): ?>
                <tr><td colspan="5">No content available.</td></tr>
            <?php else: ?>
                <?php foreach ($contents as $content): ?>
                <tr>
                    <td><?= htmlspecialchars($content['lesson_title']) ?></td>
                    <td><?= htmlspecialchars($content['template_name']) ?></td>
                    <td><?= htmlspecialchars($content['content_key']) ?></td>
                    <td><?= htmlspecialchars($content['content_value']) ?></td>
                    <td>
                        <a href="edit_lesson_content.php?id=<?= htmlspecialchars($content['content_id']) ?>">Edit</a> | 
                        <a href="delete_lesson_content.php?id=<?= htmlspecialchars($content['content_id']) ?>" onclick="return confirm('Are you sure you want to delete this content?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
