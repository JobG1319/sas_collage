<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];

$stmt = $pdo->prepare("
    SELECT c.name, c.description 
    FROM courses c
    INNER JOIN registrations r ON c.id = r.course_id
    WHERE r.student_id = ?
    ORDER BY c.name
");
$stmt->execute([$student_id]);
$registered_courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Registered Courses</title>
</head>
<body>
    <h2>My Registered Courses</h2>

    <?php if (count($registered_courses) === 0): ?>
        <p>You have not registered for any courses yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registered_courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['name']); ?></td>
                        <td><?php echo htmlspecialchars($course['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
