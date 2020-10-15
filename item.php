<?php

require_once("header.php");

require_once("pages.php");

$servername="";
	$dbname="";
	$dbusername=""; 
	$dbpassword=""; 
    
	$mysqli= new mysqli($servername, $dbusername, $dbpassword, $dbname);

	if($mysqli->connect_errno){
		echo "Failed to connect to the database: (". $mysqli->connect_errno. ")" . $mysqli->connect_error;
		mysqli_close($mysqli);
		exit;
	}

viewItem();

require_once("footer.php");

?>