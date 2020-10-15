<?php

//Page navigation off the action pages
function page_redirect($location){

	header("Location: " . $location);
	exit();

}


//Login form
function loginForm(){
    
    session_start();

	echo "<h1>Please enter your username and password:</h1>
		<form action=\"cms_loginSubmit.php\" method=\"post\">
		Username: <input name=\"username\" type=\"text\" value=\"\"><br>
		Password: <input name=\"password\" type=\"password\" value=\"\"><br>
		<input name=\"submit\" type=\"submit\" value=\"Login\"></input>
		</form>
		<p><a href=\"cms_forgotUsernamePassword.php\">Forgot Username/Password</a></p>
		<p><a href=\"cms_createUser.php\">Create User</a></p>";

}

//Verify login input
function loginSubmit(){

	global $mysqli;
	global $cms_login;

	
	session_start();

	$inputUsername=$_POST["username"];
	$inputPassword=$_POST["password"];
	$submit=$_POST["submit"];

	$hashCommand="SELECT * FROM users WHERE username='".$inputUsername."';";
	$hash=mysqli_query($mysqli, $hashCommand);

	if(isset($submit)){

        while($hashline=$hash->fetch_assoc()){
            
            if($inputUsername==$hashline["username"] && password_verify($inputPassword, $hashline["password"])){

			$_SESSION["username"]=$inputUsername;
			$_SESSION["password"]=$inputPassword;

			page_redirect("cms_library.php");
			
            }
            
            
        }

	}else{
		    
		    if(password_verify($inputPassword, $hash["password"])){
		        
		        echo $inputPassword;
		        echo "<br>";
		        echo $hash["password"];
		    }
		
		//	page_redirect($cms_login);

	}

}



//Used in the header for every page protected by login
function login_check($cms_login){

	if(!isset($_SESSION["username"]) || !isset($_SESSION["password"])){
        		page_redirect($cms_login);
	}

}

//The logout function
function logout(){
    
    global $cms_login;

	session_start();
	unset($_SESSION["username"]);
	unset($_SESSION["password"]);
	page_redirect($cms_login);

}

//Forgot Username Password Form

function forgotUNPWForm(){

	session_start();

	echo "<h1>Please fill in the following information:</h1>
		<form action=\"cms_forgotUsernamePasswordSubmit.php\" method=\"post\">
		Email: <input name=\"email\" type=\"text\" value=\"\"><br>
		Security Question: What month, day, and year were you born?<br>
		Month: <input name=\"month\" type=\"text\" value=\"\"><br>
		Day: <input name=\"day\" type=\"text\" value=\"\"><br>
		Year: Month: <input name=\"year\" type=\"text\" value=\"\"><br>
		<input name=\"create\" type=\"submit\" value=\"Submit\"></input>
		</form>";

}

//Forgot Username Password Submit
function forgotUNPWSubmit(){

	global $mysqli;
	global $cms_login;

	require_once("cms_uNpW.php");
	
	session_start();

	$inputEmail=$_POST["email"];
	$email=password_hash($inputEmail, PASSWORD_DEFAULT);
	$inputMonth=$_POST["month"];
	$inputDay=$_POST["day"];
	$inputYear=$_POST["year"];
	$submit=$_POST["submit"];

	$hashCommand="SELECT month, day, year FROM users WHERE email=".$email.";";
	$hash=mysqli_query($mysqli, $hashCommand);

	if(isset($submit)){

		if(password_verify($inputMonth, $hash["month"]) 
		&& password_verify($inputDay, $hash["day"]) 
		&& password_verify($inputYear, $hash["year"])){

			page_redirect("cms_changePassword.php?email=".$email."");

		}else{
		
			page_redirect($cms_login);

		}

	}

}

//Change Password Form

function changePasswordForm(){

	global $mysqli;

	$getEmail=$_GET["email"];

	$sql_username="SELECT username FROM users WHERE email=".$getEmail.";";
	$username=mysqli_query($mysqli, $sql_username);

	session_start();

	echo "<h1>Please fill in the following information:</h1>
		<form action=\"cms_changePasswordSubmit.php?username=".$username."\" method=\"post\">
		Your Username is: ".$username."<br>
		New Password: <input name=\"newPassword\" type=\"password\" value=\"\"><br>
		Confirm Password: <input name=\"confirmPassword\" type=\"password\" value=\"\"><br>
		<input name=\"change\" type=\"submit\" value=\"Change\"></input>
		</form>";

}

//Change Password Submit

function changePasswordSubmit(){

	global $mysqli;

	$username=$_GET["username"];

	$newPassword=password_hash($_POST["newPassword"], PASSWORD_DEFAULT);
	$confirmPassword=password_hash($_POST["confirmPassword"], PASSWORD_DEFAULT);
	$change=$_POST["change"];

	if(isset($change)){

		if($newPassword==$confirmPassword){

			$update_sql="UPDATE users SET password=".$newPassword." WHERE username=".$username.";";
			mysqli_query($mysqli, $update_sql);
			page_redirect("cms_login.php");

		}else{

			page_redirect("cms_changePassword.php");

		}

	}else{

		page_redirect("cms_changePassword.php");

	}

}

//New User Form

function createUserForm(){

	session_start();

	echo "<h1>Please fill in the following information:</h1>
		<form action=\"cms_createUserSubmit.php\" method=\"post\">
		Email: <input name=\"email\" type=\"text\" value=\"\"><br>
		Username: <input name=\"username\" type=\"text\" value=\"\"><br>
		Password: <input name=\"password\" type=\"password\" value=\"\"><br>
		Confirm Password: <input name=\"confirmPassword\" type=\"password\" value=\"\"><br>
		Security Question: What month, day, and year were you born?<br>
		Month: <input name=\"month\" type=\"text\" value=\"\"><br>
		Day: <input name=\"day\" type=\"text\" value=\"\"><br>
		Year: <input name=\"year\" type=\"text\" value=\"\"><br>
		<input name=\"create\" type=\"submit\" value=\"Create\"></input>
		</form>";

}

//New User Submit

function createUserSubmit(){

	global $mysqli;

	$email=password_hash($_POST["email"], PASSWORD_DEFAULT);
	$username=$_POST["username"];
	$password=$_POST["password"];
	$confirmPassword=$_POST["confirmPassword"];
	$password_hash=password_hash($_POST["password"], PASSWORD_DEFAULT);
	$month=password_hash($_POST["month"], PASSWORD_DEFAULT);
	$day=password_hash($_POST["day"], PASSWORD_DEFAULT);
	$year=password_hash($_POST["year"], PASSWORD_DEFAULT);
	$create=$_POST["create"];

	if(isset($create)){
	    
	    if(!isset($email)){
            
            page_redirect("cms_createUser.php");

		}
		
		if(!isset($username)){

			page_redirect("cms_createUser.php");

		}
		
		if(!isset($password)){

			page_redirect("cms_createUser.php");

		}
		
		if(!isset($confirmPassword)){

			page_redirect("cms_createUser.php");

		}
		
		if(!isset($month)){

			page_redirect("cms_createUser.php");

		}
		
		if(!isset($day)){

			page_redirect("cms_createUser.php");

		}
	    
	    if(!isset($year)){

			page_redirect("cms_createUser.php");

		}
	
	    if($password!==$confirmPassword){
	        
	        page_redirect("cms_createUser.php");   
	        
	        
	    }else{
	        
	        $create_sql="INSERT INTO users(email, username, password, birthMonth, birthDay, birthYear) VALUES('".$email."', '".$username."', '".$password_hash."', '".$month."', '".$day."', '".$year."');";
			$create_res=mysqli_query($mysqli, $create_sql);
	        
	        
	    }

		if($create_res){

			page_redirect("cms_login.php");

		}else{

			page_redirect("cms_createUser.php");

		}

	}else{

		page_redirect("cms_createUser.php");

	}

}

//HTML escape function
function h($string=""){

	return htmlspecialchars($string);

}

//URL escape function
function u($string=""){

	return urlencode($string);

}


//Database connection function
function db_open(){

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
	
	return $mysqli;

}

//Database escape function
function d($mysqli, $string){

	return mysqli_real_escape_string($mysqli, $string);

}

//Database disconnect function
function db_close($res, $mysqli){

	if($res){
    		$res->close();
	}

	if($mysqli){
    		$mysqli->close();
	}

}

//Main table of continents
function listContinents(){
	
	//Database connection
	global $mysqli;

	//Href page objects
	global $cms_addContinent;
	global $cms_viewContinent;
	global $cms_editContinent;
	global $cms_deleteContinent;
	global $cms_continent;

	//Data selection for the table
	$sql_listContinents="SELECT * FROM continents;";

	$res_listContinents=mysqli_query($mysqli, $sql_listContinents);

	//Table header
	echo "<div class=\"main\">
	    <a href=\"http://www.thechrisnutterhub.com/libraryofcirclaria.com/cms/cms_blogIndex.php\">Blog Management</a>
		<table>
		<tr><th>Continent</th><th>UX</th><th>View</th><th>Edit</th><th>Delete</th></tr>";

	//Table data display
	if($res_listContinents->num_rows>0){

		while($row=$res_listContinents->fetch_assoc()){

			echo "<tr><td><a href=\"".$cms_continent."?continent_id=".u($row["continent_id"])."&continent_name=".h(u($row["continent_name"]))."\">".h(u($row["continent_name"]))."</a></td>
				<td>".$row["ux"]."</td>
				<td><a href=\"".$cms_viewContinent."?continent_id=".u($row["continent_id"])."\">View</a></td>
				<td><a href=\"".$cms_editContinent."?continent_id=".u($row["continent_id"])."\">Edit</a></td>
				<td><a href=\"".$cms_deleteContinent."?continent_id=".u($row["continent_id"])."&continent_name=".h(u($row["continent_name"]))."\">Delete</a></td></tr>";

		}

	}

	//End of the table and add continent feature
	echo "</table>
		<p><a href=\"".$cms_addContinent."\">Add Continent</a></p>";

	$res_listContinents->close();
	$mysqli->close();

}

function blogIndex(){
    
    //Database connection
	global $mysqli;

	//Href page objects
	global $cms_addContinent;
	global $cms_viewContinent;
	global $cms_editContinent;
	global $cms_deleteContinent;
	global $cms_continent;


	//Database data selection
	$sql_listContinents="SELECT * FROM continents ORDER BY continent_id;";
	$res_listContinents=mysqli_query($mysqli, $sql_listContinents);
	
	//Database blogs
	$sql_blogs="SELECT * FROM blogs ORDER BY edited_timestamp DESC;";
	$res_blogs=mysqli_query($mysqli, $sql_blogs);
	

	//Page generation, listing the continents pending the visiblility check
	if($res_listContinents){
	    
	    echo "<div class=\"navpane\">
	            <nav>";

		while($list=$res_listContinents->fetch_assoc()){

			if($list["ux"]=="Visible" || $list["ux"]=="visible"){

				$continent_title=ucfirst(d($mysqli, $list["continent_name"]));
				
				echo "<p style=\"margin-bottom:10px\"><a href=\"".$cms_continent."?continent_id=".d($mysqli, $list["continent_id"])."\">".h($continent_title)."</a></p><br>";

			}

		}
		
		echo "</nav>
		        </div>";

	}else{
	   echo "Update in progress. Content coming soon";
	}
	
	//Blogspace
	echo "<div class=\"heading\">
	       <p><img style=\"margin-bottom: 100px\" src=\"../remikra/Remikra Standard.jpg\" height=\"600\" width=\"800\" alt=\"Map of Remikra\"></p>"; 
	echo "</div>"; 
	
	echo "<div class=\"blog-list\" id=\"blogs\">";
	       
    if($res_blogs){
        
        while($blog=$res_blogs->fetch_assoc()){
            
            echo "<a href=\"http://www.thechrisnutterhub.com/libraryofcirclaria.com/cms/cms_editBlog.php?title=".$blog["title"]."\">".$blog["title"]."</a>";    
            
        }
        
    }
        
    echo "<br><button id=\"add\" onclick=\"newBlog()\">&plus;</button>";
    
    echo "</div>";
    
    echo "<script>
            function newBlog(){
            
                document.getElementById('add').style.visibility = 'hidden';
                
                document.getElementById('blogs').innerHTML +=  
              '<form action=\"cms_addBlog.php\" method=\"post\"><br> Title: <input name=\"title\" type=\"text\"><br> Author: <input name=\"author\" type=\"text\"><br> Body: <textarea name=\"body\" rows=\"100\" columns=\"100\"></textarea><br> <input name=\"post\" type=\"submit\" value=\"Post\"></form>';
                
            }
            </script>";
        
	        
	
	//Database disconnect
	$res_listContinents->close();
	$mysqli->close();
    
}

//User form for adding a new continent
function addContinentForm(){

	echo "<div class=\"heading\">
		<h2>Add New Continent</h2>
		</div>
		<div class=\"main\">
		<form action=\"cms_addContinentSubmit.php\" enctype=\"multipart/form-data\" method=\"post\">
		Continent Name: <input name=\"continent_name\" type=\"text\" value=\"\"></input><br>
		Map: <input accept=\"image/*\" id=\"map\" name=\"map\" type=\"file\"><br>
		Description: <textarea name=\"description\" rows=\"50\" cols=\"100\"></textarea>
		User Access: <select name=\"ux\">
				<option value=\"Hidden\">Hidden</option>
				<option value=\"Visible\">Visible</option>
				</select>
		<input name=\"submit\" type=\"submit\" value=\"Create\"></input>
		</form>";

}

//Handle for the above add continent form
function addContinent(){

	//Database connection
	global $mysqli;

	//Href page objects
	global $cms_library;
	global $cms_addContinent;

	//Input data handle objects- note, the text paths are for the database
	$continent_name=strtolower($_POST["continent_name"]);
	$map_path="".$continent_name."/".basename($_FILES["map"]["name"])."";
	$description=$_POST["description"];
	$ux=strtolower($_POST["ux"]);
	$submit=$_POST["submit"];

	//Submit button trigger
	if(isset($submit)){

		//To create a folder and move the uploaded files into it
		mkdir("../".$continent_name."");
		move_uploaded_file($_FILES["map"]["tmp_name"], "../".$map_path."");   
		
		$description_path="".$continent_name."/description.txt";
		file_put_contents("../".$description_path."", $description);

		//The corresponding database entry
		$sql_addContinent="INSERT INTO continents(continent_name, map_path, description_path, ux) VALUES('".d($mysqli, $continent_name)."', '".d($mysqli, $map_path)."', '".d($mysqli, $description_path)."', '".$ux."');";
		$res_addContinent=mysqli_query($mysqli, $sql_addContinent);

		//The page redirect to display the new results
		if($res_addContinent){
			page_redirect($cms_library);
		}else{
			page_redirect($cms_addContinent);

		}

	}

	$res_addContinent->close();
	$mysqli->close();

}

//The front-end webpage preview
function viewContinent(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_viewContinent;
	global $cms_viewAttribute;

	//Selected continent
	$continent_id=$_GET["continent_id"];

	//Database data selection
	$sql_viewContinent="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_viewContinent=mysqli_query($mysqli, $sql_viewContinent);
	$sql_viewContinentBody="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_viewContinentBody=mysqli_query($mysqli, $sql_viewContinentBody);
	$sql_listAttributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_listAttributes=mysqli_query($mysqli, $sql_listAttributes);

	if($res_viewContinent){

		//The continent title pending the visibility check
		while($title=$res_viewContinent->fetch_assoc()){

			$continent_title=ucfirst($title["continent_name"]);

			echo "<div class=\"heading\">
				<h2><a href=\"".$cms_viewContinent."?continent_id=".$continent_id."\">".h($continent_title)."";
				
			if($title["ux"]=="Hidden" || $title["ux"]=="hidden"){
			    
			    echo "*";
			    
			}
			
			echo "</a></h2>
				</div>";

		}

		echo "<br>";		

		//The navigation toolbar
		if($res_listAttributes){


			echo "<div class=\"navpane\">
				<nav>";

			//The associated attributes
			while($row=$res_listAttributes->fetch_assoc()){

				$attribute=ucfirst($row["attribute_name"]);

				echo "<p><a href=\"".$cms_viewAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">".h($attribute)."";
				
				if($row["ux"]=="Hidden" || $row["ux"]=="hidden"){
				
				    echo"*";
				    
				}
				
				echo "</a></p><br>";

				

			}

			echo "</nav>
				</div>";

		}

		//Continent profile body
		while($page=$res_viewContinentBody->fetch_assoc()){

			//Title, map, and description file paths
			$map_path=$page["map_path"];
			$continent_title=ucfirst($page["continent_name"]);
			$description_path=$page["description_path"];

			//Image and description
			echo "<div class=\"main\">
				<p><img src=\"../".$map_path."\" height=\"600\" width=\"800\" alt=\"Map of ".h($continent_title)."\"></p><br>
            			<p>".h(file_get_contents("../".$description_path.""))."</p><br>";

		}
		
	}	

    //Database disconnect
	$res_viewContinent->close();
	$res_viewContinentBody->close();
	$res_listAttributes->close();
	$mysqli->close();
}

//Function for the edit continents form
function editContinentForm(){

	//Database connection
	global $mysqli;

	//Selected continent
	$continent_id=$_GET["continent_id"];
	
	//Database query
	$sql_editContinent="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id)."";
	$res_editContinent=mysqli_query($mysqli, $sql_editContinent);

	//The edit form
	while($profile=$res_editContinent->fetch_assoc()){

		echo "<div class=\"heading\">
			<h2>Edit ".h(ucfirst($profile["continent_name"]))."</h2>
			</div>
			<div class=\"main\">
			<form action=\"cms_editContinentSubmit.php?continent_id=".u($continent_id)."\" enctype=\"multipart/form-data\" method=\"post\">
			Continent Name: <input name=\"continent_name\" type=\"text\" value=\"".h(ucfirst($profile["continent_name"]))."\"></input><br>
			Old Map: <p><img src=\"../".$profile["map_path"]."\" height=\"600\" width=\"800\"></p><br>
			New Map: <input accept=\"image/*\" id=\"map\" name=\"map\" type=\"file\"><br>
			Description: <textarea name=\"description\" rows=\"100\" cols=\"100\">".h(file_get_contents("../".$profile["description_path"].""))."</textarea><br>
			User Access: <select name=\"ux\">";
		if($profile["ux"]=="hidden"){

			echo "<option value=\"Hidden\" selected>Hidden</option>";


		}else{

			echo "<option value=\"Hidden\">Hidden</option>";

		}

		if($profile["ux"]=="visible"){

			echo "<option value=\"Visible\" selected>Visible</option>";

		}else{
		
			echo "<option value=\"Visible\">Visible</option>";
		
		}
		
			echo "</select>
			<input name=\"submit\" type=\"submit\" value=\"Create\"></input>
			</form>";

	}

	$res_editContinent->close();
	$mysqli->close();

}

//Function for the edit continents action page 
function editContinent(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_library;
	global $cms_editContinent;
	
	//URL parameter handle and form handles
	$continent_id=$_GET["continent_id"];
	$continent_name=strtolower($_POST["continent_name"]);
	$description=$_POST["description"];
	$ux=strtolower($_POST["ux"]);
	$submit=$_POST["submit"];
	
	
	//Unedited continent database query
	$sql_oldContinentMap="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id)."";
	$res_oldContinentMap=mysqli_query($mysqli, $sql_oldContinentMap);
	$sql_oldContinent="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id)."";
	$res_oldContinent=mysqli_query($mysqli, $sql_oldContinent);
	
	//The map conditional
	if($_FILES["map"]["size"]>0){
	    
	    $map_path="".$continent_name."/".basename($_FILES["map"]["name"])."";
	    
	}else{
	    
	    if($res_oldContinentMap){
	        
	        while($map=$res_oldContinentMap->fetch_assoc()){
	            
	            $map_path=$map["map_path"];   
	            
	        }    
	        
	    }
	    
	    
	}

	//Submit button trigger
	if(isset($submit)){

        if($res_oldContinent){
		while($profile=$res_oldContinent->fetch_assoc()){

			//Passed in database connection and continent selection
			global $mysqli;
			global $continent_name;

			//Continent name change, directory and database 
			if(isset($continent_name) && $continent_name!==$profile["continent_name"]){

				rename("../".$profile["continent_name"]."", "../".$continent_name."");

				$sql_newContinent="UPDATE continents SET continent_name='".d($mysqli, $continent_name)."' WHERE continent_id=".d($mysqli, $profile["continent_id"])."; ";
				$sql_newContinent.="UPDATE attributes SET continent_name='".d($mysqli, $continent_name)."' WHERE continent_id=".d($mysqli, $profile["continent_id"])."; ";
				$sql_newContinent.="UPDATE items SET continent_name='".d($mysqli, $continent_name)."' WHERE continent_id=".d($mysqli, $profile["continent_id"]).";";
				$res_newContinent=mysqli_query($mysqli, $sql_newContinent);

			}

			//Map change, directory and database
			if(isset($map_path) && $map_path!==$profile["map_path"]){

				rename("../".$profile["map_path"]."", "../".$map_path."");

				$sql_newMap="UPDATE continents SET map_path='".d($mysqli, $map_path)."' WHERE continent_id=".d($mysqli, $profile["continent_id"]).";";
				$res_newMap=mysqli_query($mysqli, $sql_newMap);

				if($res_newMap){

					move_uploaded_file($_FILES["map"]["tmp_name"], "../".$map_path."");

				}

			}
		
			//Description change
			if(isset($description)){

				file_put_contents("../".$profile["description_path"]."", $description);

			}

			//User access status change
			if(isset($ux) && $ux!==$profile["ux"]){

				$sql_newUxContinent="UPDATE continents SET ux='".d($mysqli, $ux)."' WHERE continent_id=".d($mysqli, $profile["continent_id"])."; ";
				$res_newUxContinent=mysqli_query($mysqli, $sql_newUxContinent);
				$sql_newUxAttribute="UPDATE attributes SET ux='".d($mysqli, $ux)."' WHERE continent_id=".d($mysqli, $profile["continent_id"])."; ";
				$res_newUxAttribute=mysqli_query($mysqli, $sql_newUxAttribute);
				$sql_newUxItem="UPDATE items SET ux='".d($mysqli, $ux)."' WHERE continent_id=".d($mysqli, $profile["continent_id"]).";";
				$res_newUxItem=mysqli_query($mysqli, $sql_newUxItem);

			}

		}
        }
        
        if($res_newContinent || $res_newMap || isset($description) || $res_newUxContinent || $res_newUxAttribute || $res_newUxItem){
            
            page_redirect($cms_library);    
            
        }else{
            
            page_redirect("".$cms_editContinent."?continent_id=".$continent_id."");
            
        }
        
		

	}else{

		page_redirect("".$cms_editContinent."?continent_id=".$continent_id."");

	}

	$res_oldContinent->close();
	$res_newContinent->close();
	$res_newMap->close();
	$res_newUx->close();
	$mysqli->close();

}

//Form for deleting a continent
function deleteContinentForm(){

	//Database connection
	global $mysqli;
	
	//Selected continent variables
	$continent_name=$_GET["continent_name"];
	$continent_id=$_GET["continent_id"];
	
	//Selected continent profile
	$sql_continent="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_continent=mysqli_query($mysqli, $sql_continent);

	//Selected continent attributes
	$sql_attributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_attributes=mysqli_query($mysqli, $sql_attributes);

	//Selected continent items
	$sql_items="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_items=mysqli_query($mysqli, $sql_items);

	//Header for the list of items about to be deleted
	echo "<div class=\"heading\">
		<h2>You are about to delete all items associated with ".h($continent_name)."</h2>
		<h3>The following items will be deleted:</h3><br>";

	//Listed continent and its profile
	if($res_continent){

		while($list=$res_continent->fetch_assoc()){

			echo "<p>Folder: ".h($continent_name)."</p><br>
				<li>".h($list["map_path"])."</li><br>
				<li>".h($list["description_path"])."</li><br>";
 

		}

	}

	//Listed associated attributes
	if($res_attributes){

		while($list=$res_attributes->fetch_assoc()){

			echo "<p>Folder: ".h($list["attribute_name"])."</p><br>
				<li>".h($list["image_path"])."</li><br>
				<li>".h($list["description_path"])."</li><br>";
 

		}

	}

	//Listed associated items
	if($res_items){

		while($list=$res_items->fetch_assoc()){

			echo "<p>".h($list["title"])."</p><br>
				<li>".h($list["image_path"])."</li><br>
				<li>".h($list["description_path"])."</li><br>";
 

		}

	}

	//Form for the confirmation question and username, password requirement
	echo "</div><div class=\"main\">
		<form action=\"cms_deleteContinentSubmit.php?continent_id=".u($continent_id)."\" method=\"post\">
	Are you sure you want to delete this?<br>	
	Username: <input name=\"username\" type=\"text\" value=\"\"></input><br>
	Password: <input name=\"password\" type=\"password\" value=\"\"></input><br>
	<input name=\"delete\" type=\"submit\" value=\"Delete\"></input>
	<input name=\"cancel\" type=\"submit\" value=\"Cancel\"></input>
	</form>";
	
	$res_continent->close();
	$res_attributes->close();
	$res_items->close();
	$mysqli->close();
}

function deleteContinent(){

	//Database connection
	global $mysqli;

	//Selected continent
	$continent_id=$_GET["continent_id"];
	
	//Hrefs
	global $cms_library;
	global $cms_deleteContinent;
	
	//Selection of the paths of the files to delete
	$sql_itemFiles="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_itemFiles=mysqli_query($mysqli, $sql_itemFiles);
	$sql_attributeFiles="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_attributeFiles=mysqli_query($mysqli, $sql_attributeFiles);
	$sql_continentFiles="SELECT * FROM continents WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_continentFiles=mysqli_query($mysqli, $sql_continentFiles);

	//Deletion of the item files
//	if($res_itemFiles){

//		while($itemFile=$res_itemFiles->fetch_assoc()){

//			unlink("".$itemFile["image_path"]."");
//			unlink("".$itemFile["description_path"]."");

//		}

//	}

	//Deletion of the attribute files
//	if($res_attributeFiles){

//		while($attributeFile=$res_attributeFiles->fetch_assoc()){

//			unlink("".$attributeFile["image_path"]."");
//			unlink("".$attributeFile["description_path"]."");

//		}

//	}

	//Deletion of the continent files
//	if($res_continentFiles){

//		while($continentFile=$res_continentFiles->fetch_assoc()){

//			unlink("".$continentFile["image_path"]."");
//			unlink("".$continentFile["description_path"]."");

//		}

//	}

	//Deletion of the associated rows from the database
	$sql_deleteContinentItems="DELETE FROM items WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_deleteContinentItems=mysqli_query($mysqli, $sql_deleteContinentItems);
	$sql_deleteContinentAttributes="DELETE FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_deleteContinentAttributes=mysqli_query($mysqli, $sql_deleteContinentAttributes);
	$sql_deleteContinent="DELETE FROM continents WHERE continent_id=".d($mysqli, $continent_id)." LIMIT 1";
	$res_deleteContinent=mysqli_query($mysqli, $sql_deleteContinent);

	if($res_deleteContinent){

		page_redirect($cms_library);

	}else{

		page_redirect("".$cms_deleteContinent."?continent_id=".$continent_id."");

	}

    if($res_itemFiles){
        
        $res_itemFiles->close();   
        
    }
    if($res_attributeFiles){
        
        $res_attributeFiles->close();    
        
    }
    if($res_continentFiles){
        
        $res_continentFiles->close();    
        
    }
    if($res_deleteContinent){
        
        $res_deleteContinent->close();    
        
    }
    if($res_deleteContinentAttributes){
        
        $res_deleteContinentAttributes->close();    
        
    }
    if($res_deleteContinentItems){
        
        $res_deleteContinentItems->close();    
        
    }
 
	$mysqli->close();

}

//CMS continent attribute page
function listAttributes(){

	//Database connection
	global $mysqli;

	//The href paths for the pages
	global $cms_attribute;
	global $cms_addAttribute;
	global $cms_viewAttribute;
	global $cms_editAttribute;
	global $cms_deleteAttribute;

	//The selected continent
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];

	//Database selection of the continent
	$sql_listAttributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_listAttributes=mysqli_query($mysqli, $sql_listAttributes);

	//Continent attribute table header
	echo "<div class=\"main\">
		<table>
		<tr><th>Attribute</th><th>UX</th><th>View</th><th>Edit</th><th>Delete</th></tr>";

	//Table body, drawn from the database
	if($res_listAttributes->num_rows>0){

		while($row=$res_listAttributes->fetch_assoc()){

			echo "<tr><td><a href=\"".$cms_attribute."?continent_id=".u($continent_id)."&continent_name=".h($row["continent_name"])."&attribute_id=".u($row["attribute_id"])."&attribute_name=".h($row["attribute_name"])."\">".h($row["attribute_name"])."</a></td>
				<td>".$row["ux"]."</td>
				<td><a href=\"".$cms_viewAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">View</a></td>
				<td><a href=\"".$cms_editAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">Edit</a></td>
				<td><a href=\"".$cms_deleteAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">Delete</a></td></tr>";

		}

	}

	//End of table and add attribute link
	echo "</table>
		<p><a href=\"".$cms_addAttribute."?continent_id=".u($continent_id)."&continent_name=".h(u($continent_name))."\">Add Attribute</a></p>";

	$res_listAttributes->close(); 
	$mysqli->close();

}

//Form for adding attribute
function addAttributeForm(){

	//Selected continent
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];

	//Add attribute form
	echo "<div class=\"heading\">
		<h2>Add New Attribute</h2>
		</div>
		<div class=\"main\">
		<form action=\"cms_addAttributeSubmit.php?continent_id=".u($continent_id)."&continent_name=".h(u($continent_name))."\" enctype=\"multipart/form-data\" method=\"post\">
		Attribute Name: <select name=\"attribute_name\">
				<option value=\"character_biographies\">Character Biographies</option>
				<option value=\"history\">History</option>
				<option value=\"stories\">Stories<option>
				</select><br>
		Image: <input accept=\"image/*\" id=\"image\" name=\"image\" type=\"file\"><br>
		Description: <textarea name=\"description\" rows=\"25\" cols=\"100\"></textarea><br>
		User Access: <select name=\"ux\">
				<option value=\"Hidden\">Hidden</option>
				<option value=\"Visible\">Visible</option>
				</select><br>
		<input name=\"submit\" type=\"submit\" value=\"Create\"></input>
		</form>";

}

//Function for adding attribute
function addAttribute(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_library;
	global $cms_addAttribute;
	global $cms_continent;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];

	//Form handlers
	$attribute_name=$_POST["attribute_name"];
	$image_path="".$continent_name."/".$attribute_name."/".basename($_FILES["image"]["name"])."";
	$description=$_POST["description"];
	$ux=$_POST["ux"];
	$submit=$_POST["submit"];

	//Regarding assignment of attribute_id
	if($attribute_name=="character_biographies"){

		$attribute_id=1;

	}elseif($attribute_name=="history"){

		$attribute_id=2;

	}elseif($attribute_name=="stories"){

		$attribute_id=3;

	}

	//Submit button trigger
	if(isset($submit)){

		//To create a folder and move the uploaded files into it
		mkdir("../".$continent_name."/".$attribute_name."");
		move_uploaded_file($_FILES["image"]["tmp_name"], "../".$image_path."");
		$description_path="".$continent_name."/".$attribute_name."/description.txt";
		file_put_contents("../".$description_path."", $description);

		//Corresponding database entry
		$sql_addAttribute="INSERT INTO attributes(continent_id, continent_name, ";
		$sql_addAttribute.="attribute_id, attribute_name, image_path, description_path, ux) ";
		$sql_addAttribute.="VALUES(".d($mysqli, $continent_id).", '".d($mysqli, $continent_name)."', ";
		$sql_addAttribute.="".d($mysqli, $attribute_id).", '".d($mysqli, $attribute_name)."','".d($mysqli, $image_path)."', ";
		$sql_addAttribute.="'".d($mysqli, $description_path)."', '".$ux."');";
		$res_addAttribute=mysqli_query($mysqli, $sql_addAttribute);

	}

	if($res_addAttribute){
		page_redirect("".$cms_continent."?continent_id=".$continent_id."");
	}else{
		page_redirect($cms_addAttribute);

	}

	$res_addAttribute->close(); 
	$mysqli->close();

}

//Front end display of attribute
function viewAttribute(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_viewItem;
	global $cms_viewAttribute;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];

	//Database attribute selection for the page
	$sql_viewAttribute="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_viewAttribute=mysqli_query($mysqli, $sql_viewAttribute);
	$sql_viewAttributeBody="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_viewAttributeBody=mysqli_query($mysqli, $sql_viewAttributeBody);

	//Database attribute selection for the nav pane
	$sql_listAttributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_listAttributes=mysqli_query($mysqli, $sql_listAttributes);

	//Database item selection for the nav pane
	$sql_listItems="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." ORDER BY pg;";

	//The page display
	if($res_viewAttribute){

		//The attribute page header
		while($title=$res_viewAttribute->fetch_assoc()){

			$continent_title=ucfirst($title["continent_name"]);
			$attribute_title=ucfirst($title["attribute_name"]);

			echo "<div class=\"heading\">
				<h2><a href=\"".$cms_viewAttribute."?continent_id=".$continent_id."&attribute_id=".$attribute_id."\">".h($continent_title)." ".h($attribute_title)."";
				
			if($title["ux"]=="Hidden" || $title["ux"]=="hidden"){
			    
			    echo "*";
			    
			}
			
			echo "</a></h2>
				</div>";

		}

		//The nav pane
		if($res_listAttributes){

			echo "<div class=\"navpane\">
				<nav>";

			//Listed attributes
			while($row=$res_listAttributes->fetch_assoc()){

				echo "<p><a href=\"".$cms_viewAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">".h(ucfirst($row["attribute_name"]))."";
				
				if($row["ux"]=="Hidden" || $row["ux"]=="hidden"){
				
				    echo "*";
				
				}
				
				echo "</a></p><br>";
					
					//Listed items under the selected attribute
					
				if($row["attribute_id"]==$attribute_id){
					    
			        $res_listItems=mysqli_query($mysqli, $sql_listItems);
					
				    if($res_listItems){

				        while($item=$res_listItems->fetch_assoc()){

					    	    if($item["attribute_id"]==$attribute_id){

					    			echo"</div><div class=\"navpanesub\">
					    			    <p><a href=\"".$cms_viewItem."?continent_id=".u($item["continent_id"])."&attribute_id=".u($item["attribute_id"])."&title=".u($item["title"])."\">".h(ucfirst($item["title"]))."";
					    			    
					    			if($item["ux"]=="Hidden" || $item["ux"]=="hidden"){
					    			    
					    			    echo "*"; 
					    			    
					    			}
					    			
					    			echo "</a></p>
						    		    </div><div class=\"navpane\">";

						    	}

				    	 }

				    }

			}

				    

		}

			echo "</nav>
			        </div>";

		}

		//The image and body of the attribute page
		while($page=$res_viewAttributeBody->fetch_assoc()){

			$continent_title=ucfirst($page["continent_name"]);
			$description_path=$page["description_path"];

			echo "<div class=\"main\">
				<img src=\"../".$page["image_path"]."\" alt=\"Map of ".h($continent_title)."\" height=\"600\" width=\"800\"><br>
				<p>".h(file_get_contents("../".$description_path.""))."</p>";
			

		}
		
	}

    //Database disconnect
    $res_viewAttribute->close();
    $res_viewAttributeBody->close();
    $res_listAttributes->close();
    $res_listItems->close();
    $mysqli->close();

}

//Form for edit attribute
function editAttributeForm(){

	//Database connection
	global $mysqli;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];
	
	//Selected attributes from database
	$sql_editAttribute="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_editAttribute=mysqli_query($mysqli, $sql_editAttribute);

	//The attribute edit form
	while($profile=$res_editAttribute->fetch_assoc()){

		echo "<div class=\"heading\">
			<h2>Edit ".h(ucfirst($profile["continent_name"]))." ".h(ucfirst($profile["attribute_name"]))."</h2>
			</div>
			<div class=\"main\">
			<form action=\"cms_editAttributeSubmit.php?continent_id=".u($continent_id)."&attribute_id=".u($attribute_id)."&attribute_name=".h(u($profile["attribute_name"]))."&continent_name=".h(u($profile["continent_name"]))."\" enctype=\"multipart/form-data\" method=\"post\">
			Old Image: <p><img src=\"../".$profile["image_path"]."\" height=\"600\" width=\"800\"></p><br>
			New Image: <input accept=\"image/*\" id=\"image\" name=\"image\" type=\"file\"><br>
			Description: <textarea name=\"description\" rows=\"100\" cols=\"100\">".h(file_get_contents("../".$profile["description_path"].""))."</textarea><br>
			User Access: <select name=\"ux\">";
		if($profile["ux"]=="Hidden" || $profile["ux"]=="hidden"){

			echo "<option value=\"Hidden\" selected>Hidden</option>";


		}else{

			echo "<option value=\"Hidden\">Hidden</option>";

		}

		if($profile["ux"]=="Visible" || $profile["ux"]=="visible"){

			echo "<option value=\"Visible\" selected>Visible</option>";

		}else{
		
			echo "<option value=\"Visible\">Visible</option>";
		
		}
		
			echo "</select>
			<input name=\"submit\" type=\"submit\" value=\"Create\"></input>
			</form>";

	}

	$res_editAttribute->close(); 
	$mysqli->close();

}

//The edit attribute function
function editAttribute(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_library;
	global $cms_editAttribute;
	global $cms_continent;
	global $cms_attribute;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];
	$attribute_id=$_GET["attribute_id"];
	$attribute_name=$_GET["attribute_name"];

	//Form handlers
	if($_FILES["image"]["size"]>0){
	    
	    $image_path="".$continent_name."/".$attribute_name."/".basename($_FILES["image"]["name"])."";   
	    
	    
	}
	
	$description=$_POST["description"];
	$ux=$_POST["ux"];
	$submit=$_POST["submit"];

	//Submit button trigger
	if(isset($submit)){

		//To edit the image file
		if($_FILES["image"]["size"]>0){
		    
		    move_uploaded_file($_FILES["image"]["tmp_name"], "../".$image_path."");
		    
		    //Corresponding database entry
		    $sql_editImage="UPDATE attributes SET image_path='".$image_path."' WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id.";";
		    $res_editImage=mysqli_query($mysqli, $sql_editImage);
		    
		}
		
		//To edit the description file
		$description_path="".$continent_name."/".$attribute_name."/description.txt";
		$editDescription=file_put_contents("../".$description_path."", $description);

		//Database entry for the UX
		$sql_editUxAttribute="UPDATE attributes SET ux='".$ux."' WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id.";";
		$res_editUxAttribute=mysqli_query($mysqli, $sql_editUxAttribute);
		$sql_editUxItem="UPDATE items SET ux='".$ux."' WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id.";";
		$res_editUxItem=mysqli_query($mysqli, $sql_editUxItem);
		
		if($res_editImage || $editDescription || $res_editUxAttribute || $res_editUxItem){
		    
		    page_redirect("".$cms_continent."?continent_id=".$continent_id."");    
		    
		}else{
		    
		    page_redirect("".$cms_editAttribute."?continent_id=".$continent_id."&attribute_id=".$attribute_id."");   
		    
		}
		
		

	}else{
		page_redirect("".$cms_editAttribute."?continent_id=".$continent_id."&attribute_id=".$attribute_id."");

	}

	$res_addAttribute->close(); 
	$mysqli->close();

}

//Form for delete attribute
function deleteAttributeForm(){

	//Database connection
	global $mysqli;
	
	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];
	$attribute_id=$_GET["attribute_id"];
	$attribute_name=$_GET["attribute_name"];

	//Selected attribute and items from the database
	$sql_attributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)."; ";
	$res_attributes=mysqli_query($mysqli, $sql_attributes);
	$sql_items="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_items=mysqli_query($mysqli, $sql_items);

	//Delete page header
	echo "<div class=\"heading\">
		<h2>You are about to delete all items associated with ".h($continent_name)." ".h($attribute_name)."</h2>
		<h3>The following items will be deleted:</h3><br>";


	//List of associated files to be deleted
	if($res_attributes){

		while($list=$res_attributes->fetch_assoc()){

			echo "<p>Folder: ".h($list["attribute_name"])."</p><br>
				<li>".h($list["image_path"])."</li><br>
				<li>".h($list["description_path"])."</li><br>";
 

		}

	}

	if($res_items){

		while($list=$res_items->fetch_assoc()){

			echo "<p>".h($list["title"])."</p><br>
				<li>".h($list["image_path"])."</li><br>
				<li>".h($list["description_path"])."</li><br>";
 

		}

	}

	//Form for confirmation and password, username input
	echo "</div><div class=\"main\">
		<form action=\"cms_deleteAttributeSubmit.php?continent_id=".u($continent_id)."&attribute_id=".u($attribute_id)."\" method=\"post\">
	Are you sure you want to delete this?<br>	
	Username: <input name=\"username\" type=\"text\" value=\"\"></input><br>
	Password: <input name=\"password\" type=\"password\" value=\"\"></input><br>
	<input name=\"delete\" type=\"submit\" value=\"Delete\"></input>
	<input name=\"cancel\" type=\"submit\" value=\"Cancel\"></input>
	</form>";

	$res_attributes->close();
	$res_items->close(); 
	$mysqli->close();

}

//The delete attribute function
function deleteAttribute(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_continent;
	global $cms_deleteAttribute;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];
	
	//Selection of the paths of the files to delete
	$sql_itemFiles="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)."; ";
	$res_itemFiles=mysqli_query($mysqli, $sql_itemFiles);
	$sql_attributeFiles="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." LIMIT 1;";
	$res_attributeFiles=mysqli_query($mysqli, $sql_attributeFiles);

	//Deletion of the item files
//	if($res_itemFiles){

	//	while($itemFile=$res_itemFiles->fetch_assoc()){

	//		unlink("".$itemFile["image_path"]."");
	//		unlink("".$itemFile["description_path"]."");

	//	}

//	}

	//Deletion of the attribute files
//	if($res_attributeFiles){

//		while($attributeFile=$res_attributeFiles->fetch_assoc()){

//			unlink("".$attributeFile["image_path"]."");
//			unlink("".$attributeFile["description_path"]."");

//		}

//	}


	//Deletion of the associated rows from the database
	$sql_deleteAttributeItems="DELETE FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_deleteAttributeItems=mysqli_query($mysqli, $sql_deleteAttributeItems);
	$sql_deleteAttribute="DELETE FROM attributes WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_deleteAttribute=mysqli_query($mysqli, $sql_deleteAttribute);

	if($res_deleteAttributeItems && $res_deleteAttribute){

		page_redirect("".$cms_continent."?continent_id=".$continent_id."");

	}else{

		page_redirect("".$cms_deleteAttribute."?continent_id=".$continent_id."&attribute_id=".$attribute_id."");

	}
    if($res_itemFiles){
        
        $res_itemFiles->close();    
        
    }
	if($res_attributeFiles){
	    
	    $res_attributeFiles->close();    
	    
	}
	
	if($res_deleteAttributeItems){
	    
	    $res_deleteAttributeItems->close();    
	    
	}
	if($res_deleteAttribute){
	    
	    $res_deleteAttribute->close();    
	    
	}
	$mysqli->close();

}

//CMS display of continent and attribute items
function listItems(){

	//Database connection
	global $mysqli;

	//The href paths for the pages
	global $cms_addItem;
	global $cms_viewItem;
	global $cms_editItem;
	global $cms_deleteItem;
	global $cms_continent;

	//The selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];
	$attribute_id=$_GET["attribute_id"];
	$attribute_name=$_GET["attribute_name"];

	//Selection of items from the database
	$sql_listItems="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id).";";
	$res_listItems=mysqli_query($mysqli, $sql_listItems);

	//Table header
	echo "<div class=\"main\">
	    <p><a href=\"".$cms_continent."?continent_id=".$continent_id."\">Back to ".ucfirst($continent_name)." Table</a></p>
		<table>
		<tr><th>Item</th><th>UX</th><th>View</th><th>Edit</th><th>Delete</th></tr>";

	//Body display of items from the database
	if($res_listItems->num_rows>0){

		while($row=$res_listItems->fetch_assoc()){

			echo "<tr><td>".h($row["title"])."</td>
				<td>".$row["ux"]."</td>
				<td><a href=\"".$cms_viewItem."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."&title=".h(u($row["title"]))."\">View</a></td>
				<td><a href=\"".$cms_editItem."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."&title=".h(u($row["title"]))."\">Edit</a></td>
				<td><a href=\"".$cms_deleteItem."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."&title=".h(u($row["title"]))."\">Delete</a></td></tr>";

		}

	}

	//The add item link
	echo "</table>
		<p><a href=\"".$cms_addItem."?continent_id=".u($continent_id)."&continent_name=".h(u($continent_name))."&attribute_id=".u($attribute_id)."&attribute_name=".h(u($attribute_name))."\">Add Item</a></p>";

	if($res_listItems){
	    $res_listItems->close();   
	}
	 
	$mysqli->close();

}

//Form for add item
function addItemForm(){

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];
	$attribute_id=$_GET["attribute_id"];
	$attribute_name=$_GET["attribute_name"];

	//The add item form
	echo "<div class=\"heading\">
		<h2>Add New Item</h2>
		</div>
		<div class=\"main\">
		<form action=\"cms_addItemSubmit.php?continent_id=".u($continent_id)."&continent_name=".h(u($continent_name))."&attribute_id=".u($attribute_id)."&attribute_name=".h(u($attribute_name))."\" enctype=\"multipart/form-data\" method=\"post\">
		Page Number: <input name=\"pg\" type=\"text\" value=\"\"></input><br>
		Item Title: <input name=\"title\" type=\"text\" value=\"\"></input><br>
		Image: <input accept=\"image/*\" id=\"image\" name=\"image\" type=\"file\"><br>
		Description: <textarea name=\"description\" rows=\"25\" cols=\"100\"></textarea><br>
		User Access: <select name=\"ux\">
				<option value=\"Hidden\">Hidden</option>
				<option value=\"Visible\">Visible</option>
				</select><br>
		<input name=\"submit\" type=\"submit\" value=\"Create\"></input>
		</form>";

}

//The add item function
function addItem(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_library;
	global $cms_attribute;
	global $cms_addItem;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];
	$attribute_id=$_GET["attribute_id"];
	$attribute_name=$_GET["attribute_name"];
	
	//Add item form handles
	$pg=$_POST["pg"];
	$title=$_POST["title"];	
	$image_path="".$continent_name."/".$attribute_name."/".$title."/".basename($_FILES["image"]["name"])."";
	$description=$_POST["description"];
	$ux=$_POST["ux"];
	$submit=$_POST["submit"];

	//Submit button trigger
	if(isset($submit)){

		//To create a folder and move the uploaded files into it
		mkdir("../".$continent_name."/".$attribute_name."/".$title."");
		move_uploaded_file($_FILES["image"]["tmp_name"], "../".$image_path."");
		$description_path="".$continent_name."/".$attribute_name."/".$title."/description.txt";
		file_put_contents("../".$description_path."", $description);

		//Corresponding database entry
		$sql_addItem="INSERT INTO items(continent_id, continent_name, ";
		$sql_addItem.="attribute_id, attribute_name, title, image_path, description_path, ux, pg) ";
		$sql_addItem.="VALUES(".d($mysqli, $continent_id).", '".d($mysqli, $continent_name)."', ";
		$sql_addItem.="".d($mysqli, $attribute_id).", '".d($mysqli, $attribute_name)."', '".d($mysqli, $title)."', ";
		$sql_addItem.="'".d($mysqli, $image_path)."', '".d($mysqli, $description_path)."', '".$ux."', '".d($mysqli, $pg)."');";
		$res_addItem=mysqli_query($mysqli, $sql_addItem);

		

	}

	if($res_addItem){
		page_redirect("".$cms_attribute."?continent_id=".$continent_id."&continent_name=".$continent_name."&attribute_id=".$attribute_id."&attribute_name=".$attribute_name."");
	}else{
		page_redirect($cms_addItem);
	}

	$res_addItem->close(); 
	$mysqli->close();
	
}

function viewItem(){

	//Database connection
	global $mysqli;
	global $cms_viewAttribute;
	global $cms_viewItem;

	//Selected continent, attribute, and title
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];
	$title=$_GET["title"];

	//Selected continent attribute items for the page
	$sql_viewItem="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." AND title='".d($mysqli, $title)."';";
	$res_viewItem=mysqli_query($mysqli, $sql_viewItem);
	$sql_viewItemBody="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." AND title='".d($mysqli, $title)."';";
	$res_viewItemBody=mysqli_query($mysqli, $sql_viewItemBody);

	//Selected continent attributes for the nav pane
	$sql_listAttributes="SELECT * FROM attributes WHERE continent_id=".d($mysqli, $continent_id).";";
	$res_listAttributes=mysqli_query($mysqli, $sql_listAttributes);

	//Selected continent attribute items for the nav pane
	$sql_listItems="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." ORDER BY pg;";
	
	//Obtain the page number array
	$sql_pages="SELECT * FROM items WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id.";";
	$res_pages=mysqli_query($mysqli, $sql_pages);
	while($pgList=$res_pages->fetch_assoc()){
	    
	    $pgNumbers=$pgList["pg"];
	    
	}

	if($res_viewItem){

		//Page heading
		while($heading=$res_viewItem->fetch_assoc()){


			$continent_title=ucfirst($heading["continent_name"]);
            $attribute_title=ucfirst($heading["attribute_name"]);
			$item_title=ucfirst($heading["title"]);

			echo "<div class=\"heading\">
				<h2><a href=\"".$cms_viewItem."?continent_id=".$continent_id."&attribute_id=".$attribute_id."&title=".$title."\">".$continent_title." ".$attribute_title.": ".$item_title."";
				
			if($heading["ux"]=="Hidden" || $heading["ux"]=="hidden"){
			    
			    echo "*";    
			    
			}
			
			echo "</a></h2>
				</div>";

		}

		//The nav pane
		if($res_listAttributes){

			echo "<div class=\"navpane\">
				<nav>";

			//Listed attributes
			while($row=$res_listAttributes->fetch_assoc()){

				echo "<p><a href=\"".$cms_viewAttribute."?continent_id=".u($row["continent_id"])."&attribute_id=".u($row["attribute_id"])."\">".h(ucfirst($row["attribute_name"]))."";
				
				if($row["ux"]=="Hidden" || $row["ux"]=="hidden"){
				
				    echo "*";
				
				}
				
				echo "</a></p><br>";
					
				//Listed items under the selected attribute
					
				if($row["attribute_id"]==$attribute_id){
					    
					   $res_listItems=mysqli_query($mysqli, $sql_listItems);
					
					   if($res_listItems){

					       while($item=$res_listItems->fetch_assoc()){

					    		   if($item["attribute_id"]==$attribute_id){

					    			echo"</div><div class=\"navpanesub\">
					    		    <p><a href=\"".$viewItem."?continent_id=".u($item["continent_id"])."&attribute_id=".u($item["attribute_id"])."&title=".u($item["title"])."\">".h(ucfirst($item["title"]))."";
					    		    
					    		    if($item["ux"]=="Hidden" || $item["ux"]=="hidden"){
					    		        
					    		        echo "*";   
					    		        
					    		    }
					    		    
					    		    echo "</a></p>
						    		    </div><div class=\"navpane\">";

						    	    }

				    	    }

				        }

				}
					
				    

			}

			echo "</nav>
			        </div>";

		}

		//Item page body
		while($page=$res_viewItemBody->fetch_assoc()){

			//Title, map, and description file paths
			$image_path=$page["image_path"];
			$item=ucfirst($page["title"]);
			$description_path=$page["description_path"];
			
			//Pages
			$current=$page["pg"];
			$previous=$current-1;
			$next=$current+1;
			$maxPg=max($pgNumbers);
			$minPg=min($pgNumbers);

			//Image and description
			echo "</div><div class=\"main\">
				<p><img src=\"../".$image_path."\" height=\"600\" width=\"800\" alt=\"".$item."\"></p><br>
				<p>";
				
				if($previous>=$minPg){
				    
				    echo "<a href=\"".$cms_viewItem."?continent_id=".$continent_id."&attribute_id=".$attribute_id."&pg=".$previous."\"></a>";   
				    
				}
				
				if($next<=$maxPg){
				    
				    echo "<a href=\"".$cms_viewItem."?continent_id=".$continent_id."&attribute_id=".$attribute_id."&pg=".$next."\"></a>";    
				    
				}
				
				echo "</p>";
				
				
            	echo "<p>".str_replace("\n", " ", nl2br(h(file_get_contents("../".$description_path.""))))."</p>";

		}
		
	}

    //Database disconnect
    $res_viewItem->close();
    $res_viewItemBody->close();
    $res_listAttributes->close();
    $res_listItems->close();
    $mysqli->close();

}

//Form for edit item
function editItemForm(){

	//Database connection
	global $mysqli;

	//Selected continent, attribute, and item
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];
	$title=$_GET["title"];
	
	//Corresponding database selection
	$sql_editItem="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." AND title='".d($mysqli, $title)."';";
	$res_editItem=mysqli_query($mysqli, $sql_editItem);

	//Edit item form
	while($profile=$res_editItem->fetch_assoc()){

		echo "<div class=\"heading\">
			<h2>Edit ".h(ucfirst($profile["continent_name"]))." ".h(ucfirst($profile["attribute_name"])).": ".h(ucfirst($profile["title"]))."</h2>
			</div>
			<div class=\"main\">
			<form action=\"cms_editItemSubmit.php?continent_id=".u($continent_id)."&continent_name=".h(u($profile["continent_name"]))."&attribute_id=".u($attribute_id)."&attribute_name=".h(u($profile["attribute_name"]))."&title=".h(u($title))."\" enctype=\"multipart/form-data\" method=\"post\">
			Page Number: Title: <input name=\"pg\" type=\"text\" value=\"".ucfirst($profile["pg"])."\"></input><br>
			Title: <input name=\"title\" type=\"text\" value=\"".ucfirst($profile["title"])."\"></input><br>
			Old Image: <p><img src=\"../".$profile["image_path"]."\" height=\"600\" width=\"800\"></p><br>
			New Image: <input accept=\"image/*\" id=\"image\" name=\"image\" type=\"file\"><br>
			Description: <textarea name=\"description\" rows=\"100\" cols=\"100\">".h(file_get_contents("../".$profile["description_path"].""))."</textarea><br>
			User Access: <select name=\"ux\">";
		if($profile["ux"]=="Hidden" || $profile["ux"]=="hidden"){

			echo "<option value=\"Hidden\" selected>Hidden</option>";


		}else{

			echo "<option value=\"Hidden\">Hidden</option>";

		}

		if($profile["ux"]=="Visible" || $profile["ux"]=="visible"){

			echo "<option value=\"Visible\" selected>Visible</option>";

		}else{
		
			echo "<option value=\"Visible\">Visible</option>";
		
		}
		
			echo "</select>
			<input name=\"submit\" type=\"submit\" value=\"Create\"></input>
			</form>";

	}

	$res_editItem->close(); 
	$mysqli->close();

}

//Edit item function
function editItem(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_library;
	global $cms_editAttribute;
	global $cms_continent;
	global $cms_attribute;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];
	$attribute_id=$_GET["attribute_id"];
	$attribute_name=$_GET["attribute_name"];
	$old_title=$_GET["title"];

	//Form handlers
	$title=$_POST["title"];
	if($_FILES["image"]["size"]>0){
	    
	    $image_path="".$continent_name."/".$attribute_name."/".$title."/".basename($_FILES["image"]["name"])."";   
	    
	    
	}
	$description=$_POST["description"];
	$ux=$_POST["ux"];
	$submit=$_POST["submit"];

	//Submit button trigger
	if(isset($submit)){

	    //To edit the title
	    $sql_oldTitle="SELECT * FROM items WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id." AND title='".$old_title."';";
	    $res_oldTitle=mysqli_query($mysqli, $sql_oldTitle);
	    while($newTitle=$res_oldTitle->fetch_assoc()){
	        
	        rename("../".$continent_name."/".$attribute_name."/".$old_title."", "../".$continent_name."/".$attribute_name."/".$title."");     
	        
	    }
	    
	    //Corresponding database entry
	    $sql_newTitle="UPDATE items SET title='".$title."' WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id." AND title='".$old_title."';";
	    $res_newTitle=mysqli_query($mysqli, $sql_newTitle);
	    
	    //To edit the page number
	    $sql_newPg="UPDATE items SET pg='".d($mysqli, $pg)."' WHERE continent_id=".d($mysqli, $continent_id)." AND attribute_id=".d($mysqli, $attribute_id)." AND title=".d($mysqli, $title).";";
	    $res_newPg=mysqli_query($mysqli, $sql_newPg);

		//To edit the image file
		if($_FILES["image"]["size"]>0){
		    
		    move_uploaded_file($_FILES["image"]["tmp_name"], "../".$image_path."");
		    
		    //Corresponding database entry
		    $sql_editImage="UPDATE items SET image_path='".$image_path."' WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id." AND title='".$title."';";
		    $res_editImage=mysqli_query($mysqli, $sql_editImage);
		    
		}
		
		//To edit the description file
		$description_path="".$continent_name."/".$attribute_name."/".$title."/description.txt";
		$editDescription=file_put_contents("../".$description_path."", $description);

		//Database entry for the UX
		$sql_editUx="UPDATE items SET ux='".$ux."' WHERE continent_id=".$continent_id." AND attribute_id=".$attribute_id." AND title='".$title."';";
		$res_editUx=mysqli_query($mysqli, $sql_editUx);
		
		if($res_newTitle || $res_editImage || $editDescription || $res_editUx){
		    
		    page_redirect("".$cms_attribute."?continent_id=".$continent_id."&continent_name=".$continent_name."&attribute_id=".$attribute_id."");    
		    
		}else{
		    
		    page_redirect("".$cms_editAttribute."?continent_id=".$continent_id."&continent_name=".$continent_name."&attribute_id=".$attribute_id."");   
		    
		}
		
		

	}else{
		page_redirect("".$cms_editAttribute."?continent_id=".$continent_id."&attribute_id=".$attribute_id."");

	}

	$res_addAttribute->close(); 
	$mysqli->close();

}

//Delete item form
function deleteItemForm(){

	//Database connection
	global $mysqli;
	
	//Selected continent, attribute, and item
	$continent_id=$_GET["continent_id"];
	$continent_name=$_GET["continent_name"];
	$attribute_id=$_GET["attribute_id"];
	$attribute_name=$_GET["attribute_name"];
	$title=$_GET["title"];

	//Corresponding database selection
	$sql_items="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." ";
	$sql_items.="AND attribute_id=".d($mysqli, $attribute_id)." AND title='".d($mysqli, $title)."';";
	$res_items=mysqli_query($mysqli, $sql_items);

	//List of associated files
	echo "<div class=\"heading\">
		<h2>You are about to delete ".h($continent_name)." ".h($attribute_name).": ".h($title)."</h2>
		<h3>The following item will be deleted:</h3><br>";


	if($res_items){

		while($list=$res_items->fetch_assoc()){

			echo "<p>".h($list["title"])."</p><br>
				<li>".h($list["image_path"])."</li><br>
				<li>".h($list["description_path"])."</li><br>";
 

		}

	}

	//Delete item form with required username and password input
	echo "</div><div class=\"main\">
		<form action=\"cms_deleteItemSubmit.php?continent_id=".u($continent_id)."&attribute_id=".u($attribute_id)."&title=".h(u($title))."\" method=\"post\">
	Are you sure you want to delete this?<br>	
	Username: <input name=\"username\" type=\"text\" value=\"\"></input><br>
	Password: <input name=\"password\" type=\"password\" value=\"\"></input><br>
	<input name=\"delete\" type=\"submit\" value=\"Delete\"></input>
	<input name=\"cancel\" type=\"submit\" value=\"Cancel\"></input>
	</form>";

    //Database disconnect
	$res_items->close();  
	$mysqli->close();

}

//Delete item function
function deleteItem(){

	//Database connection
	global $mysqli;
	
	//Hrefs
	global $cms_attribute;
	global $cms_deleteItem;

	//Selected continent and attribute
	$continent_id=$_GET["continent_id"];
	$attribute_id=$_GET["attribute_id"];
	$title=$_GET["title"];
	
	//Selection of the paths of the files to delete
	$sql_itemFiles="SELECT * FROM items WHERE continent_id=".d($mysqli, $continent_id)." ";
	$sql_itemFiles.="AND attribute_id=".d($mysqli, $attribute_id)." AND title='".d($mysqli, $title)."';";
	$res_itemFiles=mysqli_query($mysqli, $sql_itemFiles);

	//Deletion of the item files
	//if($res_itemFiles){

	//	while($itemFile=$res_itemFiles->fetch_assoc()){

	//		unlink("../".$itemFile["image_path"]."");
	//		unlink("../".$itemFile["description_path"]."");

	//	}

//	}

	//Deletion of the associated rows from the database
	$sql_deleteItem="DELETE FROM items WHERE continent_id=".d($mysqli, $continent_id)." ";
	$sql_deleteItem.="AND attribute_id=".d($mysqli, $attribute_id)." AND title='".d($mysqli, $title)."' LIMIT 1;";
	$res_deleteItem=mysqli_query($mysqli, $sql_deleteItem);

	if($res_deleteItem){

		page_redirect("".$cms_attribute."?continent_id=".$continent_id."&attribute_id=".$attribute_id."");

	}else{

		page_redirect("".$cms_deleteItem."?continent_id=".$continent_id."&attribute_id=".$attribute_id."");

	}

    //Database disconnect
	if($res_itemFiles){
	    
	    $res_itemFiles->close();    
	    
	}
	
	if($res_deleteItem){
	    
	    $res_deleteItem->close();    
	    
	}
	 
	$mysqli->close();

}

?>