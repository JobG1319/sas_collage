<?php
session_start();
require_once "../includes/config.php";

// Ensure student is logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit;
}

$student_id = $_SESSION["student_id"];

// Handle course unregistration
if (isset($_GET["unregister"]) && is_numeric($_GET["unregister"])) {
    $course_id = $_GET["unregister"];
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$student_id, $course_id]);
    $message = "You have unregistered from the course.";
}

// Fetch registered courses
$sql = "SELECT c.id, c.name, c.description
        FROM registrations r
        JOIN courses c ON r.course_id = c.id
        WHERE r.student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Registered Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f7;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .course {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .unregister {
            margin-top: 10px;
        }

        .unregister a {
            text-decoration: none;
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
        }

        .message {
            background: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .back {
            margin-top: 20px;
        }

        .back a {
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .back a:hover {
            background: #217dbb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Registered Courses</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (count($courses) > 0): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course">
                    <strong><?php echo htmlspecialchars($course["name"]); ?></strong><br>
                    <?php echo htmlspecialchars($course["description"]); ?><br>
                    <div class="unregister">
                        <a href="?unregister=<?php echo $course["id"]; ?>" onclick="return confirm('Are you sure you want to unregister from this course?');">Unregister</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You are not registered for any courses.</p>
        <?php endif; ?>

        <div class="back">
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
