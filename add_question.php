<?php
include "db.php";

$quiz_id = $_POST['quiz_id'];
$question = $_POST['question'];
$type = $_POST['type'];
$op1 = $_POST['option1'];
$op2 = $_POST['option2'];
$op3 = $_POST['option3'];
$op4 = $_POST['option4'];
$answer = $_POST['answer'];

$sql = "INSERT INTO questions (quiz_id, question, q_type, op1, op2, op3, op4, answer)
        VALUES ('$quiz_id', '$question', '$type', '$op1', '$op2', '$op3', '$op4', '$answer')";

if ($conn->query($sql)) {
    echo "Question Added!<br>";
    echo "<a href='../backend/fetch_quizzes.php'>Add More</a>";
} else {
    echo "Error!";
}
?>
