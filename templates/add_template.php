<?php
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $template_name = filter_input(INPUT_POST, 'template_name', FILTER_SANITIZE_STRING);
    $template_description = filter_input(INPUT_POST, 'template_description', FILTER_SANITIZE_STRING);

    // File upload handling
    if (!empty($_FILES['template_file']['name'])) {
        $uploadDir = "uploads/";  // Change to your preferred upload directory
        $uploadFile = $uploadDir . basename($_FILES['template_file']['name']);
        
        // Ensure the uploads folder exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file to uploads folder
        if (move_uploaded_file($_FILES['template_file']['tmp_name'], $uploadFile)) {
            $stmt = $pdo->prepare("
                INSERT INTO activity_template (template_name, template_description, template_code)
                VALUES (:template_name, :template_description, :template_code)
            ");
            $stmt->execute([
                'template_name' => $template_name,
                'template_description' => $template_description,
                'template_code' => $uploadFile
            ]);

            echo "Template uploaded successfully!";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file selected.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Template</title>
</head>
<body>
    <h2>Add New Template</h2>
    <form method="POST" enctype="multipart/form-data">
        Name: <input type="text" name="template_name" required><br><br>
        Description: <textarea name="template_description"></textarea><br><br>
        Code File: <input type="file" name="template_file" required><br><br>
        <input type="submit" value="Add Template">
    </form>
    <a href="list_templates.php">Back to Template List</a>
</body>
</html>