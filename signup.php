<?php

$servername = "localhost:3306";
$username = "root";
$password = "";
$db = "furnishus";
$tbl = "siteUsers";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = "";
$address = "";
$city = "";
$state = "";
$zip = "";
$email = "";
$pw = "";
$pw2 = "";
$msg = "";

if(isset($_POST['name']))
{
	// validate all input
	$name = mysql_real_escape_string($_POST['name']);
	$email = mysql_real_escape_string($_POST['email']);
	$address = mysql_real_escape_string($_POST['address']);
	$city = mysql_real_escape_string($_POST['city']);
	$state = mysql_real_escape_string($_POST['state']);
	$zip = mysql_real_escape_string($_POST['zipcode']);
	$pw = mysql_real_escape_string($_POST['password']);
	$pw2 = mysql_real_escape_string($_POST['password2']);

	if( !$name || !$email || !$address || !$city || !$state || !$zip || !$pw || !$pw2)
	{
		$msg .= "Please enter values for all fields. ";
	}
	
}
if(strlen($pw) < 8)
{
	$msg .= "Your password must be at least 8 characters long. ";
}
if($pw != $pw2)
{
	$msg .= "Your passwords do not match. ";
}
// check for:
// numbers/special characters in name, city, state  not match ~[0-9]~
// non-numbers in zip  DO match [0-9]*
// email format  .@.\..
if(preg_match('~[0-9]~', $name) == 1)
{
	$msg .= "There should not be any numbers in your name. ";
}
if(preg_match('~[0-9]~', $city) == 1)
{
	$msg .= "There should not be any numbers in your city name. ";
}
if(preg_match('~[0-9]~', $state) == 1)
{
	$msg .= "There should not be any numbers in your state name. ";
}
if(preg_match('/[0-9][0-9]*/', $zip) == 0)
{
	$msg .= "Please enter a numeric zip code. ";
}
if(strlen($zip) != 5)
{
	$msg .= "Please enter a 5-digit zip code. ";
} 
if(preg_match('/.*@.*\..*/', $email) == 0)
{
	$msg .= "Please enter a valid email address. ";
}
$emailquery = "SELECT 1 FROM `siteUsers` WHERE `Email` = '" . $email . "' LIMIT 1";
	$result = $conn->query($emailquery);
	$numrows = $result->num_rows;
	//if( mysql_num_rows($result) == 1)
	if($numrows == 1)
	{
		$msg .= "Email is already registered. ";
	}
if(strlen($msg) == 0)
{
	
	// send mysql query
	//$sql = "INSERT INTO " . $tbl . "(name, email, address, city, state, zipcode, password) VALUES " ."(" . $name .", ". $email .", ". $address .", ". $city .", ". $state .", ". $zip .", ". $pw . ")";
	$sql = "INSERT INTO `siteUsers`(`Name`, `Email`, `Address`, `City`, `State`, `Zipcode`, `Password`) VALUES " ."('" . $name ."', '". $email ."', '". $address ."', '". $city ."', '". $state ."', '". $zip ."', '". $pw . "')";

	//echo($sql);

	//$sql = "INSERT INTO " . $tbl . "(" . $name .", ". $email .", ". $address .", ". $city .", ". $state .", ". $zip .", ". $pw . ")";

	if($conn->query($sql) === TRUE)
	{
		// worked
		//echo("user added");
		$msg .= "You have successfully singed up. Welcome to Furnish Us!";
	}
	else
	{
		// did not work 
		//echo("failed");
		$msg = "Sorry, your sing up attempt failed. \n" . $msg;
	}
}
$conn->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <title>Sign Up - Furnish Us</title>
  <meta name="description" content="free website template" />
  <meta name="keywords" content="enter your keywords here" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=9" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/image_slide.js"></script>
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="welcome">
	    <h1><span>Furnish Us</span></h1>
	  </div><!--end welcome-->
      <div id="menubar">
        <ul id="menu">
          <li><a href="index.php">Home</a></li>
          <li><a></a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a></a></li>
          <li class="current"><a href="singup.php">Sign Up</a></li>
        </ul>
      </div><!--end menubar-->
    </div><!--end header-->
    
	<div id="site_content">  
	
      <div id="text_content">
	  <p>Please enter the following information to register with Furnish Us. Make sure that your password is at least 8 characters for security purposes.</p>

      <div class="container">
		<form action="singup.php" method="post">
          Name: <input type="text" name="name"><br />
          Email: <input type="text" name="email"><br />
          Address: <input type="text" name="address"><br />
          City: <input type="text" name="city"><br />
          State: <input type="text" name="state"><br />
          Zip code: <input type="text" name="zipcode"><br />
          Password: <input type="password" name="password"><br />
          Re-enter password: <input type="password" name="password2"><br />
		  </div>
		<input type="submit" value="Sign Up" name="button"><br>
		</form>
		
	  
		<?php
		if(isset($_POST['button']))
			echo $msg;
		?>
	  
	  </div><!--close text_content-->	
	
      <ul class="slideshow">
        <li class="show"><img width="500" height="450" src="images/home_1.jpg" alt="Furnish Us provides quality used furniture" /></li>
        <li><img width="500" height="450" src="images/home_2.jpg" alt="Furnish Us provides quality used furniture" /></li>
      </ul>   
    
	</div><!--end site_content--> 


  </div><!--end main-->  


  <div id="footer">
    <div id="footer_container">
    <div class="footer_container_box">
    <p>"Furnish your living space and focus on living"</p>
    </div><!--close footer_container--> 
  </div><!--close footer-->  
  </body>
</html>
