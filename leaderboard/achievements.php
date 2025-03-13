/* achievements.php */
<?php
include 'db.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM achievements WHERE user_id = ?");
$stmt->execute([$user_id]);
$achievements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Your Achievements</h2>
<ul>
<?php foreach ($achievements as $achievement) {
    echo "<li>🏅 " . htmlspecialchars($achievement['title']) . "</li>";
} ?>
</ul>