<?php
session_start();

// defininig username and password
$valid_username = 'admin@bookcafe.com';
$valid_password = 'Password..@11';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST')
 {
    // Retrieve the entered username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the entered username and password match the predefined ones
    if ($username === $valid_username && $password === $valid_password) {
        // Authentication successful, redirect to the secured page
        $_SESSION['username'] = $username;
        header('');
        exit;
    } 
    else {
        // Authentication failed, display an error message
       /*  $error_message = 'Invalid username or password'; */
       /*  echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
    } */
    $error_message = 'Wrong username or password';
    echo "<script>alert('$error_message');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin Login</title>
    <link rel="stylesheet" href="adminlogin.css">
</head>

<body>

    <div class="container">

        <div class="login">
            <h2>Login To Enter Into Dashboard</h2>
        </div>
        <form name="adminform" action="" method="post">

            <input type="text" name="username" id="username" placeholder="Username" required>

            <input type="password" name="password" id="password" placeholder="password" required>

            <input type="submit" value="Login" id="login" class="submit">

        </form>
    </div>
</body>

</html>