/* teacher_dashboard.php */
<?php
session_start();
include 'db.php';

// Restrict to teachers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    die("Access denied.");
}
$teacher_id = $_SESSION['user_id'];

// Fetch assigned courses
$stmt = $pdo->prepare("SELECT * FROM courses WHERE teacher_id = ?");
$stmt->execute([$teacher_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<h3>Your Assigned Courses:</h3>
<ul>
<?php foreach ($courses as $course) {
    echo "<li>" . htmlspecialchars($course['title']) . "</li>";
} ?>
</ul>
<a href="logout.php">Logout</a>
