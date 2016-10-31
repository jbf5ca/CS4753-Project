<?php

$servername = "localhost:3305";
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
else if(strlen($pw) < 8)
{
	$msg .= "Your password must be at least 8 characters long. ";
}
else if($pw != $pw2)
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
if(preg_match('[0-9]*', $zip) == 0)
{
	$msg .= "There should be only numbers in your zip code. ";
}
if(strlen($zip) != 5)
{
	$msg .= "Please enter a 5-digit zip code. ";
}
if(preg_match('.@.\..', $email) == 0)
{
	$msg .= "Please enter a valid email address. ";
}

if(strlen($msg) == 0)
{
	// send mysql query
	//$sql = "INSERT INTO " . $tbl . "(name, email, address, city, state, zipcode, password) VALUES " ."(" . $name .", ". $email .", ". $address .", ". $city .", ". $state .", ". $zip .", ". $pw . ")";
	$sql = "INSERT INTO `siteUsers`(`Name`, `Email`, `Address`, `City`, `State`, `Zipcode`, `Password`) VALUES " ."(" . $name .", ". $email .", ". $address .", ". $city .", ". $state .", ". $zip .", ". $pw . ")";

	echo($sql);

	//$sql = "INSERT INTO " . $tbl . "(" . $name .", ". $email .", ". $address .", ". $city .", ". $state .", ". $zip .", ". $pw . ")";

	if($conn->query($sql) === TRUE)
	{
		// worked
		echo("user added");
	}
	else
	{
		// did not work 
		echo("failed");
	}
}



$conn->close();
?>