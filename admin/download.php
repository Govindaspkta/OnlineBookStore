<?php
session_start();

ini_set('log_errors', 1);
ini_set('error_log', 'path_to_error_log.txt');

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('admin/config/adminDbConn.php');

// Check if user is logged in and order_id is passed
if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: loginForm.php");
    exit();
}

// Get the order_id from the URL
$order_id = $_GET['order_id'];

// Query to get the order details
$order_query = mysqli_query($con, "SELECT * FROM `orderr` WHERE id = '$order_id'");

if (!$order_query) {
    die("Database query failed: " . mysqli_error($con));
}

if (mysqli_num_rows($order_query) > 0) {
    $order = mysqli_fetch_assoc($order_query);

    // Decrypt the files field to get individual file paths
    $encrypted_files = $order['files'];
    $decrypted_files = openssl_decrypt($encrypted_files, 'aes-256-cbc', 'your_secret_key', 0, 'your_iv');

    if ($decrypted_files === false) {
        die("Decryption failed");
    }

    // Split decrypted files string into array of file paths
    $file_paths = explode(', ', $decrypted_files);

    // Serve the first file for download if it exists
    if (!empty($file_paths)) {
        // Adjust path as per your file storage structure
        $book_file_path = 'uploads/pdfs/' . $file_paths[0]; // Assuming you want to serve the first file in the list

        // Check if the file exists
        if (file_exists($book_file_path)) {
            // Insert or update download count in database
            $user_id = $_SESSION['user_id'];
            $book_id = pathinfo($book_file_path, PATHINFO_FILENAME); // Extract book_id from file path, adjust as per your naming convention

            // Query to insert or update download count in downloads table
            $query = "INSERT INTO downloads (user_id, book_id, download_count)
                      VALUES (?, ?, 1)
                      ON DUPLICATE KEY UPDATE download_count = download_count + 1";
            
            $stmt = $con->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: (" . $con->errno . ") " . $con->error);
                die("Prepare failed: (" . $con->errno . ") " . $con->error);
            }

            $stmt->bind_param("ii", $user_id, $book_id);
            if (!$stmt->execute()) {
                error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            $stmt->close();

            // Set headers to force download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($book_file_path) . '"');
            header('Content-Length: ' . filesize($book_file_path));

            // Output the file
            readfile($book_file_path);

            // Exit to prevent additional output
            exit();
        } else {
            echo "File not found: " . $book_file_path . "<br>";
        }
    } else {
        echo "No files found for download.";
    }
} else {
    echo "No order found for the user.";
}
?>
