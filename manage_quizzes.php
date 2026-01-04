<?php
session_start();
include "db.php";

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all quizzes along with the teacher who created them
$sql = "SELECT 
            q.id, 
            q.title, 
            q.subject, 
            q.chapter, 
            q.special_exam_name, 
            u.fullname AS teacher_name
        FROM quizzes q
        JOIN users u ON q.created_by = u.id
        ORDER BY q.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes | Admin | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mstyle.css">
</head>

<body>

    <!-- NAVBAR -->
    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main>
        <div class="dashboard">
            <h2>Manage Quizzes</h2>

            <?php if ($result && $result->num_rows > 0): ?> 
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Chapter</th>
                            <th>Special Exam</th>
                            <th>Teacher</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody> <!-- Table body to list quizzes -->
                        <?php while ($quiz = $result->fetch_assoc()): ?>
                            <tr> 
                                <td><?php echo $quiz['id']; ?></td>

                                <td><?php echo htmlspecialchars($quiz['title'] ?? ''); ?></td>

                                <td><?php echo htmlspecialchars($quiz['subject'] ?? ''); ?></td>

                                <td><?php echo htmlspecialchars($quiz['chapter'] ?? ''); ?></td>

                                <td><?php echo htmlspecialchars($quiz['special_exam_name'] ?? ''); ?></td>

                                <td><?php echo htmlspecialchars($quiz['teacher_name'] ?? ''); ?></td>

                                <td>
                                    <a href="view_quiz_questions.php?quiz_id=<?php echo $quiz['id']; ?>">View Questions</a>
                                    <!-- Link to view questions of this quiz -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No quizzes found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

</body>

</html>