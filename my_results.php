<?php
session_start();
include "db.php";

// Student only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT r.id, q.title AS quiz_title, a.attempt_no, r.score, r.total_possible, r.percentage, r.attempted_on
FROM results r
INNER JOIN attempts a ON r.attempt_id = a.id
INNER JOIN quizzes q ON r.quiz_id = q.id
WHERE r.user_id = ?
ORDER BY r.attempted_on DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Prepare Failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result(); 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Results | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="tstyle.css">
</head>

<body>

    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="student_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <main>
        <div class="quiz-container">
            <h2 style="text-align:center;color:#ffd700;margin-bottom:20px;">My Quiz Results</h2>

            <?php if ($results->num_rows === 0): ?>
                <p style="text-align:center;color:#ffcc00;">No quiz attempts yet.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse;">
                    <tr style="background:#18334b;color:#ffd700;">
                        <th>Quiz</th>
                        <th>Attempt</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>

                    <?php while ($row = $results->fetch_assoc()):
                        $pass = $row['percentage'] >= 40;
                        ?>
                        <tr style="background:#1b3953;color:white;text-align:center;">
                            <td><?= htmlspecialchars($row['quiz_title']) ?></td>
                            <td><?= $row['attempt_no'] ?></td>
                            <td><?= $row['score'] ?> / <?= $row['total_possible'] ?></td>
                            <td><?= round($row['percentage'], 2) ?>%</td>
                            <td><?= date("d M Y, h:i A", strtotime($row['attempted_on'])) ?></td>
                            <td style="color:<?= $pass ? '#7CFC00' : '#ff4d4d' ?>;font-weight:bold;">
                                <?= $pass ? 'PASS' : 'FAIL' ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

</body>

</html>