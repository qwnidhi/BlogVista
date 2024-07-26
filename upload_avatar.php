<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id']; // Assuming user_id is sent with the form
    $upload_dir = 'images/';
    $upload_file = $upload_dir . basename($_FILES['avatar']['name']);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file)) {
        // Update user avatar path in the database
        $avatar_path = basename($_FILES['avatar']['name']);
        $update_query = "UPDATE users SET avatar = '$avatar_path' WHERE id = $user_id";
        $result = mysqli_query($conn, $update_query);

        if (!$result) {
            die("Error updating avatar: " . mysqli_error($conn));
        }

        echo "Avatar updated successfully.";
    } else {
        echo "Failed to upload avatar.";
    }
}
?>
