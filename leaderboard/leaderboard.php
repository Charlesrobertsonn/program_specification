/* leaderboard.php */
<?php
include 'db.php';
$stmt = $pdo->query("SELECT users.username, COUNT(user_progress.id) AS lessons_completed FROM users JOIN user_progress ON users.id = user_progress.user_id GROUP BY users.id ORDER BY lessons_completed DESC LIMIT 10");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Leaderboard</h2>
<ul>
<?php foreach ($students as $student) {
    echo "<li>" . htmlspecialchars($student['username']) . " - " . $student['lessons_completed'] . " lessons</li>";
} ?>
</ul>