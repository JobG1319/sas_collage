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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST['course_id'] ?? '';

    if (empty($course_id)) {
        $error = "Please select a course to register.";
    } else {
        // Check if student already registered for this course
        $stmt = $pdo->prepare("SELECT * FROM registrations WHERE student_id = ? AND course_id = ?");
        $stmt->execute([$student_id, $course_id]);

        if ($stmt->rowCount() > 0) {
            $error = "You are already registered for this course.";
        } else {
            // Insert registration
            $stmt = $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (?, ?)");
            if ($stmt->execute([$student_id, $course_id])) {
                $success = "Course registered successfully!";
            } else {
                $error = "Failed to register course, please try again.";
            }
        }
    }
}

// Fetch all courses to show in dropdown
$stmt = $pdo->query("SELECT id, name FROM courses ORDER BY name");
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register for a Course</title>
</head>
<body>
    <h2>Register for a Course</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form method="post" action="register_course.php">
        <label for="course_id">Select Course:</label><br>
        <select name="course_id" id="course_id" required>
            <option value="">-- Select a Course --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo $course['id']; ?>">
                    <?php echo htmlspecialchars($course['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Register</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
