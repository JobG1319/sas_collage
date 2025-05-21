<?php
session_start();
require_once "../includes/config.php";

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];
$message = "";

// Fetch existing data
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);

    $stmt = $pdo->prepare("UPDATE courses SET name = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $description, $id]);
    $message = "Course updated!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
</head>
<body>
    <h2>Edit Course</h2>
    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($course["name"]) ?>" required><br><br>
        <label>Description:</label><br>
        <textarea name="description" required><?= htmlspecialchars($course["description"]) ?></textarea><br><br>
        <button type="submit">Save Changes</button>
    </form>
    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
