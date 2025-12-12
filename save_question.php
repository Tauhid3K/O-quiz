<?php
$conn = new mysqli("localhost", "root", "", "oquiz_db");

$quiz_id = $_POST['quiz_id'];
$q_text = $_POST['question_text'];
$type = $_POST['question_type'];
$a = $_POST['option_a'] ?? null;
$b = $_POST['option_b'] ?? null;
$c = $_POST['option_c'] ?? null;
$d = $_POST['option_d'] ?? null;
$correct = $_POST['correct_answer'];

$stmt = $conn->prepare("
INSERT INTO questions (quiz_id, question_text, question_type, option_a, option_b, option_c, option_d, correct_answer)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssss", $quiz_id, $q_text, $type, $a, $b, $c, $d, $correct);
$stmt->execute();

header("Location: add_question.php?quiz_id=".$quiz_id);
?>
