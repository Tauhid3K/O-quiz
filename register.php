<?php
session_start();
include "db.php";

$error = '';
$success = '';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get POST values
  $fullname = trim($_POST['fullname'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Basic validations
  if (!$fullname || !$email || !$password || !$confirm_password) {
    $error = "Please fill in all fields!";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format!";
  } elseif ($password !== $confirm_password) {
    $error = "Passwords do not match!";
  } else {

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "Email already registered!";
    } else {
      // Hash password and insert user
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $conn->prepare(
        "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)"
      );
      $role = 'student';
      $stmt->bind_param("ssss", $fullname, $email, $hashed_password, $role);

      if ($stmt->execute()) {
        $success = "Registration successful! You can now <a href='login.php'>login</a>.";
      } else {
        $error = "Registration failed! Please try again.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | O'Quiz</title>
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
        <h2>Register</h2>

        <?php if ($error): ?>
          <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($success): ?>
          <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="register.php" method="post">
          <input type="text" name="fullname" placeholder="Full Name" required
            value="<?php echo htmlspecialchars($fullname ?? ''); ?>">
          <input type="email" name="email" placeholder="Email" required
            value="<?php echo htmlspecialchars($email ?? ''); ?>">
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
      </div>
    </section>
  </main>
  <footer>
    <p>© 2025 O’Quiz | Designed by Tauhid</p>
  </footer>

</body>

</html>