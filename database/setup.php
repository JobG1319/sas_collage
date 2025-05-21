<?php
require_once __DIR__ . '/../includes/config.php';

try {
    // Create tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            phone VARCHAR(15),
            gender ENUM('Male','Female','Other'),
            dob DATE,
            password VARCHAR(255) NOT NULL,
            department_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        )
    ");

    // Add other table creation queries here...

    // Create default admin
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO admins (username, password)
        VALUES (:username, :password)
    ");
    
    $stmt->execute([
        ':username' => $username,
        ':password' => $password
    ]);

    echo "Database setup completed successfully!";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}