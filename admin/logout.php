<?php
include('config/adminDbConn.php');
session_start();  
session_unset();
session_destroy();
header("Location:../LoginForm.php");
exit;


?>
