<?php
session_start();
include "db.php";

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get quiz ID
$quiz_id = isset($_GET['quiz_id']) ? (int) $_GET['quiz_id'] : 0;

if ($quiz_id <= 0) {
    die("Invalid Quiz ID.");
}

// Fetch quiz info
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz_result = $stmt->get_result();
$quiz = $quiz_result->fetch_assoc();
$stmt->close();

if (!$quiz) {
    die("Quiz not found.");
}

// Fetch all questions for this quiz
$stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$questions_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="qstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>

<body>

    <!-- NAVBAR -->
    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_quizzes.php">Manage Quizzes</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main>
        <div class="quiz-container">

            <div class="quiz-form">
                <h2>Quiz: <?php echo htmlspecialchars($quiz['title']); ?></h2>
                <p>Subject: <?php echo htmlspecialchars($quiz['subject']); ?></p>
                <a href="manage_quizzes.php" style="color:#ffd700;">← Back to Quizzes</a>

                <div class="question-list">
                    <h3>Questions</h3>
                    <?php if ($questions_result->num_rows > 0): ?>
                        <ul>
                            <?php $count = 1; ?>
                            <?php while ($q = $questions_result->fetch_assoc()): ?>
                                <li>
                                    <p><strong>Q<?php echo $count++; ?>:</strong> <span
                                            class="mathjax"><?php echo htmlspecialchars($q['question_text']); ?></span></p>
                                    <p>A: <span class="mathjax"><?php echo htmlspecialchars($q['option_a']); ?></span></p>
                                    <p>B: <span class="mathjax"><?php echo htmlspecialchars($q['option_b']); ?></span></p>
                                    <p>C: <span class="mathjax"><?php echo htmlspecialchars($q['option_c']); ?></span></p>
                                    <p>D: <span class="mathjax"><?php echo htmlspecialchars($q['option_d']); ?></span></p>
                                    <p class="correct-answer">Correct Answer:
                                        <?php echo htmlspecialchars($q['correct_answer']); ?></p>
                                    <p>Feedback: <?php echo htmlspecialchars($q['answer_feedback']); ?></p>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <script>
                            // Render MathJax for all .mathjax spans
                            window.addEventListener('load', function () {
                                MathJax.typesetPromise();
                            });
                        </script>
                    <?php else: ?>
                        <p>No questions added yet for this quiz.</p>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

</body>

</html>