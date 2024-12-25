<?php
$con=mysqli_connect("localhost","root","Mysql..@11","project4thsem");
if(!$con){
    header("location:./errors/db.php");
    die();
}
?>