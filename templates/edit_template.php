<?php
include_once '../db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM activity_template WHERE template_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE activity_template 
        SET template_name = :template_name, template_description = :template_description, template_code = :template_code
        WHERE template_id = :id
    ");
    $stmt->execute([
        'id' => $_POST['id'],
        'template_name' => $_POST['template_name'],
        'template_description' => $_POST['template_description'],
        'template_code' => $_POST['template_code']
    ]);
    echo "Template updated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Template</title>
</head>
<body>
    <h2>Edit Template</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $template['template_id'] ?>">
        Name: <input type="text" name="template_name" value="<?= htmlspecialchars($template['template_name']) ?>" required><br><br>
        Description: <textarea name="template_description"><?= htmlspecialchars($template['template_description']) ?></textarea><br><br>
        Code: <textarea name="template_code"><?= htmlspecialchars($template['template_code']) ?></textarea><br><br>
        <input type="submit" value="Update Template">
    </form>
    <a href="list_templates.php">Back to Template List</a>
</body>
</html>
