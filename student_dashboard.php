<?php
session_start();

// Check login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | O'Quiz</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<!-- NAVBAR -->
<div class="nav">
    <h1 class="logo">O'Quiz</h1>
    <div class="nav_links">
        <a href="student_dashboard.php">Dashboard</a>
        <a href="available_quizzes.php">Quizzes</a>
        <a href="my_results.php">Results</a>
        <a href="logout.php">Logout</a>
        <a href="profile.php">Profile</a>
    </div>
</div>
<main>
<!-- DASHBOARD -->
<div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?> 👋</h2>
    <p><strong>Student Panel</strong></p>

    <div class="dashboard-overview">
        <a class="card" href="available_quizzes.php">
            <h3>Available Quizzes</h3>
            <p>View and attempt quizzes</p>
        </a>

        <a class="card" href="my_results.php">
            <h3>My Results</h3>
            <p>Check your quiz results</p>
        </a>

        <a class="card" href="profile.php">
            <h3>Profile</h3>
            <p>View your profile information</p>
        </a>
    </div>
</div>
</main>
<!-- FOOTER -->
<footer>
    <p>© 2025 O’Quiz | Designed by Tauhid</p>
</footer>

</body>
</html>
