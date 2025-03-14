<?php
// Database connection
$host = "localhost";        // Server hostname
$username = "root";         // MySQL username (default for XAMPP)
$password = "";             // MySQL password (default is blank for XAMPP)
$dbname = "discussionDB";   // Name of your database

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (Insert discussion into database)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['username'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO discussions (username, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user_name, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Discussion submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting discussion: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch all discussions (Retrieve from database)
$discussions = [];
$sql = "SELECT * FROM discussions ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $discussions[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discussion Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    .form-container {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    h2, h3 {
      color: #333;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }
    input[type="text"], textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }
    button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 15px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
    }
    button:hover {
      background-color: #0056b3;
    }
    .discussion-list {
      margin-top: 30px;
      padding: 10px;
      border-top: 1px solid #ddd;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      margin-bottom: 15px;
      padding: 10px;
      background: #f4f4f4;
      border-radius: 4px;
    }
    small {
      display: block;
      margin-top: 10px;
      font-size: 12px;
      color: #888;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Start a Discussion</h2>
    <form method="POST" action="">
      <label for="username">Your Name:</label>
      <input type="text" id="username" name="username" placeholder="Enter your name" required>

      <label for="subject">Discussion Title:</label>
      <input type="text" id="subject" name="subject" placeholder="Enter discussion title" required>

      <label for="message">Your Message:</label>
      <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>

      <button type="submit">Submit</button>
    </form>

    <div class="discussion-list">
      <h3>Previous Discussions</h3>
      <ul>
        <?php foreach ($discussions as $discussion): ?>
          <li>
            <strong><?php echo htmlspecialchars($discussion['subject']); ?></strong>
            by <?php echo htmlspecialchars($discussion['username']); ?>
            <p><?php echo nl2br(htmlspecialchars($discussion['message'])); ?></p>
            <small>Posted on: <?php echo $discussion['created_at']; ?></small>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</body>
</html>
