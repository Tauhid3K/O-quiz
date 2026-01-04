<?php
session_start();
include "db.php";

// Check if user is teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch all results with proper columns
$query = "SELECT r.id, u.fullname AS student_name, q.title AS quiz_title, 
                 r.score, r.total_possible, r.percentage, r.attempted_on
          FROM results r
          JOIN users u ON r.user_id = u.id
          JOIN quizzes q ON r.quiz_id = q.id
          WHERE q.created_by = ?
          ORDER BY r.attempted_on DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$results = $stmt->get_result();


if (!$results) {
    $error_message = "Failed to fetch results: " . $conn->error;
    $results = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="qstyle.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            color: white;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ffd700;
        }

        th {
            background-color: rgba(255, 215, 0, 0.2);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="teacher_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main>
        <div class="quiz-container">
            <div class="quiz-form">
                <h2>Student Results</h2>

                <?php if (!empty($error_message)): ?>
                    <p class="quiz-error"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <?php if ($results && $results->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Quiz Title</th>
                                <th>Score</th>
                                <th>Total</th>
                                <th>Percentage</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php while ($row = $results->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quiz_title']); ?></td>
                                    <td><?php echo $row['score']; ?></td>
                                    <td><?php echo $row['total_possible']; ?></td>
                                    <td><?php echo $row['percentage']; ?>%</td>
                                    <td><?php echo $row['attempted_on']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No results available yet.</p>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

</body>

</html>