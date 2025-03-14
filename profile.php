<?php
session_start();
include 'db-config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$sql = "SELECT full_name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($full_name, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

    <div class="profile-container">
        <h2>Welcome, <?php echo htmlspecialchars($full_name); ?>!</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <a href="logout.php"><button class="btn">Logout</button></a>
    </div>

</body>
</html>
