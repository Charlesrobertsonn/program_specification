<?php
include_once '../db.php';

$sql = "
    SELECT lc.content_id, lc.content_key, lc.content_value, 
           l.lesson_title, t.template_name 
    FROM lesson_content lc
    JOIN lesson l ON lc.lesson_id = l.lesson_id
    JOIN activity_template t ON lc.template_id = t.template_id
";
$contents = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
                <th>Key</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contents as $content): ?>
                <tr>
                    <td><?= htmlspecialchars($content['lesson_title']) ?></td>
                    <td><?= htmlspecialchars($content['template_name']) ?></td>
                    <td><?= htmlspecialchars($content['content_key']) ?></td>
                    <td><?= htmlspecialchars($content['content_value']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
