<?php
session_start();
include "db.php";

// Check student login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($fullname) && !empty($email)) {
        if (!empty($password)) {
            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE `users` SET `fullname`=?, `email`=?, `password`=? WHERE `id`=?");

            if (!$stmt)
                die("Prepare failed: " . $conn->error);
            $stmt->bind_param("sssi", $fullname, $email, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE `users` SET `fullname`=?, `email`=? WHERE `id`=?");

            if (!$stmt)
                die("Prepare failed: " . $conn->error);
            $stmt->bind_param("ssi", $fullname, $email, $user_id);
        }
        $stmt->execute();
        $stmt->close();
        $message = "Profile updated successfully!";
    } else {
        $message = "Full Name and Email cannot be empty.";
    }
}

// Fetch student info
$stmt = $conn->prepare("SELECT `fullname`, `email`, `role` FROM `users` WHERE `id` = ?");

if (!$stmt)
    die("Prepare failed: " . $conn->error);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("User not found.");
}
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | O'Quiz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="pstyle.css">
</head>

<body>

    <div class="nav">
        <h1 class="logo">O'Quiz</h1>
        <div class="nav_links">
            <a href="student_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <main>
        <div class="profile-container">
            <div class="profile-form">
                <h2>My Profile</h2>

                <?php if (!empty($message)): ?>
                    <p style="color: #ffd700; font-weight: bold; text-align:center;">
                        <?php echo htmlspecialchars($message); ?>
                    </p>
                <?php endif; ?>

                <!-- Display Info -->
                <div class="profile-info">
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
                </div>

                <hr style="margin:20px 0; border-color:#ffd700;">

                <!-- Edit Profile Form -->
                <h3>Edit Profile</h3>
                <form method="POST" action="">
                    <label for="fullname">Full Name:</label>
                    <input type="text" name="fullname" id="fullname"
                        value="<?php echo htmlspecialchars($user['fullname']); ?>"
                        style="width:100%; padding:8px; margin-bottom:15px; border-radius:5px; border:1px solid #c0d4e0;">

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                        style="width:100%; padding:8px; margin-bottom:15px; border-radius:5px; border:1px solid #c0d4e0;">

                    <label for="password">New Password:</label>
                    <input type="password" name="password" id="password"
                        placeholder="Leave empty to keep current password"
                        style="width:100%; padding:8px; margin-bottom:15px; border-radius:5px; border:1px solid #c0d4e0;">

                    <button type="submit" name="update_profile"
                        style="background-color:#ffd700; color:#1b3953; font-weight:bold; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Update
                        Profile</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>© 2025 O’Quiz | Designed by Tauhid</p>
    </footer>

</body>

</html>