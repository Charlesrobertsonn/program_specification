<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];

        try {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $user_id]);
            $_SESSION['username'] = $username; // Update session data
            echo "✅ Profile updated successfully!";
        } catch (PDOException $e) {
            echo "❌ Error: " . $e->getMessage();
        }
    }

    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        // Check if current password is correct
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password'])) {
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$new_password, $user_id]);
            echo "✅ Password changed successfully!";
        } else {
            echo "❌ Incorrect current password.";
        }
    }

    if (isset($_POST['upload_image'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $stmt->execute([$target_file, $user_id]);
            echo "✅ Profile picture updated!";
        } else {
            echo "❌ Error uploading file.";
        }
    }
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Profile Page</h2>
<img src="<?= $user['profile_picture'] ?: 'default.png' ?>" width="100" alt="Profile Picture">
<form method="POST" enctype="multipart/form-data">
    <label>Username:</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <button type="submit" name="update_profile">Update Profile</button>
</form>

<h2>Change Password</h2>
<form method="POST">
    <input type="password" name="current_password" placeholder="Current Password" required><br>
    <input type="password" name="new_password" placeholder="New Password" required><br>
    <button type="submit" name="change_password">Change Password</button>
</form>

<h2>Upload Profile Picture</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="profile_picture" required><br>
    <button type="submit" name="upload_image">Upload</button>
</form>

<a href="dashboard.php">Back to Dashboard</a>
