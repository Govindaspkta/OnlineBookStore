<?php
session_start();
include ("db.php");
$errname = $errpass = $errrepass = $erremail = $errnumb = '';
$e=1;
/* $username = $pass = $repass = $email = $numb = ''; */
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    /*  if(isset($_POST['submit'])){ */
    $username = $_POST['name'];
    $email = $_POST['mail'];
    $pass = $_POST['pass'];
    $repass = $_POST['repass'];
    $number = $_POST['numb'];
    $usertype = $_POST['usertype'];
    if (!preg_match("/^[a-zA-Z ]*$/", $username)) {
        $errname = "input must be in string";
    }
 if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*.])[A-Za-z\d!@#$%^&*.]{8,}$/",$pass)){
    $errpass="Password must contain uppercase,lowercase,number and special character";
    $e=0;
} 
 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erremail = "email is not in proper format";
        $e=0;
    }
    if (is_numeric($_POST['numb']) && strlen($_POST['numb'] == 10)) {
        $errnumb = "Number must have digits and not less than 10 ";
        $e=0;
    }
    if (strlen($pass <= 8)) {
        $errrepass = "Password must be atleast 8 characters long";
        $e=0;
    }
    if ($pass != $repass) {
        $errpass = "Password and re-password must be same";
        $e=0;
    }
    $query = "select * from uuser where email='$email'";
    $result = mysqli_query($con, $query);
    if (!$result) {
        echo "<script type='text/javascript'> alert('Error in database query')</script>";
    } else
        if(mysqli_num_rows($result) > 0) {
         /*    echo "<script type='text/javascript'> alert('email is already used')</script>"; */
         echo"Email is in already used";
        }
    
     if($e==1){
        $queryy = "INSERT INTO uuser(usernamel,passwordd,repassword,email,phone,user_type) VALUES('$username','$pass','$repass','$email','$number','$usertype')";
       /*  echo $queryy; */
        mysqli_query($con, $queryy);
       /*  echo "<script type='text/javascript'> alert('Successfully registered')</script>"; */  
       //retriving user_id form newly registered
       $user_id_query = "SELECT user_id FROM uuser WHERE email='$email'";
       $user_id_result = mysqli_query($con, $user_id_query);
       $user_row = mysqli_fetch_assoc($user_id_result);
       $user_id = $user_row['user_id'];
       
       echo"Successfully registered";
       header("Location: products.php");

    }
}



?>
<html>

<head>
    <link rel="stylesheet" href="signup.css">
</head>

<body>
    <div class="container">
        <div class="beamember">
            <h2>Be a Member</h2>
        </div>
        <form name="validate" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="validation()">

            <input type="text" name="name" id="name" placeholder="FullName" required>
            <span class="error">
                <?php echo $errname; ?>
            </span>

            <input type="password" name="pass" id="pass" placeholder="password" required>
            <span class="error">
                <?php echo $errpass; ?>
            </span>

            <input type="password" name="repass" id="repass" placeholder="re-password" required>
            <span class="error">
                <?php echo $errrepass; ?>
            </span>

            <input type="email" name="mail" id="mail" placeholder="Email here" required>
            <span class="error">
                <?php echo $erremail; ?>
            </span>
            <input type="number" name="numb" id="numb" placeholder="Number" required>
            <span class="error">
                <?php echo $errnumb; ?>
            </span>
            <select name="usertype">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>


            <input type="submit" name="submit" value="Register" id="register" class="submit">

        </form>
        <div class="privacypolicy">
            <p>By clicking the Register button, you agree to our<br>
                <a href="">Terms and Condtions</a>and <a href="#">Privacy Policy</a>
            </p>
        </div>
        <div class="login">
            <h4>
                <p>Already have an account?<a href="LoginForm.php">Login</a></p>
            </h4>
        </div>
    </div>


 <script type="text/javascript">
    function validation() {
        var name = document.validate.name.value;
        if (name == null || name == "") {
            alert("Name should not be empty");
            return false;
        }

        var number = document.validate.numb.value;
        var numberexp = /^[0-9]{10}$/;
        if (!number.match(numberexp)) {
            alert("Invalid number type");
            return false;
        }

        var password = document.validate.pass.value;
        var repassword = document.validate.repass.value;
        var passregex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*.])[A-Za-z\d!@#$%^&*.]{8,}$/;
        if (password != repassword) {
            alert("Password must be same");
            return false;
        } 
       if (!password.match(passregex)) {
            alert("Password must contain Uppercase, lowercase, number, and special character");
            return false;
        }
        // return true;
    }
    </script>

</body>

</html>