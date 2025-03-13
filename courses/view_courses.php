/* view_courses.php */
<?php
include 'db.php';
$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Available Courses</h2>
<ul>
<?php foreach ($courses as $course) {
    echo "<li>" . htmlspecialchars($course['title']) . "</li>";
} ?>
</ul>