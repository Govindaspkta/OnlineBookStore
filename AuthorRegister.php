<html>

<head>
    <link rel="stylesheet" href="signup.css">
</head>

<body>
    <div class="container">
        <div class="beamember">
            <h2>Be a Member</h2>
        </div>
        <form name="validate" method="post" onsubmit="validation()">

            <input type="text" name="name" id="name" placeholder="Username" required>
            <span class="error">
            <!--     <?php echo $errors; ?> -->
            </span>

            <input type="password" name="pass" id="pass" placeholder="password" required>
            <span class="error">
                <!--   <?php echo $errpass; ?> -->

                <input type="password" name="repass" id="repass" placeholder="re-password" required>
                <span class="error">
                    <!--                         <?php echo $errrepass; ?>
 -->
                    <input type="email" name="mail" id="mail" placeholder="Email here" required>
                    <span class="error">
                        <!--  <?php echo $erremail; ?> -->

                        <input type="text" name="numb" id="numb" placeholder="Number" required>
                        <span class="error">
                            <!--  <?php echo $errnumb; ?> -->

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
    </div>

    <script type="text/javascript">
        function validation() {
            var name = document.validate.name.value;
            // var validname=/^[aA-zZ]+$/
            if (name == null || name == "") {
                alert("Name shouldnot be empty");
            } else {
                return true;
            }
            var number = document.validate.numb.value;
            var numberexp = /^[0-9]{10}$/;
            if (!number.match(numberexp)) {
                alert("Invalid number type");
                else if (numb == 0) {
                    alert("number cannot be empty");
                }

            }
            else {
                return true;
            }
            var password = document.validate.pass.value;
            var repassword = document.validate.repass.value;
            var passregex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
            if (!password.match(repassword)) {

                alert("Password must be same");
            
            else if (!password.match(passregex)) {
                    alert("Password must contain Uppercase,lowerCase,number and special character");

                }
            }
            else {
                return true;
            }
        }

    </script>
</body>

</html>
<?php

session_start();
include("db.php");
$errname = $errpass = $errrepass = $erremail = $errnumb = '';

$username = $pass = $repass = $email = $numb = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    /*  if(isset($_POST['submit'])){ */
    $username = $_POST['name'];
    $email = $_POST['mail'];
    $pass = $_POST['pass'];
    $repass = $_POST['repass'];
    $number = $_POST['numb'];
    $errors = array();
    /*  $formaterrors=array(); */
    if (empty($username) or empty($email) or empty($pass) or empty($repass) or empty($numb)) {
        array_push($errors, 'All fields are required');
    }
    if (!preg_match("/^[a-zA-Z ]*$/", $_POST['name'])) {
        array_push($errors, 'Usernammust be in string');
    } else {
        echo $errfname = "input must be in string";
    }




    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "email is not in proper format");

    }
    if (is_numeric($_POST['numb']) && strlen($_POST['numb'] == 10)) {

        array_push($errors, "Number must have digits and not less than 10 ");
    }

    if (strlen($pass <= 5)) {
        array_push($errors, "Password must be atleast 10 characters long");
    }


    if ($pass != $repass) {
        array_push($errors, "Password and re password must be same");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {

             echo "<script type='text/javascript'> alert('Please enter valid info')</script>";

       

        }
    } else {

        $query = "insert into author (name,password,repassword,email,phone) values('$username','$pass','$repass','$email','$number')";
        mysqli_query($con, $query);
        echo "<script type='text/javascript'> alert('successfully registered')</script>";
    }
}/* else {
     echo "<script type='text/javascript'> alert('Please enter valid info')</script>";

 } */



?>

