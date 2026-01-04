<?php
session_start();
include "db.php";

// Check student login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Fetch all quizzes with question count and total points
$sql = "
    SELECT q.id, q.title, q.subject, q.chapter, q.special_exam_name, 
           q.time_limit_minutes,
           COUNT(que.id) AS total_questions,
           COALESCE(SUM(que.points), 0) AS total_points
    FROM quizzes q
    LEFT JOIN questions que ON q.id = que.quiz_id
    GROUP BY q.id
    ORDER BY q.id DESC
";

$quizzes = $conn->query($sql);

// Separate into regular and special exams
$regular_quizzes = [];
$special_exams = [];

while($q = $quizzes->fetch_assoc()) {
    if($q['special_exam_name']) {
        $special_exams[] = $q;
    } else {
        $regular_quizzes[] = $q;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Available Quizzes | O'Quiz</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="qstyle.css">
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
    <div class="quiz-form">
        <h2>Available Quizzes</h2>

        <?php if(count($regular_quizzes) > 0): ?>
            <div class="table-wrapper">
            <table class="quiz-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Chapter</th>
                        <th>Total Marks</th>
                        <th>Duration (minutes)</th>
                        <th>Questions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($regular_quizzes as $q): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($q['title']); ?></td>
                        <td><?php echo htmlspecialchars($q['subject']); ?></td>
                        <td><?php echo $q['chapter'] ? htmlspecialchars($q['chapter']) : '-'; ?></td>
                        <td><?php echo (int)$q['total_points']; ?></td>
                        <td><?php echo (int)$q['time_limit_minutes']; ?></td>
                        <td><?php echo (int)$q['total_questions']; ?></td>
                        <td>
                            <a href="take_quiz.php?quiz_id=<?php echo $q['id']; ?>" class="btn-start">Start</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php else: ?>
            <p class="no-data">No quizzes available.</p>
        <?php endif; ?>

        <?php if(count($special_exams) > 0): ?>
            <h2>Special Exams</h2>
            <div class="table-wrapper">
            <table class="quiz-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Chapter</th>
                        <th>Special Exam</th>
                        <th>Total Marks</th>
                        <th>Duration (minutes)</th>
                        <th>Questions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($special_exams as $q): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($q['title']); ?></td>
                        <td><?php echo htmlspecialchars($q['subject']); ?></td>
                        <td><?php echo $q['chapter'] ? htmlspecialchars($q['chapter']) : '-'; ?></td>
                        <td><?php echo htmlspecialchars($q['special_exam_name']); ?></td>
                        <td><?php echo (int)$q['total_points']; ?></td>
                        <td><?php echo (int)$q['time_limit_minutes']; ?></td>
                        <td><?php echo (int)$q['total_questions']; ?></td>
                        <td>
                            <a href="take_quiz.php?quiz_id=<?php echo $q['id']; ?>" class="btn-start">Take</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>
</main>

<footer>
    <p>© 2025 O’Quiz | Designed by Tauhid</p>
</footer>

</body>
</html>
