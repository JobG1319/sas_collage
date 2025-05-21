<?php
session_start();
require_once "../includes/config.php";

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

// Get total counts
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$total_courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$total_registrations = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();

// Get registrations per course
$data = $pdo->query("
    SELECT courses.name, COUNT(registrations.id) as total 
    FROM courses 
    LEFT JOIN registrations ON courses.id = registrations.course_id 
    GROUP BY courses.id
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Stats</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; padding: 40px; background: #f7f7f7; }
        .card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 0 10px #ccc; }
    </style>
</head>
<body>
    <h2>Admin Dashboard - Stats</h2>

    <div class="card">
        <p><strong>Total Students:</strong> <?= $total_students ?></p>
        <p><strong>Total Courses:</strong> <?= $total_courses ?></p>
        <p><strong>Total Registrations:</strong> <?= $total_registrations ?></p>
    </div>

    <div class="card">
        <h3>Registrations per Course</h3>
        <canvas id="courseChart" width="600" height="300"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('courseChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($data, 'name')) ?>,
                datasets: [{
                    label: 'Registrations',
                    data: <?= json_encode(array_column($data, 'total')) ?>,
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Course Registrations' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
