<?php
require_once "includes/config.php";

$hash = password_hash("123456", PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->execute(["admin", $hash]);

echo "Admin created!";
