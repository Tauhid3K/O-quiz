<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {
    header("Location: login.html");
    exit;
}

include 'db.php';

// Fetch results for logged-in student
$email = $_SESSION['email'];
$sql = "SELECT r.score, q.quiz_name, r.attempted_on 
        FROM results r
        JOIN quizzes q ON r.quiz_id = q.id
        WHERE r.student_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>My Quiz Results</h1>";

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Quiz Name</th><th>Score</th><th>Date</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['quiz_name']."</td>";
        echo "<td>".$row['score']."</td>";
        echo "<td>".$row['attempted_on']."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found yet. Take a quiz first!</p>";
}

echo "<br><a href='student_dashboard.php'>Back to Dashboard</a>";

$conn->close();
?>
