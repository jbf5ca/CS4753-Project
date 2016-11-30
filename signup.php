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
$cardnum = $expdate = $cvv = "";
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
	$cardnum = mysql_real_escape_string($_POST['cardnum']);
	$expdate = mysql_real_escape_string($_POST['expdate']);
	$cvv = mysql_real_escape_string($_POST['cvv']);
	
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
	$sql = "INSERT INTO `siteUsers`(`Name`, `Email`, `Address`, `City`, `State`, `Zipcode`, `Password`, `Cardnum`, `Expdate`, `CVV`) VALUES " ."('" . $name ."', '". $email ."', '". $address ."', '". $city ."', '". $state ."', '". $zip ."', '". $pw . "', '" . $cardnum . "', '" . $expdate . "', '" . $cvv . "')";

	//echo($sql);

	//$sql = "INSERT INTO " . $tbl . "(" . $name .", ". $email .", ". $address .", ". $city .", ". $state .", ". $zip .", ". $pw . ")";

	if($conn->query($sql) === TRUE)
	{
		// worked
		//echo("user added");
		$msg .= "You have successfully singed up. Welcome to Furnish Us!";
		
		// MAILING STUFF //
		//date_default_timezone_set('Etc/EST');
		require 'PHPMailerAutoload.php';
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = "furnishusmailing@gmail.com";
		$mail->Password = "furnishus1";
		$mail->setFrom('furnishusmailing@gmail');
		$mail->addAddress($email, $name);
		$mail->Subject = "Welcome to Furnish Us!";
		$mail->Body = 'Hello ' . $name . ', 
		Thank you or joining us at Furnish Us. We hope that you have a pleasant experience as you solve all of your used furniture buying and selling needs. 

		The Furnish Us Team';
		if(!$mail->send()) {
			//echo 'Message could not be sent.';
			//echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'Message has been sent';
		}
	}
	else
	{
		// did not work 
		//echo("failed");
		$msg = "Sorry, your sign up attempt failed. \n" . $msg;
	}
}
$conn->close();

/* // MAILING STUFF //
date_default_timezone_set('Etc/EST');
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "furnishusmailing@gmail.com";
$mail->Password = "furnishus1";
$mail->setFrom('furnishusmailing@gmail');
$mail->addAddress($email, $name);
$mail->Subject = "Welcome to Furnish Us!";
$mail->Body = 'Hello ' . $name . ', 
Thank you or joining us at Furnish Us. We hope that you have a pleasant experience as you solve all of your used furniture buying and selling needs. 

The Furnish Us Team';
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
} */
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
          <li class="current"><a href="signup.php">Sign Up</a></li>
        </ul>
      </div><!--end menubar-->
    </div><!--end header-->
    
	<div id="site_content">  
	
      <div id="text_content">
	  <p>Please enter the following information to register with Furnish Us. Make sure that your password is at least 8 characters for security purposes.</p>
	  <p>Personal Information: </p>
      <div class="container">
		<form action="signup.php" method="post">
          Name: <input type="text" name="name" pattern="^[a-zA-Z][a-zA-Z ]+$" required><br />
          Email: <input type="text" name="email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" required title="A valid email address"><br />
          Address: <input type="text" name="address" required><br />
          City: <input type="text" name="city" required><br />
          State: <input type="text" name="state" required><br />
          Zip code: <input type="text" name="zipcode" pattern="^[0-9]{5}$" title="A 5-digit zip code" required><br />
          Password: <input type="password" name="password" required><br />
          Re-enter password: <input type="password" name="password2" required><br />
		  <br><p>Banking Information: </p>
		  Credit Card Number (no dashes/spaces): <input type="text" name="cardnum" pattern="^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$" title="Your credit card number with digits only, no slashes or spaces."> <br />
		  Expiration Date (MM/YY): <input type="text" name="expdate" pattern="[0-9][0-9][/][0-9][0-9]" title="The expiration date in MM/YY format e.g. 03/18 for March 2018"><br>
		  CVV: <input type="text" name="cvv" pattern="^[0-9]{3,4}$" title="3 or 4 digit CVV, usually found on the back of your card"><br>
		  </div>
		  
		<input type="submit" value="Sign Up" name="button"><br>
		</form>
		
		<p>Subscribe to Furnish Us Elite for $4.99/mo for access to discounts and advanced features<p>
		<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="MW9WZYXH4QKJG">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
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
