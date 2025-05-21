<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$error = "";
$success = "";

// Fetch current student info
$stmt = $pdo->prepare("SELECT full_name, email, password FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found.");
}

$full_name = $student['full_name'];
$email = $student['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_full_name = trim($_POST['full_name'] ?? '');
    $new_email = trim($_POST['email'] ?? '');

    // Password fields
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate name and email
    if (empty($new_full_name) || empty($new_email)) {
        $error = "Full name and email cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email is already used by another student
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
        $stmt->execute([$new_email, $student_id]);
        if ($stmt->rowCount() > 0) {
            $error = "This email is already used by another account.";
        }
    }

    // If no error so far, proceed
    if (!$error) {
        // Handle password change only if current password is entered
        if (!empty($current_password)) {
            // Verify current password
            if (!password_verify($current_password, $student['password'])) {
                $error = "Current password is incorrect.";
            } elseif (strlen($new_password) < 6) {
                $error = "New password must be at least 6 characters.";
            } elseif ($new_password !== $confirm_password) {
                $error = "New password and confirm password do not match.";
            }
        }
    }

    // If still no error, update database
    if (!$error) {
        // Update name and email
        $stmt = $pdo->prepare("UPDATE students SET full_name = ?, email = ? WHERE id = ?");
        $stmt->execute([$new_full_name, $new_email, $student_id]);

        // Update password if needed
        if (!empty($current_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE students SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $student_id]);
        }

        $success = "Profile updated successfully.";
        $full_name = $new_full_name;
        $email = $new_email;
        $_SESSION['student_name'] = $full_name;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
</head>
<body>
    <h2>My Profile</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif ($success): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form method="post" action="profile.php">
        <label>Full Name:</label><br>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <h3>Change Password</h3>
        <p>Leave blank if you do not want to change your password.</p>

        <label>Current Password:</label><br>
        <input type="password" name="current_password"><br><br>

        <label>New Password:</label><br>
        <input type="password" name="new_password"><br><br>

        <label>Confirm New Password:</label><br>
        <input type="password" name="confirm_password"><br><br>

        <button type="submit">Update Profile</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
