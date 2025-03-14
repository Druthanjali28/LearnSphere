<?php
include 'db-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Query to check if email exists
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Fetch the user details
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Start session
                session_start();
                $_SESSION['user_id'] = $id; // Save user ID in session
                $_SESSION['email'] = $email; // Save email in session

                // Redirect to the home page
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

// Additional functionality starts here (unchanged logic above remains intact)

// Check if the user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect to the profile page if already logged in
    header("Location: profile.php");
    exit;
}

// Additional functionality ends here

$conn->close();
?>
