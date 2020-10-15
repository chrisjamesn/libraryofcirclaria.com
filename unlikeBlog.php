<?php

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

require_once("functions.php");

$blog_id=$_GET["blog_id"];
	
session_start();

$sql_unlike="UPDATE blogs SET like_count=like_count-1 WHERE blog_id=".$blog_id.";";
$res_unlike=mysqli_query($mysqli, $sql_unlike);

if($res_unlike){
    unset($_SESSION["like".$blog_id.""]);
    page_redirect("http://www.thechrisnutterhub.com/libraryofcirclaria.com/blog.php?blog_id=".$blog_id."");
}else{
    echo "<a href=\"http://www.thechrisnutterhub.com/libraryofcirclaria.com/blog.php?blog_id=".$blog_id."\">Database error occurred. Click to return to the blog.</a>";
}

?>