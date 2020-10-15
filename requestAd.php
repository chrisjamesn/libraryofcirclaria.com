<?php

require_once("functions.php");

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

$request=$_POST["request"];

$adNew=$_POST["adNew"];
$type=$_POST["type"];
$linkNew=$_POST["linkNew"];
$bus_nameNew=$_POST["bus_nameNew"];
$bus_descriptionNew=$_POST["bus_descriptionNew"];
$contact_infoNew=$_POST["contact_infoNew"];

if(isset($request)){
    
    $ad_path="ads/".basename($_FILES["adNew"]["name"])."";
   move_uploaded_file($_FILES["adNew"]["tmp_name"], $ad_path);
   
   $sql_request="INSERT INTO requests(ad, type, link, bus_name, bus_description, contact_info) VALUES('".$ad_path."', '".$type."', '".$linkNew."', '".$bus_nameNew."', '".$bus_descriptionNew."', '".$contact_infoNew."');";
   $res_request=mysqli_query($mysqli, $sql_ad);
   
   if($res_request){
       
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
       
       $msg="<html><p>You have received a request from the following contact: </p>";
       $msg.="<p>".$bus_nameNew."</p>";
       $msg.="<p>Please go to your requests page to respond: ";
       $msg.="<a href=\"http://www.thechrisnutterhub.com/requests.php\">Requests Page</a></p></html>";
       
       mail("chrisnttr@yahoo.com", "Ad Request From: ".$bus_nameNew."", $msg, $headers);
       
   }
    
}

?>