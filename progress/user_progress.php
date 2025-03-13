/* user_progress.php */
<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("Access denied.");
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT lessons.title FROM user_progress JOIN lessons ON user_progress.lesson_id = lessons.id WHERE user_progress.user_id = ?");
$stmt->execute([$user_id]);
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Your Progress</h2>
<ul>
<?php foreach ($lessons as $lesson) {
    echo "<li>" . htmlspecialchars($lesson['title']) . "</li>";
} ?>
</ul>