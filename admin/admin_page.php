<?php

include("dbconn.php");
include("LoginForm.php");
session_start();
$admin_id=$_session['admin_id'];
if(!isset($admin_id)){
header("location:loginform.php");
}
?>
