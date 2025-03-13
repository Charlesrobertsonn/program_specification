/* enroll.php */
<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("Access denied.");
}
$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $check = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $check->execute([$user_id, $course_id]);
    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);
        echo "Enrolled successfully!";
    } else {
        echo "Already enrolled.";
    }
}
?>
<form method="POST">
    <select name="course_id">
        <?php
        $stmt = $pdo->query("SELECT * FROM courses");
        while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$course['id']}'>{$course['title']}</option>";
        }
        ?>
    </select>
    <button type="submit">Enroll</button>
</form>