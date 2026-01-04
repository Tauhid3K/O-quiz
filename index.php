<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'teacher') {
        header("Location: teacher_dashboard.php");
        exit();
    } else {
        header("Location: student_dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'Quiz - Conduct Quizzes Online</title>

    <meta name="description" content="O'Quiz: Conduct quizzes online, get instant results, and simplify learning & assessment.">

    <meta property="og:title" content="O'Quiz - Conduct Quizzes Online">
    <meta property="og:description" content="O'Quiz: Conduct quizzes online, get instant results, and simplify learning & assessment.">
    <meta property="og:image" content="https://oquiz.tauhidshahriar.xyz/assets/preview.png">
    <meta property="og:url" content="https://oquiz.tauhidshahriar.xyz">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="O'Quiz - Conduct Quizzes Online">
    <meta name="twitter:description" content="O'Quiz: Conduct quizzes online, get instant results, and simplify learning & assessment.">
    <meta name="twitter:image" content="https://oquiz.tauhidshahriar.xyz/assets/preview.png">

    <link rel="icon" href="assets/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="nav">
    <h1 class="logo">O'Quiz</h1>
    <nav class="nav_links">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="about.html">About</a>
        <a href="contact.html">Contact</a>
    </nav>
</header>

<main>

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to O'Quiz</h1>
            <p>Conduct quizzes online, get instant results, and simplify learning & assessment</p>
            <div class="hero-buttons">
                <a class="btn btn-primary" href="login.php">Login</a>
                <a class="btn btn-secondary" href="register.php">Register</a>
            </div>
        </div>
    </section>

    <section class="hero1">
        <div class="hero-content1">
            <h1>Build a Test</h1>
            <div class="hero-buttons">
                <a class="btn btn-primary" href="quiz.html">Build</a>
            </div>
        </div>
    </section>

    <section class="features">
        <h2>Why Use O'Quiz?</h2>
        <div class="feature-box">
            <p>Create and manage quizzes easily.</p>
            <p>Instant grading and results.</p>
            <p>Accessible from anywhere.</p>
        </div>
    </section>

</main>

<!-- FOOTER -->
<footer>
    <p>© 2025 O'Quiz | Designed by Tauhid</p>
</footer>

</body>
</html>
