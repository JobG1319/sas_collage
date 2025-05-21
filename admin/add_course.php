<?php
// admin/add_course.php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($name === '') {
        $error = "Course name is required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO courses (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        $success = "Course added successfully.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
</head>
<body>
    <h2>Add New Course</h2>
    <p><a href="dashboard.php">Back to Dashboard</a></p>

    <?php
    if (!empty($error)) echo "<p style='color:red;'>$error</p>";
    if (!empty($success)) echo "<p style='color:green;'>$success</p>";
    ?>

    <form method="post" action="">
        <input type="text" name="name" placeholder="Course Name" required><br><br>
        <textarea name="description" placeholder="Course Description"></textarea><br><br>
        <button type="submit">Add Course</button>
    </form>
</body>
</html>
