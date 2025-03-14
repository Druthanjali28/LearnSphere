<?php
$host = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "";     // Replace with your MySQL password
$dbname = "discussionDB";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['username'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO discussions (username, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user_name, $subject, $message);

    if ($stmt->execute()) {
        echo "Discussion submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit;
}

// Fetch all discussions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM discussions ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $discussions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $discussions[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($discussions);
    exit;
}

$conn->close();
?>
