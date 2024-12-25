<html>
    <head>

        <title>
</title>
</head>
            <body>
                <form method="post">
                    <input type="number" name="principle" placeholder="Principle">
                    <input type="number" name="rate" placeholder="Rate">
                    <input type="number" name="time" placeholder="Time">
                    <button type="submit" name="submit">SUbmit</button>



</form>

</body>
</html>


<?php

if(isset($_POST["submit"])) {
  $p=  $_POST['principle'];
  $t=  $_POST['rate'];
  $r=  $_POST['time'];
  if($p>0 && $t> 0 && $r> 0) {

    if(is_numeric($p) && is_numeric($t) && is_numeric($r)) {
    $si=$p*$t*$r/100;
    echo"SImple interest is:".$si;
  
}
  }
}
?>