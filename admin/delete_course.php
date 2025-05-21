<?php
session_start();
require_once "../includes/config.php";

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: dashboard.php");
exit;
