<?php
session_start();
include "db.php";

$error = ''; // Initialize error variable

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') { 

  if (isset($_POST['email'], $_POST['password'])) { 

    $email = $_POST['email'];
    $password = $_POST['password']; 

    $stmt = $conn->prepare("SELECT id, fullname, password, role FROM users WHERE email = ?");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role 
        if ($user['role'] === 'admin') {
          header("Location: admin_dashboard.php");
        } elseif ($user['role'] === 'teacher') {
          header("Location: teacher_dashboard.php");
        } else {
          header("Location: student_dashboard.php");
        }
        exit();
      } else {
        $error = "Invalid password!";
      }
    } else {
      $error = "User not found!";
    }
  } else {
    $error = "Please fill in all fields!";
  }// Handle errors 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | O'Quiz</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="lrstyle.css">
</head>

<body>

  <div class="nav">
    <h1 class="logo">O'Quiz</h1>
    <div class="nav_links">
      <a href="index.php">Home</a>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
      <a href="about.html">About</a>
      <a href="contact.html">Contact</a>
    </div>
  </div>
  <main>
    <section class="auth-section">
      <div class="auth-container">
        <h2>Login</h2>

        <?php if ($error): ?>
          <p class="error-message"><?php echo htmlspecialchars($error); ?></p> <!-- Display error message if any -->
        <?php endif; ?>


        <form action="login.php" method="post">
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p>Don't have an account? <a href="register.html">Register</a></p>
      </div>
    </section>
  </main>

  <footer>
    <p>© 2025 O’Quiz | Designed by Tauhid</p>
  </footer>

</body>

</html>