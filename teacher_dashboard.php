<?php
session_start();
include "db.php";

// Check if user is teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Get total quizzes (no teacher_id filter)
$quizResult = $conn->query("SELECT COUNT(*) AS total FROM quizzes");
$quizCount = $quizResult ? $quizResult->fetch_assoc()['total'] : 0;

// Get total results (no teacher_id filter)
$resultResult = $conn->query("SELECT COUNT(*) AS total FROM results");
$resultCount = $resultResult ? $resultResult->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <!-- NAVBAR -->
    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="teacher_dashboard.php">Dashboard</a>
            <a href="create_quiz.php">Create Quiz</a>
            <a href="my_quizzes.php">Quizzes</a>
            <a href="view_results.php">Student Results</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <main>
        <!-- DASHBOARD -->
        <div class="dashboard">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?> 👋</h2>
            <p><strong>Teacher Panel</strong></p>

            <div class="dashboard-overview">
                <a class="card" href="create_quiz.php">
                    <h3>Create New Quiz</h3>
                    <p>Build a new quiz for students</p>
                </a>

                <a class="card" href="my_quizzes.php">
                    <h3>Quizzes</h3>
                    <p>View and manage your quizzes</p>
                </a>

                <a class="card" href="view_results.php">
                    <h3>Student Results</h3>
                    <p>View student results and performance</p>
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