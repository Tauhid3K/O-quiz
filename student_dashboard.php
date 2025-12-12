<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {
    header("Location: login.html");
    exit;
}

include 'db.php';

// Fetch all quizzes
$sql = "SELECT * FROM quizzes ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard | O'Quiz</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="nav">
    <h1 class="logo">O'Quiz</h1>
    <div class="nav_links">
        <a href="index.html">Home</a>
        <a href="student_dashboard.php">Dashboard</a>
        <a href="result.php">My Results</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<section class="dashboard">
    <h2>Welcome, <?php echo $_SESSION['email']; ?></h2>
    <p>Select a quiz to attempt:</p>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($quiz = $result->fetch_assoc()) {
                echo "<li><a href='take_quiz.php?quiz_id=".$quiz['id']."'>".$quiz['title']."</a></li>";
            }
        } else {
            echo "<li>No quizzes available yet.</li>";
        }
        ?>
    </ul>
</section>

<footer>
    <p>© 2025 O’Quiz | Designed by Tauhid</p>
</footer>
</body>
</html>
<?php $conn->close(); ?>
