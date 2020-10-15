<?php

session_start();

require_once("pages.php");

require_once("functions.php");

db_open();

echo "<html>
<head>
<title>Library of Circlaria</title>
<meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width\">
<meta property=\"og:title\" content=\"Library of Circlaria\">
<meta property=\"og:description\" content=\"All stories, timelines, biographies, and other artifacts associated with the world of Circlaria\">
<meta property=\"og:image\" content=\"http://www.thechrisnutterhub.com/libraryofcirclaria.com/remikra/Remikra%20Standard.jpg\">
<meta property=\"og:url\" content=\"http://www.thechrisnutterhub.com/libraryofcirclaria.com/index.php\">
<meta name=\"twitter:card\" content=\"summary_large_image\">
<link rel=\"stylesheet\" media=\"all\" href=\"styles.css\"/>
</head>
<body>";

require_once("ad.php");

echo "<div class=\"header\">
    <h1><a href=\"".$index."\" title=\"Library of Circlaria\">Library of Circlaria</a></h1>";
    
echo "</div>";
    



    


?>