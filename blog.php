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

require_once("header.php");

require_once("pages.php");

//session_start();



$blog_id=$_GET["blog_id"];

$sql_blog="SELECT * FROM blogs WHERE blog_id=".$blog_id.";";
$res_blog=mysqli_query($mysqli, $sql_blog);

$sql_comments="SELECT * FROM comments WHERE blog=".$title.";";
$res_comments=mysqli_query($mysqli, $sql_comments);

$sql_replies="SELECT * FROM comments WHERE blog=".$title.";";
$res_replies=mysqli_query($mysqli, $sql_replies);

if($res_blog){
    
    while($blog=$res_blog->fetch_assoc()){
        
        echo "<div class=\"blog\">
	    <div class=\"blog-header\">
	    <h3>".$blog["title"]."</h3>
	    <p>Author: ".$blog["author"]."</p>
	    <p>Posted: ".$blog["timestamp"]."</p> 
	    <p>Edited: ".$blog["edited_timestamp"]."</p>
	    <p>Likes: ".$blog["like_count"]."</p>
        <p>Dislikes: ".$blog["dislike_count"]."</p>
	    </div>
	    <p>".str_replace("\n", " ", nl2br(h(file_get_contents("".$blog["body"].""))))."</p>";
	    if(isset($_SESSION["like".$blog_id.""])){
	        echo "<p>";
	        echo "<button onclick=\"blogUnlike()\">Unlike</button>"; 
	        echo "<button onclick=\"blogDislike()\" disabled>&#x1f44e;</button>";
	        echo "</p>";
	        
	    }
	    
	    if(isset($_SESSION["dislike".$blog_id.""])){
	        echo "<p>";
	        echo "<button onclick=\"blogLike()\" disabled>&#x1f44d;</button>"; 
	        echo "<button onclick=\"blogUndislike()\">Undislike</button>";
	        echo "</p>";
	        
	    }
	    
	    if(!isset($_SESSION["like".$blog_id.""])&&!isset($_SESSION["dislike".$blog_id.""])){
	        
	        echo "<p><button onclick=\"blogLike()\">&#x1f44d;</button>
	    	<button onclick=\"blogDislike()\">&#x1f44e;</button></p>
	        <p>Likes: ".$blog["like_count"]."</p>
	        <p>Dislikes ".$blog["dislike_count"]."</p>";    
	        
	    }
    
        
    }   
    
}

if($res_comments){
    
    while($comment=$res_comments->fetch_assoc()){
        
        echo "<div class=\"comments\">
        <h4>Name: ".$comment["comment_name"]."</h4>
        <p>Posted: ".$comment["timestamp"]."</p>
        <p>Edited: ".$comment["edited_timestamp"]."</p>
        <p>".$comment["body"]."</p>
        <p><button onclick=\"commentLike()\">&#x1f44d;</button>
        	<button onclick=\"commentDislike()\">&#x1f44e;</button></p>
        <p>Likes: ".$comment["like_count"]."</p>
        <p>Dislikes ".$comment["dislike_count"]."</p>
        </div>";    
        
    }
    
}

if($res_replies){
    
    while($reply=$res_replies->fetch_assoc()){
        
        echo "<div class=\"replies\">
        <h5>Name: ".$reply["reply_name"]."</h5>
        <p>Posted: ".$reply["timestamp"]."</p>
        <p>Edited: ".$reply["edited_timestamp"]."</p>
        <p>".$reply["body"]."</p>
	    <p><button onclick=\"replyLike()\">&#x1f44d;</button>
		    <button onclick=\"replyDislike()\">&#x1f44e;</button></p>
	    <p>Likes: ".$reply["like_count"]."</p>
	    <p>Dislikes ".$reply["dislike_count"]."</p>
	    </div>";    
        
    }
    
}
	




echo "</div>";

echo "<script>
        function blogLike(){
            window.location.href='http://www.thechrisnutterhub.com/libraryofcirclaria.com/likeBlog.php?blog_id=".$blog_id."';
        }
        function blogDislike(){
            window.location.href='http://www.thechrisnutterhub.com/libraryofcirclaria.com/dislikeBlog.php?blog_id=".$blog_id."';
        }
        function blogUnlike(){
            window.location.href='http://www.thechrisnutterhub.com/libraryofcirclaria.com/unlikeBlog.php?blog_id=".$blog_id."';
        }
        function blogUndislike(){
            window.location.href='http://www.thechrisnutterhub.com/libraryofcirclaria.com/undislikeBlog.php?blog_id=".$blog_id."';
        }
        </script>";

require_once("footer.php");

?>