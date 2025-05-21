<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?>!</h2>

    <p>
        <a href="register_course.php">Register for a Course</a><br>
        <a href="view_courses.php">View Registered Courses</a><br>
        <a href="profile.php">Edit Profile</a><br>
        <a href="logout.php">Logout</a>
    </p>
</body>
</html>
