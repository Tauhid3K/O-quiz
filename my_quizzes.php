<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Handle duration update
if(isset($_POST['quiz_id'], $_POST['duration'])) {
    $quiz_id = (int)$_POST['quiz_id'];
    $duration = (int)$_POST['duration'];

    $stmt = $conn->prepare("UPDATE quizzes SET time_limit_minutes = ? WHERE id = ? AND created_by = ?");
    
    $stmt->bind_param("iii", $duration, $quiz_id, $_SESSION['user_id']);
    $stmt->execute();
}

// Fetch all quizzes created by this teacher
$teacher_id = $_SESSION['user_id'];
$quizzes = $conn->query("SELECT * FROM quizzes WHERE created_by = $teacher_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Quizzes | O'Quiz</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="qstyle.css">
</head>
<body>

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
        <h2>My Quizzes</h2>

        <?php if($quizzes->num_rows > 0): ?>
            <table class="quiz-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Chapter</th>
                    <th>Special Exam</th>
                    <th>Total Marks</th>
                    <th>Duration (minutes)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($q = $quizzes->fetch_assoc()):
                    $quiz_id = $q['id'];
                    $total_marks_result = $conn->query("SELECT SUM(points) as total FROM questions WHERE quiz_id=$quiz_id");
                    $total_marks_row = $total_marks_result->fetch_assoc();
                    $total_marks = $total_marks_row['total'] ?? 0;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($q['title']); ?></td>
                    <td><?php echo htmlspecialchars($q['subject']); ?></td>
                    <td><?php echo $q['chapter'] ? htmlspecialchars($q['chapter']) : '-'; ?></td>
                    <td><?php echo $q['special_exam_name'] ? htmlspecialchars($q['special_exam_name']) : '-'; ?></td>
                    <td><?php echo $total_marks; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="quiz_id" value="<?php echo $q['id']; ?>">
                            <input type="number" name="duration" value="<?php echo $q['time_limit_minutes'] ?? 30; ?>" min="1" style="width:80px;">
                            <button type="submit">Update Time</button>
                        </form>
                    </td>
                    <td>
                        <a href="add_questions.php?quiz_id=<?php echo $quiz_id; ?>">View Questions</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align:center; color:#ffcc00;">
            No quizzes found. <a href="create_quiz.php" style="color:#ffd700;">Create a new quiz</a>
        </p>
        <?php endif; ?>
    </div>
</div>
</main>

<footer>
    <p>© 2025 O’Quiz | Designed by Tauhid</p>
</footer>

</body>
</html>
