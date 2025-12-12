<?php
session_start();
$conn = new mysqli("localhost", "root", "", "oquiz_db");

$user_id = $_SESSION['user_id'];
$quiz_id = $_POST['quiz_id'];

$q = $conn->query("SELECT * FROM questions WHERE quiz_id=$quiz_id");

$score = 0;
$total = $q->num_rows;

while($row = $q->fetch_assoc()) {
    $qid = $row['id'];
    $correct = $row['correct_answer'];

    $ans = $_POST["q$qid"] ?? "";

    // Save student answer
    $stmt = $conn->prepare("INSERT INTO answers (user_id, quiz_id, question_id, answer) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $quiz_id, $qid, $ans);
    $stmt->execute();

    // Marking
    if (trim(strtolower($ans)) == trim(strtolower($correct))) {
        $score++;
    }
}

echo "<h1>Your Score: $score / $total</h1>";
?>
