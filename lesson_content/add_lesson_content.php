<?php
include_once '../db.php';

// Fetch lessons and templates
try {
    $lessons = $pdo->query("SELECT * FROM lesson")->fetchAll(PDO::FETCH_ASSOC);
    $templates = $pdo->query("SELECT * FROM activity_template")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p style='color:red;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>");
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
    <h3>Auto-Populate Lesson Content</h3>
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

    <hr>

    <!-- FORM FOR MANUAL ENTRY -->
    <h3>Manually Add Lesson Content</h3>
    <form method="POST" enctype="multipart/form-data">
        <label for="lesson_id_manual">Lesson:</label>
        <select name="lesson_id" required>
            <option value="">Select Lesson</option>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= htmlspecialchars($lesson['lesson_id']) ?>">
                    <?= htmlspecialchars($lesson['lesson_title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="template_id_manual">Template:</label>
        <select name="template_id" required>
            <option value="">Select Template</option>
            <?php foreach ($templates as $template): ?>
                <option value="<?= htmlspecialchars($template['template_id']) ?>">
                    <?= htmlspecialchars($template['template_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="content_key">Content Key:</label>
        <select name="content_key" required>
            <option value="">Select Content Key</option>
        </select>

        <label for="content_value">Content Value:</label>
        <textarea name="content_value"></textarea>

        <label for="file_upload">Upload File:</label>
        <input type="file" name="file_upload">

        <input type="submit" value="Add Content">
    </form>

    <br>
    <a href="list_lesson_content.php">Back to Content List</a>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const templateSelect = document.querySelector("select[name='template_id']");
        const contentKeySelect = document.querySelector("select[name='content_key']");
        const contentValueField = document.querySelector("textarea[name='content_value']");

        templateSelect.addEventListener("change", function () {
            const templateId = this.value;

            if (!templateId) {
                console.log("No template selected. Clearing dropdown.");
                contentKeySelect.innerHTML = "<option value=''>Select Content Key</option>";
                contentValueField.value = "";
                return;
            }

            console.log(`Fetching content keys for template_id: ${templateId}`);
            fetch(`get_template_keys.php?template_id=${templateId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Content Keys Received:", data);
                    contentKeySelect.innerHTML = "<option value=''>Select Content Key</option>";

                    if (data.error) {
                        console.error("Error:", data.error);
                        return;
                    }

                    if (data.length === 0) {
                        console.warn("No content keys found for this template.");
                        return;
                    }

                    data.forEach(key => {
                        const option = document.createElement("option");
                        option.value = key;
                        option.textContent = key;
                        contentKeySelect.appendChild(option);
                    });

                    console.log("Dropdown Updated Successfully");
                })
                .catch(error => console.error("Error fetching content keys:", error));
        });

        contentKeySelect.addEventListener("change", function () {
            const contentKey = this.value;
            const templateId = templateSelect.value;

            if (!contentKey || !templateId) {
                console.log("No content key selected.");
                contentValueField.value = "";
                return;
            }

            console.log(`Fetching content value for template_id: ${templateId}, content_key: ${contentKey}`);
            fetch(`get_template_values.php?template_id=${templateId}&content_key=${contentKey}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Content Value Received:", data);
                    if (data.error) {
                        console.error("Error:", data.error);
                        contentValueField.value = "";
                    } else {
                        contentValueField.value = data.content_value;
                    }
                })
                .catch(error => console.error("Error fetching content values:", error));
        });
    });
    </script>

</body>
</html>
