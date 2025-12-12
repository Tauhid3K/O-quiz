<?php
include "db.php";

$result = $conn->query("SELECT * FROM quizzes");

echo "<h2>Select Quiz to Add Questions</h2>";

while ($row = $result->fetch_assoc()) {
    echo "<a href='../add_question.html?quiz_id={$row['id']}'>";
    echo $row['title'] . " (" . $row['subject'] . ")";
    echo "</a><br>";
}
?>
