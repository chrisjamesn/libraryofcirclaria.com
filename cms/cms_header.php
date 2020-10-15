<?php

require_once("cms_functions.php");

require_once("cms_pages.php");

session_start();

login_check("cms_login.php");

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

echo "<html>
<head>
<title>Library of Circlaria CMS</title>
<meta charset=\"utf-8\">
<link rel=\"stylesheet\" media=\"all\" href=\"../styles.css\"/>
</head>
<body>
    <div class=\"header\">
    <h1><a href=\"".$cms_library."\" title=\"Library of Circlaria CMS\">Library of Circlaria CMS</a></h1>
    </div>";

?>