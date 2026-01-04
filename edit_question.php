<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$quiz_id = isset($_GET['quiz_id']) ? (int) $_GET['quiz_id'] : 0; 
$qid = isset($_GET['id']) ? (int) $_GET['id'] : 0; 

$message = '';

// Validate quiz
if ($quiz_id <= 0) {
    die("Invalid Quiz ID.");
}

// Initialize empty values
$question_text = $option_a = $option_b = $option_c = $option_d = $correct_answer = $feedback = '';
$points = 1; 

// If editing existing question, fetch it
if ($qid > 0) {
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id=? AND quiz_id=?"); 
    $stmt->bind_param("ii", $qid, $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();
    $stmt->close();

    if ($question) { 
        $question_text = $question['question_text'];
        $option_a = $question['option_a'];
        $option_b = $question['option_b'];
        $option_c = $question['option_c'];
        $option_d = $question['option_d'];
        $correct_answer = $question['correct_answer'];
        $feedback = $question['answer_feedback'];
        $points = $question['points']; 
    } else {
        die("Invalid Question ID.");
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];
    $feedback = $_POST['feedback'];
    $points = (int) $_POST['points']; 

    if ($qid > 0) {
        // Update existing question
        $stmt = $conn->prepare(
            "UPDATE questions SET question_text=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_answer=?, answer_feedback=?, points=? 
            WHERE id=? AND quiz_id=?"
        );
        $stmt->bind_param("sssssssiii", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $feedback, $points, $qid, $quiz_id);
    } else {
        // Add new question
        $stmt = $conn->prepare(
            "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer, answer_feedback, points)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssssssi", $quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $feedback, $points);
    }

    if ($stmt->execute()) { //runs the INSERT or UPDATE query
        $stmt->close();
        header("Location: add_questions.php?quiz_id=$quiz_id");
        exit();
    } else {
        $message = "Error saving question: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $qid ? 'Edit' : 'Add'; ?> Question | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="qstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>

<body>

    <!-- NAV -->
    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="add_questions.php?quiz_id=<?php echo $quiz_id; ?>">Back</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main>
        <div class="quiz-container">
            <div class="quiz-form">
                <h2><?php echo $qid ? 'Edit' : 'Add'; ?> Question</h2>

                        <?php if ($message): ?>
                    <p class="quiz-error"><?php echo $message; ?></p>
                        <?php endif; ?>

                <form method="post">
                    <div class="form-group">
                        <label for="question_text">Question:</label>
                        <textarea id="question_text" name="question_text"
                            placeholder="Question (use $$...$$ for Equation)"
                            required><?php echo htmlspecialchars($question_text); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="option_a">Option A:</label>
                        <input type="text" id="option_a" name="option_a"
                            placeholder="Option A (use $$...$$ for Equation)"
                            value="<?php echo htmlspecialchars($option_a); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="option_b">Option B:</label>
                        <input type="text" id="option_b" name="option_b"
                            placeholder="Option B (use $$...$$ for Equation)"
                            value="<?php echo htmlspecialchars($option_b); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="option_c">Option C:</label>
                        <input type="text" id="option_c" name="option_c"
                            placeholder="Option C (use $$...$$ for Equation)"
                            value="<?php echo htmlspecialchars($option_c); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="option_d">Option D:</label>
                        <input type="text" id="option_d" name="option_d"
                            placeholder="Option D (use $$...$$ for Equation)"
                            value="<?php echo htmlspecialchars($option_d); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="correct_answer">Correct Answer:</label>
                        <select id="correct_answer" name="correct_answer" required>
                            <option value="">Select correct answer</option>
                            <option value="A" <?php if ($correct_answer == 'A')
                                echo 'selected'; ?>>A</option>
                            <option value="B" <?php if ($correct_answer == 'B')
                                echo 'selected'; ?>>B</option>
                            <option value="C" <?php if ($correct_answer == 'C')
                                echo 'selected'; ?>>C</option>
                            <option value="D" <?php if ($correct_answer == 'D')
                                echo 'selected'; ?>>D</option>
                        </select>
                    </div>

                    <!-- NEW POINTS FIELD -->
                    <div class="form-group">
                        <label for="points">Points:</label>
                        <input type="number" id="points" name="points" min="1"
                            value="<?php echo htmlspecialchars($points); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="feedback">Answer Feedback:</label>
                        <textarea id="feedback" name="feedback"
                            placeholder="Answer Feedback (optional)"><?php echo htmlspecialchars($feedback); ?></textarea>
                    </div>

                    <button type="submit"><?php echo $qid ? 'Update' : 'Add'; ?> Question</button>
                </form>
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