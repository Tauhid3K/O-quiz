<?php
session_start();  

// Check teacher login
if (!isset($_SESSION['user_id']) /*for user login*/ || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$quiz_id = isset($_GET['quiz_id']) ? (int) $_GET['quiz_id'] : 0; 

if ($quiz_id <= 0) {
    die("Invalid Quiz ID.");
}

// Fetch all questions for this quiz
$questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id ORDER BY id ASC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions List | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="qstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>

<body>

    <!-- NAV -->
    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="teacher_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <main>
        <div class="quiz-container">
            <div class="quiz-form">
                <h2>Questions List</h2>

                <!-- Add Question Button -->
                <a href="edit_question.php?quiz_id=<?php echo $quiz_id; ?>"
                    style="display:block; text-align:center; margin-bottom:20px; text-decoration:none; background-color:#ffd700; color:#1b3953; padding:10px 0; border-radius:6px; font-weight:bold;">
                    Add Question
                </a>

                <!-- Questions List -->
                <?php if ($questions && $questions->num_rows > 0): ?> 
                    <div class="question-list">
                        <ul>
                            <?php $count = 1; ?>
                            <?php while ($q = $questions->fetch_assoc()): ?>

                                <li>
                                    <div class="question-item">
                                        <p><strong>Q<?php echo $count++; ?>:</strong> 
                                            <span class="mathjax"><?php echo htmlspecialchars($q['question_text']); ?></span> 
                                        </p> 
                                        <p class="points">Points: <?php echo htmlspecialchars($q['points']); ?></p>
                                    </div>
                                    <p>A: <span class="mathjax"><?php echo htmlspecialchars($q['option_a']); ?></span></p>
                                    <p>B: <span class="mathjax"><?php echo htmlspecialchars($q['option_b']); ?></span></p>
                                    <p>C: <span class="mathjax"><?php echo htmlspecialchars($q['option_c']); ?></span></p>
                                    <p>D: <span class="mathjax"><?php echo htmlspecialchars($q['option_d']); ?></span></p>
                                    <p class="correct-answer">Correct Answer:
                                        <?php echo htmlspecialchars($q['correct_answer']); ?>
                                    </p> 

                                    <p><a href="edit_question.php?id=<?php echo $q['id']; ?>&quiz_id=<?php echo $quiz_id; ?>" 
                                            style="color:#ffd700; font-weight:bold;">Edit</a></p> 
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <p style="text-align:center; color:#ffcc00;">No questions added yet. Click "Add Question" to create one.
                    </p>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

    <script>
        window.addEventListener('load', function () {
            MathJax.typesetPromise();
        });
    </script>

</body>

</html>