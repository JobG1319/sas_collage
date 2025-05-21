<?php
require_once '../includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST data safely
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already registered.";
        } else {
            // Hash password and insert
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$full_name, $email, $hash]);

            // Redirect to login page after successful registration
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
</head>
<body>
    <h2>Student Registration</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="register.php">
        <input type="text" name="full_name" placeholder="Full Name" required value="<?php echo htmlspecialchars($full_name ?? ''); ?>"><br><br>

        <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email ?? ''); ?>"><br><br>

        <input type="password" name="password" placeholder="Password" required><br><br>

        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
