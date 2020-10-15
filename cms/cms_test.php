<?php

require_once("cms_uNpW.php");
	
session_start();

$inputUsername=$_POST["username"];
$inputPassword=$_POST["password"];
$submit=$_POST["submit"];
	
if(isset($submit)){
    if($inputUsername==$username && $inputPassword==$password){

			$_SESSION["username"]=$inputUsername;
			$_SESSION["password"]=$inputPassword;
			
			echo $_SESSION["username"];
			echo $_SESSION["password"];
    }
}else{
    echo "no";
}	

?>