<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Insert message into database
    $sql = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        header('Location: contact.php?status=success');
    } else {
        header('Location: contact.php?status=error');
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: contact.php');
}
?>