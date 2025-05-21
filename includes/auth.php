<?php
require_once 'config.php';

function studentLogin($email, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->execute([$email]);
        $student = $stmt->fetch();
        
        return ($student && password_verify($password, $student['password']));
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

function adminLogin($username, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        return ($admin && password_verify($password, $admin['password']));
    } catch (PDOException $e) {
        error_log("Admin login error: " . $e->getMessage());
        return false;
    }
}

function isStudentLoggedIn() {
    return isset($_SESSION['student_id']);
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}
?>