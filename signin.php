<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config/constants.php';

$username_email = $_SESSION['signin-data']['username_or_email'] ?? '';
$password = $_SESSION['signin-data']['password'] ?? '';

unset($_SESSION['signin-data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogvista</title>
    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- Iconscout CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!-- Google font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
<section class="form__section">
    <div class="container form__section-container">
        <h2>Sign In</h2>
        <?php if(isset($_SESSION['signup-success'])): ?>
            <div class="alert__message success">
                <p>
                    <?php 
                    echo $_SESSION['signup-success'];
                    unset($_SESSION['signup-success']);
                    ?>
                </p>
            </div>
        <?php elseif(isset($_SESSION['signin'])): ?>
            <div class="alert__message error">
                <p>
                    <?php 
                    echo $_SESSION['signin'];
                    unset($_SESSION['signin']);
                    ?>
                </p>
            </div>
        <?php endif ?>
        <form action="<?= ROOT__URL ?>signin-logic.php" method="POST">
        <input type="text" name="username_or_email" value="<?= htmlspecialchars($username_email, ENT_QUOTES, 'UTF-8') ?>" placeholder="Username or Email" required>
            <input type="password" name="password" value="<?= htmlspecialchars($password, ENT_QUOTES, 'UTF-8') ?>" placeholder="Password" required>
            <button type="submit" name="submit" class="btn">Sign In</button>
            <small>Don't have an account? <a href="signup.php">Sign Up</a></small>
        </form>
    </div>
</section>
</body>
</html>
