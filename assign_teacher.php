<?php
session_start();
include 'db.php'; // Update path based on your folder structure

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Only admins can assign teachers.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $teacher_id = $_POST['teacher_id'];

    try {
        $stmt = $pdo->prepare("UPDATE courses SET teacher_id = ? WHERE id = ?");
        $stmt->execute([$teacher_id, $course_id]);
        echo "✅ Teacher assigned successfully!";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>

<!-- Assign Teacher Form -->
<h2>Assign Teacher to Course</h2>
<form method="POST">
    <label>Select Course:</label>
    <select name="course_id">
        <?php
        // Fetch courses that don't have a teacher assigned
        $stmt = $pdo->query("SELECT * FROM courses WHERE teacher_id IS NULL");
        while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$course['id']}'>{$course['title']}</option>";
        }
        ?>
    </select><br>

    <label>Select Teacher:</label>
    <select name="teacher_id">
        <?php
        // Fetch teachers from the users table
        $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'teacher'");
        while ($teacher = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$teacher['id']}'>{$teacher['username']}</option>";
        }
        ?>
    </select><br>

    <button type="submit">Assign Teacher</button>
</form>
<a href="../users/dashboard.php">Back to Dashboard</a>
