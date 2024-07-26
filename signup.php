<?php 
require 'config/constants.php';

// Get back form data if there was a registration error
$firstname = $_SESSION['signup-data']['firstname'] ?? '';
$lastname = $_SESSION['signup-data']['lastname'] ?? '';
$username = $_SESSION['signup-data']['username'] ?? '';
$email = $_SESSION['signup-data']['email'] ?? '';
$createpassword = $_SESSION['signup-data']['createpassword'] ?? '';
$confirmpassword = $_SESSION['signup-data']['confirmpassword'] ?? '';

// Delete the session
unset($_SESSION['signup-data']);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogvista</title>
    <!--- Custom stylesheet -->
    <link rel="stylesheet" href="./css/style.css">
    <!--- Iconscount CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!--- Google font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
<section class="form__section">
    <div class="container form__section-container">
        <h2>Sign Up</h2>
        <?php 
            if (isset($_SESSION['signup'])): ?>
            <div class="alert__message error">
                <p>
                    <?php
                    echo $_SESSION['signup'];
                    unset($_SESSION['signup']);
                    ?>
                </p>
            </div>
        <?php endif ?>    
        <form action="<?php echo ROOT__URL; ?>signup-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" placeholder="First name">
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" placeholder="Last name">
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="User name">
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email">
            <input type="password" name="createpassword" value="<?php echo htmlspecialchars($createpassword); ?>" placeholder="Create Password">
            <input type="password" name="confirmpassword" value="<?php echo htmlspecialchars($confirmpassword); ?>" placeholder="Confirm Password">
            <div class="form__control">
                <label for="avatar">User Avatar</label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit" name="submit" class="btn">Sign Up</button>
            <small>Already have an account? <a href="signin.php">Sign In</a></small>
        </form>
    </div>
</section>
</body>
</html>
