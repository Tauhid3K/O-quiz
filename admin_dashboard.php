<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    // Not logged in → redirect to login page
    header("Location: login.html");
    exit;
}

// Admin logged in
echo "Welcome Admin: " . $_SESSION['admin'] . "\n";
echo "Options:\n";
echo "1. Create Quiz → create_quiz.html\n";
echo "2. Add Questions → backend/fetch_quizzes.php\n";
echo "3. Logout → logout.php\n";

// You can also handle automatic redirection like this if needed:
// header("Location: create_quiz.html"); // redirect directly to quiz creation
