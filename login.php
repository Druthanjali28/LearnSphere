<?php
session_start(); // Start the session at the beginning

include 'db-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format!");
        }

        // Query to check if the email exists
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in SQL query: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Fetch user details
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Store user details in session
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;

                // Redirect to home page
                header("Location: index.html");
                exit;
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "No account found with this email.";
        }

        $stmt->close();
    } else {
        echo "Please fill in both email and password!";
    }
}

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

$conn->close();
?>
