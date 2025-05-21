<?php
// admin/dashboard.php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db.php';

// Fetch admin info optionally
$stmt = $pdo->prepare("SELECT username FROM admins WHERE id = ?");
$stmt->execute([$_SESSION["admin_id"]]);
$admin = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($admin['username']); ?></h1>
    <p><a href="logout.php">Logout</a></p>

    <h2>Course Management</h2>
    <p><a href="add_course.php">Add New Course</a></p>

    <!-- You can add course listing here -->

</body>
</html>
