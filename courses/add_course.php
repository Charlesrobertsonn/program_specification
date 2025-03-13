<?php
session_start();
include 'db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $program_id = $_POST['program_id']; // Get selected program ID

    $stmt = $pdo->prepare("INSERT INTO courses (title, description, program_id) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $program_id]);

    echo "✅ Course added successfully!";
}
?>

<!-- Course Creation Form -->
<h2>Add a New Course</h2>
<form method="POST">
    <label>Title:</label>
    <input type="text" name="title" required><br>

    <label>Description:</label>
    <textarea name="description" required></textarea><br>

    <label>Select Program:</label>
    <select name="program_id">
        <?php
        // Fetch available programs from the database
        $stmt = $pdo->query("SELECT * FROM programs");
        while ($program = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$program['id']}'>{$program['name']}</option>";
        }
        ?>
    </select><br>

    <button type="submit">Create Course</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>