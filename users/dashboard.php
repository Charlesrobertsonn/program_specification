<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

echo "<h2>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h2>";
echo "<p>Role: " . htmlspecialchars($_SESSION['role']) . "</p>";

// Fetch total lessons
$totalLessonsStmt = $pdo->query("SELECT COUNT(*) AS total FROM lessons");
$totalLessons = $totalLessonsStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Fetch completed lessons for this user
$completedLessonsStmt = $pdo->prepare("SELECT COUNT(*) AS completed FROM user_progress WHERE user_id = ?");
$completedLessonsStmt->execute([$user_id]);
$completedLessons = $completedLessonsStmt->fetch(PDO::FETCH_ASSOC)['completed'];

// Calculate progress percentage
$progress = ($totalLessons > 0) ? round(($completedLessons / $totalLessons) * 100, 2) : 0;

// Display progress bar
echo "<h3>📊 Your Progress</h3>";
echo "<div style='width: 100%; background-color: #ddd; border-radius: 5px;'>";
echo "<div style='width: {$progress}%; background-color: #4caf50; text-align: center; padding: 5px; color: white; border-radius: 5px;'>";
echo "{$progress}%";
echo "</div>";
echo "</div>";

// Fetch and display completed lessons
$stmt = $pdo->prepare("SELECT lessons.title, user_progress.completed_at 
                       FROM user_progress 
                       JOIN lessons ON user_progress.lesson_id = lessons.id 
                       WHERE user_progress.user_id = ?");
$stmt->execute([$user_id]);
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>✅ Completed Lessons:</h3>";
if ($lessons) {
    foreach ($lessons as $lesson) {
        echo "<p>📚 " . htmlspecialchars($lesson['title']) . " - Completed on " . $lesson['completed_at'] . "</p>";
    }
} else {
    echo "<p>No lessons completed yet. <a href='complete_lesson.php'>Complete a lesson</a></p>";
}
?>

<!-- Logout Button -->
<a href="logout.php">Logout</a>
