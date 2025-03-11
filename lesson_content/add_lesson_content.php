<?php
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: Log incoming data
    error_log("Form Data Received: " . json_encode($_POST));

    // Ensure all required fields exist
    if (!empty($_POST['lesson_id']) && !empty($_POST['template_id']) && !empty($_POST['content_key']) && !empty($_POST['content_value'])) {
        
        $lesson_id = intval($_POST['lesson_id']);
        $template_id = intval($_POST['template_id']);
        $content_key = $_POST['content_key'];
        $content_value = $_POST['content_value'];

        try {
            // Insert the lesson content
            $sql = "INSERT INTO lesson_content (lesson_id, template_id, content_key, content_value) 
                    VALUES (:lesson_id, :template_id, :content_key, :content_value)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':lesson_id' => $lesson_id,
                ':template_id' => $template_id,
                ':content_key' => $content_key,
                ':content_value' => $content_value
            ]);

            echo "<p style='color:green;'>Lesson content added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Database Insert Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Error: All fields are required.</p>";
    }
}

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
        <label for="lesson_id_auto">Lesson:</label>
        <select name="lesson_id_auto" required>
            <option value="">Select Lesson</option>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= htmlspecialchars($lesson['lesson_id']) ?>">
                    <?= htmlspecialchars($lesson['lesson_title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="template_id_auto">Template:</label>
        <select name="template_id_auto" required>
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
        <select id="lesson_id" name="lesson_id" required>
            <option value="">Select Lesson</option>
            <?php 
            $uniqueLessons = []; // Array to track unique lessons
            foreach ($lessons as $lesson): 
                if (!in_array($lesson['lesson_id'], $uniqueLessons)): 
                    $uniqueLessons[] = $lesson['lesson_id']; // Store the unique ID
            ?>
                <option value="<?= htmlspecialchars($lesson['lesson_id']) ?>">
                    <?= htmlspecialchars($lesson['lesson_title']) ?>
                </option>
            <?php endif; endforeach; ?>
        </select>

        <label for="template_id_manual">Template:</label>
        <select id="template_id" name="template_id" required>
            <option value="">Select Template</option>
            <?php 
            $uniqueTemplates = []; // Track already displayed template names
            foreach ($templates as $template): 
                if (!in_array($template['template_name'], $uniqueTemplates)) {
                    $uniqueTemplates[] = $template['template_name']; ?>
                    <option value="<?= htmlspecialchars($template['template_id']) ?>">
                        <?= htmlspecialchars($template['template_name']) ?>
                    </option>
            <?php } endforeach; ?>
        </select>

        <label for="content_key">Content Key:</label>
        <select id="content_key" name="content_key" required>
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
        const templateSelect = document.getElementById("template_id");
        const contentKeySelect = document.getElementById("content_key");
        const contentValueField = document.querySelector("textarea[name='content_value']");

        templateSelect.addEventListener("change", function () {
            const templateId = this.value;
            
            if (!templateId) {
                console.warn("No template selected. Clearing dropdown.");
                contentKeySelect.innerHTML = "<option value=''>Select Content Key</option>";
                contentValueField.value = "";
                return;
            }

            console.log(`Fetching: get_template_keys.php?template_id=${templateId}`);

            fetch(`get_template_keys.php?template_id=${templateId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Received content keys:", data);

                    if (!Array.isArray(data)) {
                        console.error("Unexpected response format:", data);
                        return;
                    }

                    contentKeySelect.innerHTML = "<option value=''>Select Content Key</option>";

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

            console.log(`Fetching: get_template_values.php?template_id=${templateId}&content_key=${contentKey}`);

            fetch(`get_template_values.php?template_id=${templateId}&content_key=${contentKey}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Received content value:", data);

                    if (!data || typeof data.content_value === "undefined") {
                        console.error("Error:", data.error || "Invalid response");
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
