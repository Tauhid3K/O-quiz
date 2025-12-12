<?php
$conn = new mysqli("localhost", "root", "", "oquiz_db");
$quiz_id = $_GET['quiz_id'];

$q = $conn->query("SELECT * FROM questions WHERE quiz_id=$quiz_id");
?>

<!DOCTYPE html>
<html>
<head>
<title>Take Quiz</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Quiz</h2>

<form action="submit_quiz.php" method="post">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

    <?php while($row = $q->fetch_assoc()): ?>
        <p><strong><?php echo $row['question_text']; ?></strong></p>

        <?php if($row['question_type'] == 'mcq'): ?>
            <label><input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo $row['option_a']; ?>"> <?php echo $row['option_a']; ?></label><br>
            <label><input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo $row['option_b']; ?>"> <?php echo $row['option_b']; ?></label><br>
            <label><input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo $row['option_c']; ?>"> <?php echo $row['option_c']; ?></label><br>
            <label><input type="radio" name="q<?php echo $row['id']; ?>" value="<?php echo $row['option_d']; ?>"> <?php echo $row['option_d']; ?></label><br>
        <?php endif; ?>

        <?php if($row['question_type'] == 'truefalse'): ?>
            <label><input type="radio" name="q<?php echo $row['id']; ?>" value="True"> True</label><br>
            <label><input type="radio" name="q<?php echo $row['id']; ?>" value="False"> False</label><br>
        <?php endif; ?>

        <?php if($row['question_type'] == 'short'): ?>
            <input type="text" name="q<?php echo $row['id']; ?>" placeholder="Your answer...">
        <?php endif; ?>

        <hr>
    <?php endwhile; ?>

    <button type="submit">Submit Quiz</button>
</form>

</body>
</html>
