<?php
include "db.php";

$title = $_POST['title'];
$subject = $_POST['subject'];

$sql = "INSERT INTO quizzes (title, subject) VALUES ('$title', '$subject')";

if ($conn->query($sql)) {
    echo "Quiz Created! <br>";
    echo "<a href='../admin_dashboard.html'>Go Back</a>";
} else {
    echo "Error!";
}
?>
