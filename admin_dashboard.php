<?php
session_start();
include "db.php";

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch overview counts (example)
$studentCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'")->fetch_assoc()['total'];
$teacherCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='teacher'")->fetch_assoc()['total'];
$quizCount = $conn->query("SELECT COUNT(*) AS total FROM quizzes")->fetch_assoc()['total'];
$resultCount = $conn->query("SELECT COUNT(*) AS total FROM results")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | O'Quiz</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="dashboard.css"> <!-- Separate CSS for dashboard -->
</head>
<body>

<div class="nav">
    <h1 class="logo">O'Quiz</h1>
    <div class="nav_links">
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
<main>
<div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
    <p><strong>Admin Pannel</strong>.</p>

    <div class="dashboard-overview">
        <a class="card" href="manage_students.php">
            <h3>Students</h3>
            <p><?php echo $studentCount; ?> Total</p>
        </a>
        <a class="card" href="manage_teachers.php">
            <h3>Teachers</h3>
            <p><?php echo $teacherCount; ?> Total</p>
        </a>
        <a class="card" href="manage_quizzes.php">
            <h3>Quizzes</h3>
            <p><?php echo $quizCount; ?> Total</p>
        </a>
        <a class="card" href="manage_results.php">
            <h3>Results</h3>
            <p><?php echo $resultCount; ?> Total</p>
        </a>
    </div>
</div>
</main>
<footer>
    <p>© 2025 O’Quiz | Designed by Tauhid</p>
</footer>

</body>
</html>
