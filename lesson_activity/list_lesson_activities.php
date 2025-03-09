<?php
include_once '../db.php';

// Fetch linked lesson activities
$lesson_activities = $pdo->query("
    SELECT lesson_activity.lesson_activity_id, 
           lesson.lesson_title, 
           activity_template.template_name
    FROM lesson_activity
    LEFT JOIN lesson ON lesson_activity.lesson_id = lesson.lesson_id
    LEFT JOIN activity_template ON lesson_activity.template_id = activity_template.template_id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lesson Activities</title>
</head>
<body>
    <h2>Lesson Activities</h2>
    <a href="link_templates.php">Link New Template</a>
    <br><br>

    <?php if (empty($lesson_activities)): ?>
        <p style="color: red;">No lesson activities linked yet.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Lesson</th>
                <th>Template</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($lesson_activities as $activity): ?>
            <tr>
                <td><?= htmlspecialchars($activity['lesson_title']) ?></td>
                <td><?= htmlspecialchars($activity['template_name']) ?></td>
                <td>
                    <a href="unlink_templates.php?lesson_activity_id=<?= htmlspecialchars($activity['lesson_activity_id']) ?>" 
                       onclick="return confirm('Are you sure you want to unlink this template?')">
                       Unlink
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
