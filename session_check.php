/* session_check.php */
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Not logged in.");
} else {
    echo "Logged in as " . $_SESSION['username'];
}
?>