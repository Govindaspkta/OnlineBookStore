
<html>
<head>
<style>
.error{
color:red;
} 
</style>

</head>
<body>
<?php
$errfname=$errpname= $errEmail=$errgender=$errshift=$errWeb=$errPhone=$errPadd=$errTadd=$errFeed=$errterms=$error=$errfaculty=$errdob=' ';
$fname=$pname=$email=$rgender=$shift=$web=$phone=$pAdd=$tAdd=$feed=$terms=$faculty=$subsel=$dob=$month=$day=$date=' ';

if(isset($_POST['submit']))
{ 
// full name validation
if(empty($_POST['fname']))
{
$errfname="full Name is blank";
}
else
{
if(preg_match("/^[a-zA-Z ]*$/",$_POST['fname']))
{
	echo $fname;
}
else
{
$errfname="input must be in string";
}
}

// parents name validation
if(empty($_POST['pname']))
{
$errpname=" Parents Name is blank";
}
else
{
if(preg_match("/^[a-zA-Z ]*$/",$_POST['pname']))
{
$pname=$_POST['pname'];
}
else
{
$errpname="parents name must be in string";
}
}

// validation of email

if(empty($_POST['email']))
{
$errEmail="Email is empty";
}
else
{
$email=$_POST['email'];
if(!filter_var($email,FILTER_VALIDATE_EMAIL))
{
 $errEmail="email is not in proper format";
}
 else
 {
 echo $email;

}
}
// validation of gender
echo "<br>";
if(empty($_POST['gender']))
{
$errgender="Please select one option";
}
else
{
 $rgender=$_POST['gender'];
}

if(empty($_POST['dob']) && empty($_POST['month']) &&  empty($_POST['day']))
{
$errdob="Please enter date of birth";
}
else
{
 $dob=$_POST['dob'];
 $month=$_POST['month'];
 $day=$_POST['day'];

$date=$dob."-".$month."-".$day;
}
if(empty($_POST['faculty']))
{
 $errfaculty="please select any one faculty";
 }
 else
 {
$faculty=$_POST['faculty'];
foreach($faculty as $value)
{
//$subsel.=$value.",";
	echo $value;
}
}
// validation for shift
if(empty($_POST['shift']))
{
$errshift=" Please select one option";
}
else
{
 $shift=$_POST['shift'];
}

// validation of website
if(empty($_POST['web']))
{
$errWeb="web is blank";
}
else
{
$web=$_POST['web'];
if(!filter_var($web,FILTER_VALIDATE_URL))
{
 $errWeb="web is not in proper format";
}
else
{
echo $web;
}
}

// phone validation
if(empty($_POST['phn']))
{
$errPhone="phone is blank";
}
else
{
if(is_numeric($_POST['phn']) && strlen($_POST['phn']!=10))
{
$phone=$_POST['phn'];
}
else
{
echo "<br> enter valid 10 digit number";

}
}

// permanetnt address validation
if(empty($_POST['pAdd']))
{
$errPadd=" permanent address is blank";
}
else
{
if(preg_match("/^[a-zA-Z ]*$/",$_POST['pAdd']))
{

 $pAdd=$_POST['pAdd'];
}
else
{
$errPadd=" permanent address must be in string";
}
}

if(empty($_POST['tAdd']))
{
$errTadd=" temporary address is blank";
}
else
{
if(preg_match("/^[a-zA-Z ]*$/",$_POST['tAdd']))
{
 $tAdd=$_POST['tAdd'];
}
else
{
$errTadd=" temporary address must be in string";
}
}

// validation of feedback
if(empty($_POST['feed']))
{
$errFeed=" Feed back is blank";
}
else
{
if(preg_match("/^[a-zA-Z ]*$/",$_POST['fname']))
{
 $feed=$_POST['feed'];
}
else
{
$errFeed=" Feed back must be in string";
}
}

 if(isset($_POST['terms']))
   {
 $faculty=$_POST['terms'];

 }
   else
  {
   $errterms="please agree to our terms and condition";
 }


}

?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<fieldset style ="width:40%">
<legend>Users information</legend>
<table border="1"><tr><td>
Full Name:</td><td><input type="text" name="fname" value="Ram Shrestha" />
<span class="error"> <?php echo $errfname; ?></span></td>
<br>
<tr><td>
Parents Name:</td><td><input type="text" name="pname" placeholder="Hari Shrestha" />
<span class="error"> <?php echo $errpname; ?></span></td></tr>
<tr><td>
Email:</td><td><input type="text" name="email"/>
<span class="error"> <?php echo $errEmail; ?></span></td></tr>
<tr>
<td>Gender</td><td><input type="radio" name="gender" value="male" />Male<br>
		<input type="radio" name="gender" value="female" />Female<br>
		<input type="radio" name="gender" value="others" />others
		<span class="error"> <?php echo $errgender; ?></span></td></tr>
<tr><td>
Date of Birth</td><td>
	<select name="dob">
		<option>year</option>
					<?php
		for($i=1992;$i<=2020;$i++)
					{
			echo "<option value=$i>$i</option>";
					}
					?> 
			</select>
			<select name="month">
			<option> month </option>
	<?php
	for($i=1;$i<=12;$i++)
					{
	echo "<option value=$i>$i</option>";
	
			}
			?>
					
			</select>
			<select name="day">
			<option>day</option>
		
					<?php
					for($i=1;$i<=31;$i++)
					{
			echo "<option value=$i>$i</option>";
			
					}
					?>
					</select>
			
	<span class="error"> <?php echo $errdob; ?></span>
		</td></tr>
<tr>
<td>Faculty</td>
<td><input type="checkbox" name="faculty[]" value="bim"/>BIM<br>
	<input type="checkbox" name="faculty[]" value="bsw"/>BSW<br>
<input type="checkbox" name="faculty[]"  value="bba"/>BBA<br>
<input type="checkbox" name="faculty[]"  value="csit"/>CSIT<br>
<input type="checkbox" name="faculty[]"  value="bbm"/>BBM<br>
<input type="checkbox" name="faculty[]"  value="bbs"/>BBS<br>
<span class="error"> <?php echo $errfaculty; ?></span></select>
</td>
</tr>
<td>Shift</td><td><input type="radio" name="shift" value="morning" />Morning<br>
		<input type="radio" name="shift" value="day" />Day<span class="error"> <?php echo $errshift; ?></span></td></tr>

<tr><td>Website:</td><td>
	<input type="text" name="web"/>
<span class="error"> <?php echo $errWeb; ?></span></td></tr>
<br>
<tr><td>
Phone Number:</td><td><input type="text" name="phn"/>
<span class="error"> <?php echo $errPhone; ?></span></td></tr>
<tr><td>
Permanent Address:</td><td><input type="text" name="pAdd"/>
<span class="error"> <?php echo $errPadd; ?></span></td></tr>
<tr><td>
Temporary Address:</td><td><input type="text" name="tAdd" maxlength="50"/>
<span class="error"> <?php echo $errTadd; ?></span></td></tr>

<tr><td>Feedback:</td>
<td><textarea rows="5" cols="12" name="feed"></textarea>
<span class="error"> <?php echo $errFeed; ?></span></td>
</tr>
<tr><td colspan="2">
<input type="checkbox" name="terms"  value="condition"/>I accept all the terms and conditions
<span class="error"> <?php echo $errterms; ?></td>
</tr>

<tr><td colspan="2"><input type="submit" name="submit" value="send" />
	            <input type="reset" name="reset" value="reset" />
				</table>
				</form>
<?php
//$fname=$_POST['fname'];
//$pname=$_POST['pname'];
//$email=$_POST['email'];
//$web=$_POST['web'];
//$phone=$_POST['phn'];
//$pAdd=$_POST['pAdd'];
//$tAdd=$_POST['tAdd'];

echo "<table border='1'>";
echo "<tr><td>Full name</td>";
echo "<td>$fname";
echo "</td></tr>";
echo"<tr><td>Parents name</td>";
echo "<td>$pname</td></tr>";

echo "<tr><td>Email</td>";
echo "<td>$email</td></tr>";

echo "<tr><td>Gender</td>";
echo "<td>$rgender</td></tr>";

echo "<tr><td>faculty</td>";
echo "<td>$subsel</td></tr>";

echo "<tr><td>Shift</td>";
echo "<td>$shift</td></tr>";


echo "<tr><td>website</td>";
echo "<td>$web </td></tr>";

echo "<tr><td>phone</td>";
echo "<td>$phone</td></tr>";

echo "<tr><td>permanent address</td>";
echo "<td>$pAdd</td></tr>";

echo "<tr><td>temporary address</td>";
echo "<td>$tAdd</td></tr>";

echo "<tr><td>feedback</td>";
echo "<td>$feed</td></tr>";

echo "<tr><td>date of birth</td>";
echo "<td>$date</td></tr>";


echo "</table>";

?>
</body>
</html>



