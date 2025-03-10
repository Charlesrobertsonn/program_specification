<?php
include_once '../db.php';

// Fetch lessons and templates
$lessons = $pdo->query("SELECT * FROM lesson")->fetchAll(PDO::FETCH_ASSOC);
$templates = $pdo->query("SELECT * FROM activity_template")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lesson_id = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);
    $template_id = filter_input(INPUT_POST, 'template_id', FILTER_VALIDATE_INT);
    $content_key = filter_input(INPUT_POST, 'content_key', FILTER_SANITIZE_SPECIAL_CHARS);
    $content_value = filter_input(INPUT_POST, 'content_value', FILTER_SANITIZE_SPECIAL_CHARS);
    $file_path = null;

    // Handle File Upload (if file exists)
    if (!empty($_FILES['file_upload']['name'])) {
        $upload_dir = '../uploads/'; // Make sure this folder exists
        $file_name = basename($_FILES['file_upload']['name']);
        $file_path = $upload_dir . $file_name;

        // Move file to uploads folder
        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $file_path)) {
            $file_path = 'uploads/' . $file_name; // Store relative path in DB
        } else {
            echo "<p style='color:red;'>File upload failed.</p>";
        }
    }

    if ($lesson_id && $template_id && $content_key && ($content_value || $file_path)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO lesson_content (lesson_id, template_id, content_key, content_value, file_path) 
                VALUES (:lesson_id, :template_id, :content_key, :content_value, :file_path)
            ");
            $stmt->execute([
                'lesson_id' => $lesson_id,
                'template_id' => $template_id,
                'content_key' => $content_key,
                'content_value' => $content_value,
                'file_path' => $file_path
            ]);
            echo "<p style='color:green;'>Lesson content added successfully!</p>";
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            echo "<p style='color:red;'>Something went wrong. Please try again later.</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid input! Please check all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson Content</title>
</head>
<body>

    <h2>Add Lesson Content</h2>

    <!-- FORM FOR AUTO-POPULATION -->
    <form action="populate_lesson_content.php" method="POST">
        <label for="lesson_id">Lesson:</label>
        <select name="lesson_id" required>
            <option value="">Select Lesson</option>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= htmlspecialchars($lesson['lesson_id']) ?>">
                    <?= htmlspecialchars($lesson['lesson_title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="template_id">Template:</label>
        <select name="template_id" required>
            <option value="">Select Template</option>
            <?php foreach ($templates as $template): ?>
                <option value="<?= htmlspecialchars($template['template_id']) ?>">
                    <?= htmlspecialchars($template['template_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Auto-Populate Content</button>
    </form>

    <br><hr>

    <!-- FORM FOR MANUAL ENTRY -->
    <form method="POST" enctype="multipart/form-data">
        <label>Lesson:</label>
        <select name="lesson_id" required>
            <option value="">Select Lesson</option>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= htmlspecialchars($lesson['lesson_id']) ?>">
                    <?= htmlspecialchars($lesson['lesson_title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Template:</label>
        <select name="template_id" required>
            <option value="">Select Template</option>
            <?php foreach ($templates as $template): ?>
                <option value="<?= htmlspecialchars($template['template_id']) ?>">
                    <?= htmlspecialchars($template['template_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Content Key:</label>
        <select name="content_key" required>
            <option value="title">Title</option>
            <option value="instructions">Instructions</option>
            <option value="video_url">Video URL</option>
            <option value="image_url">Image URL</option>
            <option value="exercise">Exercise</option>
        </select>

        <label>Content Value:</label>
        <textarea name="content_value"></textarea>

        <label>Upload File:</label>
        <input type="file" name="file_upload">

        <input type="submit" value="Add Content">
    </form>

    <a href="list_lesson_content.php">Back to Content List</a>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const templateSelect = document.querySelector("select[name='template_id']");
        const contentKeySelect = document.querySelector("select[name='content_key']");
        
        templateSelect.addEventListener("change", function () {
            const templateId = this.value;

            // Fetch content keys dynamically via AJAX
            fetch(`get_template_keys.php?template_id=${templateId}`)
                .then(response => response.json())
                .then(data => {
                    contentKeySelect.innerHTML = ""; // Clear previous options
                    data.forEach(key => {
                        const option = document.createElement("option");
                        option.value = key;
                        option.textContent = key;
                        contentKeySelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Error fetching content keys:", error));
        });
    });
    </script>
</body>
</html>
