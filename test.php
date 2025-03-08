<?php
include_once 'db.php';
$stmt = $pdo->query("SHOW TABLES");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Tables_in_program_specification'] . "<br>";
}
?>