/* complete_lesson.php */
<?php
session_start();
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lesson_id = $_POST['lesson_id'];
    $stmt = $pdo->prepare("INSERT INTO user_progress (user_id, lesson_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $lesson_id]);
    echo "Lesson marked as completed!";
}
?>
<form method="POST">
    <select name="lesson_id">
        <?php
        $stmt = $pdo->query("SELECT * FROM lessons");
        while ($lesson = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$lesson['id']}'>{$lesson['title']}</option>";
        }
        ?>
    </select>
    <button type="submit">Complete Lesson</button>
</form>