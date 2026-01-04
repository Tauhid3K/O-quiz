<?php
session_start();
include "db.php";

// Check student login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;
if ($quiz_id <= 0) die("Invalid Quiz ID.");

// Fetch quiz details
$qres = $conn->query("SELECT time_limit_minutes FROM quizzes WHERE id=$quiz_id");
if (!$qres || $qres->num_rows == 0) die("Quiz not found.");
$quiz = $qres->fetch_assoc();
$time_limit_seconds = ((int)$quiz['time_limit_minutes']) * 60;

// Fetch questions
$questions = $conn->query("SELECT * FROM questions WHERE quiz_id=$quiz_id ORDER BY id ASC");
if (!$questions || $questions->num_rows == 0) die("No questions found.");

// Check for existing in-progress attempt
$attempt = $conn->query("
    SELECT * FROM attempts
    WHERE user_id=$user_id AND quiz_id=$quiz_id AND status='in_progress'
    ORDER BY id DESC LIMIT 1
");

if ($attempt && $attempt->num_rows > 0) {

    $row = $attempt->fetch_assoc();
    $attempt_id = $row['id'];
    $start_time = strtotime($row['started_at']);
    $end_time = strtotime($row['end_time']);


    if (!$end_time) {
        $end_time = $start_time + $time_limit_seconds;
        $conn->query("UPDATE attempts SET end_time=FROM_UNIXTIME($end_time) WHERE id=$attempt_id");
    }

} else {

    $res = $conn->query("SELECT MAX(attempt_no) AS max_attempt FROM attempts WHERE user_id=$user_id AND quiz_id=$quiz_id");
    $attempt_no = 1;
    if ($r = $res->fetch_assoc()) {
        $attempt_no = ((int)$r['max_attempt']) + 1;
    }

    $now = time();
    $end_time_val = $now + $time_limit_seconds;
    $end_time_str = date('Y-m-d H:i:s', $end_time_val);

    $conn->query("
        INSERT INTO attempts (user_id, quiz_id, attempt_no, status, started_at, end_time)
        VALUES ($user_id, $quiz_id, $attempt_no, 'in_progress', NOW(), '$end_time_str')
    ");

    $attempt_id = $conn->insert_id;

    $row = $conn->query("SELECT started_at, end_time FROM attempts WHERE id=$attempt_id")->fetch_assoc();
    $start_time = strtotime($row['started_at']);
    $end_time = strtotime($row['end_time']);
}

// Calculate remaining time
$remaining_seconds = max(0, $end_time - time());

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $total_score = 0;
    $total_possible = 0;

    $questions->data_seek(0);
    while ($q = $questions->fetch_assoc()) {
        $qid = $q['id'];
        $selected = $_POST['answer'][$qid] ?? '';
        $is_correct = ($selected === $q['correct_answer']) ? 1 : 0;
        $points = $is_correct ? (int)$q['points'] : 0;

        $total_score += $points;
        $total_possible += (int)$q['points'];

        // Save answer
        $stmt = $conn->prepare("
            INSERT INTO answers (attempt_id, question_id, answer, is_correct, points_earned)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisii", $attempt_id, $qid, $selected, $is_correct, $points);
        $stmt->execute();
        $stmt->close();
    }

    $percentage = ($total_possible > 0) ? round(($total_score/$total_possible)*100, 2) : 0;
    $time_spent = time() - $start_time;

    // Save result
    $stmt = $conn->prepare("
        INSERT INTO results (attempt_id, user_id, quiz_id, score, total_possible, percentage)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiiidd", $attempt_id, $user_id, $quiz_id, $total_score, $total_possible, $percentage);
    $stmt->execute();
    $stmt->close();

    // Update attempt
    $stmt = $conn->prepare("
        UPDATE attempts
        SET status='completed', finished_at=NOW(), total_time_seconds=?
        WHERE id=?
    ");

    $stmt->bind_param("ii", $time_spent, $attempt_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
        alert('Quiz Submitted! Score: $total_score / $total_possible');
        window.location='student_dashboard.php';
    </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Take Quiz | O'Quiz</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="tstyle.css">
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
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

<h2>Take Quiz</h2>

<div id="timer">Time Left: <span id="time"></span></div>

<form method="POST">

<?php
$count = 1;
$questions->data_seek(0);
while ($q = $questions->fetch_assoc()):
    $options = [
        'A' => $q['option_a'],
        'B' => $q['option_b'],
        'C' => $q['option_c'],
        'D' => $q['option_d']
    ];
?>
<div class="question-item">
    <p><strong>Q<?= $count++; ?>:</strong> <span class="mathjax"><?= htmlspecialchars($q['question_text']); ?></span></p>

    <div class="options">
        <?php foreach ($options as $key => $val): ?>
            <?php if (!empty($val)): ?>
            <label>
                <input type="radio" name="answer[<?= $q['id']; ?>]" value="<?= $key; ?>">
                <span class="mathjax"><?= htmlspecialchars($val); ?></span>
            </label>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<hr>
<?php endwhile; ?>

<button type="submit">Submit Quiz</button>

</form>
</div>
</div>
</main>

<footer>
<p>© 2025 O’Quiz | Designed by Tauhid</p>
</footer>

<script>

// TIMER SCRIPT
let totalSeconds = <?= $remaining_seconds ?>;

function startTimer() {
    const display = document.getElementById('time');
    const interval = setInterval(() => {
        let minutes = Math.floor(totalSeconds / 60);
        let seconds = totalSeconds % 60;

        display.textContent =
            (minutes < 10 ? '0' : '') + minutes + ':' +
            (seconds < 10 ? '0' : '') + seconds;

        totalSeconds--;

        if (totalSeconds < 0) {
            clearInterval(interval);
            alert("Time is up! Quiz will be submitted.");
            document.querySelector("form").submit();
        }
    }, 1000);
}

// Initialize MathJax and start timer on page load
window.onload = function() {
    startTimer();
    MathJax.typesetPromise();
};
</script>

</body>
</html>
