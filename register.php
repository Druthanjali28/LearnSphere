<?php
include 'db-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields are set
    if (!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if passwords match
        if ($password === $confirm_password) {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Passwords do not match!";
        }
    } else {
        echo "All fields are required!";
    }

    $conn->close();
}
?>
