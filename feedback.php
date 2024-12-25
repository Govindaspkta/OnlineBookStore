<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login page or handle unauthorized access
        header('Location: login.php');
        exit();
    }

    // Validate and sanitize feedback input
    $feedback = $_POST['feedback']; // Assuming 'feedback' is the name of the textarea

    // Example: Save feedback to database
    include("admin/config/adminDbConn.php"); // Adjust path as necessary

    $user_id = $_SESSION['user_id'];

    $insert_query = $con->prepare("INSERT INTO feedback (user_id, feedback_text) VALUES (?, ?)");
    $insert_query->bind_param("is", $user_id, $feedback);
    
    if ($insert_query->execute()) {
        // Feedback submitted successfully
        $_SESSION['feedback_success'] = "Thank you for your feedback!";
    } else {
        // Error handling if insertion fails
        $_SESSION['feedback_error'] = "Error submitting feedback. Please try again.";
    }

    $insert_query->close();
    $con->close();

    // Redirect back to feedback page or any other appropriate page
    header('Location: feedback.php');
    exit();
} else {
    // Handle invalid request method (GET or other)
    header('Location: feedback.php'); // Redirect back to feedback page
    exit();
}
?>
