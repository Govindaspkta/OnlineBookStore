<?php
session_start();
include("db.php");
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $mail = $_POST['email'];
    $pass = $_POST['pass'];
    // Validate email and password inputs
    if (!empty($mail) && !empty($pass)) {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM uuser WHERE email=? AND passwordd=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $mail, $pass);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        // Check if there's a matching user
        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            // Check if the user is an admin
            if ($data['user_type'] == 'admin') {
                // Set session variables for admin
                $_SESSION['admin_id'] = $data['user_id'];
                $_SESSION['admin_name'] = $data['usernamel'];
                $_SESSION['admin_email'] = $data['email'];
                $_SESSION['admin_logged_in'] = true;
                header("Location: admin/registeredBook.php");
                exit();
            } elseif ($data['user_type'] == 'user') {
                // Set session variables for regular user
                $_SESSION['user_id'] = $data['user_id'];
                $_SESSION['user_name'] = $data['usernamel'];
                $_SESSION['user_email'] = $data['email'];
                header("Location: products.php");
                exit();
            }
        } else {
            echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Please provide email and password')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-size: cover;
            background-color: aqua;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: antiquewhite;
            padding: 30px;
            width: 350px;
            box-sizing: border-box;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        .submit {
            background-color: rgb(0, 0, 0);
            font-size: medium;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            cursor: pointer;
            box-sizing: border-box;
        }

        .login {
            margin-bottom: 20px;
        }

        a {
            color: blue;
            text-decoration: none;
            padding: 10px;
        }

        .reg {
            margin-top: 20px;
        }

        .social-login {
            margin-top: 20px;
        }

        .social-icons {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }

        .social-icons a {
            color: #fff;
            font-size: 24px;
            text-decoration: none;
            padding: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #3b5998; /* Facebook blue */
        }

        .social-icons a:hover {
            opacity: 0.8;
        }

        .social-icons .google {
            background-color: #dd4b39; /* Google red */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login">
        <h2>Login</h2>
    </div>

    <!-- Login Form -->
    <form name="validate" action="LoginForm.php" method="post" onsubmit="return validation();">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="pass" id="pass" placeholder="Password" required>
        <input type="submit" value="Login" id="login" class="submit">
    </form>
    <!-- <div id="or">-------------------------Or-------------------------</div> -->

    <!-- Social Login Icons -->
    <!-- <div class="social-login">
        <h3>Login with</h3>
        
        <div class="social-icons">
            <a href="https://www.facebook.com/login.php" class="facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://accounts.google.com/login" class="google"><i class="fab fa-google"></i></a>
        </div>
    </div> -->

    <div class="reg">
        <h4>Don't have an account? <a href="signupform.php">Register</a></h4>
    </div>
</div>

<!-- JavaScript for form validation -->
<script type="text/javascript">
    function validation() {
        var email = document.getElementById('email').value;
        var pass = document.getElementById('pass').value;

        if (email.trim() == '' || pass.trim() == '') {
            alert('Please provide email and password');
            return false;
        }

        return true;
    }
</script>

</body>
</html>