<?php

require_once("cms_functions.php");

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

$title=$_POST["title"];
$author=$_POST["author"];
$body=$_POST["body"];
$post=$_POST["post"];

if(isset($post)){
    
    if(isset($title)&&isset($author)&&isset($body)){
        $blog_path="../blogs/".$title.".txt";
		file_put_contents($blog_path, $body);
		
		$db_blog_path="blogs/".$title.".txt";
		
		$timestamp = date('Y-m-d H:i:s');
		
		$sql_blog="INSERT INTO blogs(title, author, timestamp, edited_timestamp, body, like_count, dislike_count) VALUES('".$title."', '".$author."', '".$timestamp."', '".$timestamp."', '".$db_blog_path."', 0, 0);";
		$res_blog=mysqli_query($mysqli, $sql_blog);
		
		if($res_blog){
		    page_redirect("cms_blogIndex.php");
		}
        
    }
    
    
}

?>