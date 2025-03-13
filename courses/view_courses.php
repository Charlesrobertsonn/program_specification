<?php
include 'db.php';

// Fetch courses along with their program names
$stmt = $pdo->query("
    SELECT programs.name AS program_name, courses.title 
    FROM courses 
    JOIN programs ON courses.program_id = programs.id 
    ORDER BY programs.name
");

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$current_program = "";
?>

<h2>📚 Available Courses</h2>

<?php
foreach ($courses as $course) {
    if ($current_program !== $course['program_name']) {
        if ($current_program !== "") {
            echo "</ul>";
        }
        echo "<h3>" . htmlspecialchars($course['program_name']) . "</h3><ul>";
        $current_program = $course['program_name'];
    }
    echo "<li>" . htmlspecialchars($course['title']) . "</li>";
}
echo "</ul>";
?>