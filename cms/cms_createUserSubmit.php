<?php

require_once("cms_functions.php");
require_once("cms_pages.php");

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

createUserSubmit();

?>