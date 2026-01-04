<?php
session_start();
include "db.php";

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle Add Teacher
if (isset($_POST['add_teacher'])) { 
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role, created_at) VALUES (?, ?, ?, 'teacher', NOW())");
    

    $stmt->bind_param("sss", $fullname, $email, $password);
    $stmt->execute();

    header("Location: manage_teachers.php");
    exit();
}

// Handle Delete Teacher
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $conn->query("DELETE FROM users WHERE id=$id AND role='teacher'");
    // Deletes the teacher from the users table.

    header("Location: manage_teachers.php");
    exit();
}

// Handle Edit Teacher
if (isset($_POST['edit_teacher'])) { 
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    if (!empty($_POST['password'])) {

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, password=? WHERE id=? AND role='teacher'");
        

        $stmt->bind_param("sssi", $fullname, $email, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname=?, email=? WHERE id=? AND role='teacher'");
        
        $stmt->bind_param("ssi", $fullname, $email, $id);
    }
    $stmt->execute();

    header("Location: manage_teachers.php");
    exit();
}

// Fetch all teachers
$teachers = $conn->query("SELECT * FROM users WHERE role='teacher'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teachers | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mstyle.css">
    <style>
        .add-teacher-form,
        .edit-teacher-form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
    </style>
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
            <h2>Manage Teachers</h2>

            <!-- Add Teacher Form -->
            <div class="add-teacher-form">
                <h3>Add New Teacher</h3>
                <form method="POST">
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="add_teacher">Add Teacher</button>
                </form>
            </div>

            <!-- Teacher List -->
            <table>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>

                <?php while ($teacher = $teachers->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $teacher['id']; ?></td>
                        <td><?php echo htmlspecialchars($teacher['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                        <td>

                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $teacher['id']; ?>">

                                <input type="text" name="fullname"
                                    value="<?php echo htmlspecialchars($teacher['fullname']); ?>" required>

                                <input type="email" name="email" value="<?php echo htmlspecialchars($teacher['email']); ?>"
                                    required>
                                    
                                <input type="password" name="password" placeholder="New password">

                                <button type="submit" name="edit_teacher">Update</button>
                            </form>
                            
                            <a href="manage_teachers.php?delete_id=<?php echo $teacher['id']; ?>" onclick="return confirm('Delete this teacher?')">Delete</a>
                        
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>

    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>
</body>

</html>