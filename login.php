<?php
session_start();

$conn = new mysqli("localhost", "root", "", "oquiz_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get login data
$email_or_username = trim($_POST['email_or_username']);
$password = trim($_POST['password']);

// Fetch user by email
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email_or_username);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1){
    $user = $result->fetch_assoc();
    
    // Verify hashed password
    if(password_verify($password, $user['password'])){
        $_SESSION['role'] = "student"; // or "admin" if you handle admins separately
        $_SESSION['email'] = $user['email'];
        header("Location: student_dashboard.php");
        exit;
    } else {
        echo "Invalid password!";
    }
} else {
    echo "User not found!";
}

$stmt->close();
$conn->close();
?>
