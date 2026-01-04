<?php
session_start();
include "db.php";

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// FETCH students
$students = $conn->query(
    "SELECT id, fullname, email FROM users WHERE role='student' ORDER BY id DESC"
);


// DELETE student (only admin action)
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id']; 

    $stmt = $conn->prepare(
        "DELETE FROM users WHERE id=? AND role='student'"
    );

    $stmt->bind_param("i", $id);//
    $stmt->execute();

    header("Location: manage_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Students | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mstyle.css">
</head>

<body>

    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <main>
        <div class="dashboard">
            <h2>Registered Students</h2>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>

                <?php while ($student = $students->fetch_assoc()): ?> 
                
                    <tr>
                        <td><?= $student['id'] ?></td> 
                        <td><?= htmlspecialchars($student['fullname']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td>
                            <a href="manage_students.php?delete_id=<?= $student['id'] ?>" onclick="return confirm('Remove this student?')"> Remove </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

        </div>
    </main>

    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

</body>

</html>