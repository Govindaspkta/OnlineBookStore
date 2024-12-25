<?php
session_start();
session_destroy();
header("Location: homepage.php"); // Redirect to homepage or login page
exit();
?>
