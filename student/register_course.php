<?php
session_start();
require_once '../includes/db.php';

$courses = [];
$dropped_courses = [];

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Undo Drop Course
    if (isset($_POST['undo_course_id'])) {
        $course_id = $_POST['undo_course_id'];
        try {
            $stmt = $pdo->prepare("UPDATE student_courses SET status = 'enrolled' WHERE student_id = :student_id AND course_id = :course_id");
            $stmt->execute([
                ':student_id' => $_SESSION['student_id'],
                ':course_id' => $course_id
            ]);
            $_SESSION['success'] = "Course enrollment restored successfully.";
            header("Location: register_course.php");
            exit;
        } catch (PDOException $e) {
            error_log("Undo Drop Error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to restore course enrollment.";
            header("Location: register_course.php");
            exit;
        }
    }
    // Existing course registration handling would be here...
}

try {
    // Fetch available courses
    $stmt = $pdo->query("SELECT id, name FROM courses ORDER BY name");
    $courses = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

    // Fetch dropped courses
    $stmt = $pdo->prepare("
        SELECT c.id, c.name 
        FROM courses c
        JOIN student_courses sc ON c.id = sc.course_id 
        WHERE sc.student_id = :student_id AND sc.status = 'dropped'
    ");
    $stmt->execute([':student_id' => $_SESSION['student_id']]);
    $dropped_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $_SESSION['error'] = "Database connection error. Please try again later.";
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register for Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-4">Register for Courses</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="post" action="register_course.php">
        <div class="mb-3">
            <h4>Available Courses</h4>
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="course_ids[]" 
                               value="<?= htmlspecialchars($course['id']) ?>" 
                               id="course_<?= $course['id'] ?>">
                        <label class="form-check-label" for="course_<?= $course['id'] ?>">
                            <?= htmlspecialchars($course['name']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">No courses available for registration.</div>
            <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-primary">Register Selected</button>
        <button type="button" class="btn btn-success" onclick="checkAll()">Register All</button>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>

    <?php if (!empty($dropped_courses)): ?>
        <div class="mt-5">
            <h4>Dropped Courses</h4>
            <?php foreach ($dropped_courses as $course): ?>
                <form method="post" class="d-inline">
                    <input type="hidden" name="undo_course_id" value="<?= htmlspecialchars($course['id']) ?>">
                    <button type="submit" class="btn btn-warning m-1">
                        Undo Drop <?= htmlspecialchars($course['name']) ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <script>
        function checkAll() {
            document.querySelectorAll('input[name="course_ids[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
            document.querySelector('form').submit();
        }
    </script>
</body>
</html>
