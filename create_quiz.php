<?php
session_start();
include "db.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}
$message = ''; // Initialize message variable to avoid undefined warnings

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    $title = trim($_POST['title']); 
    $subject = trim($_POST['subject']);
    $chapter = !empty($_POST['chapter']) ? trim($_POST['chapter']) : null; 
    $special_exam = !empty($_POST['special_exam']) ? trim($_POST['special_exam']) : null;

    $stmt = $conn->prepare(
        "INSERT INTO quizzes (title, subject, chapter, special_exam_name, created_by)
         VALUES (?, ?, ?, ?, ?)"  //prepared statement (safe against SQL injection)
    );

    $stmt->bind_param( 
        "ssssi", //s → string  i → integer 
        $title,
        $subject,
        $chapter,
        $special_exam,
        $_SESSION['user_id']
    );

    if ($stmt->execute()) { 
        $quiz_id = $stmt->insert_id; 
        header("Location: add_questions.php?quiz_id=$quiz_id");
        exit();
    } else {
        $message = "Error creating quiz!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="qstyle.css">
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
                <h2>Create Quiz</h2>

                <?php if ($message): ?>
                    <p class="quiz-error"><?php echo $message; ?></p>
                <?php endif; ?>

                <form method="post">
                    <label for="title">Quiz Title</label>
                    <input type="text" id="title" name="title" placeholder="Enter Quiz Title" required>

                    <label for="subject">Subject Name</label>
                    <input type="text" id="subject" name="subject" placeholder="Enter Subject Name" required>

                    <label for="chapter">Chapter Name (Optional)</label>
                    <input type="text" id="chapter" name="chapter" placeholder="Enter Chapter Name">

                    <label for="special_exam">Special Exam (Optional)</label>
                    <input type="text" id="special_exam" name="special_exam" placeholder="Enter Special Exam Name">

                    <button type="submit">Create Quiz</button>
                </form>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

</body>

</html>