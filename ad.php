<?php

$servername="";
$dbname="";
$dbusername=""; 
$dbpassword=""; 
    
$mysqli_ad= new mysqli($servername, $dbusername, $dbpassword, $dbname);

if($mysqli_ad->connect_errno){
	echo "Failed to connect to the database: (". $mysqli_ad->connect_errno. ")" . $mysqli_ad->connect_error;
	mysqli_close($mysqli_ad);
	exit;
}

$sql_slideshow="SELECT * FROM ads ORDER BY ad_id;";
$res_slideshow=mysqli_query($mysqli_ad, $sql_slideshow);


	echo "<div class=\"ad\">";

    if($res_slideshow){

	        echo "<div class=\"slideshow-container\">";

	        while($slide=$res_slideshow->fetch_assoc()){

		        echo "<div class=\"mySlides\">
		                <a href=\"../".$slide["link"]."\"><img src=\"../".$slide["ad"]."\" style=\"length:100%;width:100%\"></a>
		                <a href=\"http://www.thechrisnutterhub.com/table.php\">See all ads here</a>
		                 </div>";

	        }

	    echo "</div>
		    <script>
		    var slideIndex=0;

		    showSlides();

		    function showSlides(){

			    var i;
			    var slides=document.getElementsByClassName('mySlides');

			    for(i=0; i<slides.length; i++){
				    slides[i].style.display = \"none\";
			    }

			    slideIndex++;

			    if(slideIndex>slides.length){
		
				    slideIndex=1

			    }

			    slides[slideIndex-1].style.display=\"block\";

			    setTimeout(showSlides, 5000); 

		    }
		    </script>";

    }else{

	    echo "<div class=\"container2\">
		    <p>Database query error occurred.</p>
		    </div>";

    }

	echo "</div><br>";

mysqli_close($mysqli_ad);

?>