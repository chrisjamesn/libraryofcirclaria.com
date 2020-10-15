<?php

require_once("functions.php");

require_once("header.php");

echo "<h3>Ad Request Form</h3>";

echo "<form action=\"requestAd.php\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Ad Image: <input accept=\"image/*\" id=\"adNew\" name=\"adNew\" type=\"file\"><br>";
echo "<label for=\"type\">Advertiser Type:</label>
        <select id=\"type\" name=\"type\">
        <option value=\"personal\">Personal</option>
        <option value=\"business\">Business</option>
        </select>Please make a selection.<br>";
echo "Link: <input id=\"linkNew\" name=\"linkNew\"type=\"text\" value=\"\">You may type \"n/a\" if you have no webpage, and I will create a default profile for you on my page.<br>";
echo "Name/Business: <input id=\"bus_nameNew\" name=\"bus_nameNew\"type=\"text\" value=\"\"><br>";
echo "Personal/Business Description: <input id=\"bus_descriptionNew\" name=\"bus_descriptionNew\"type=\"text\" value=\"\"><br>";
echo "Contact Information: <input id=\"contact_infoNew\" name=\"contact_infoNew\"type=\"text\" value=\"\">Please provide a phone number or email.<br>";
echo "<input name=\"request\" type=\"submit\" value=\"Request\">";
echo "</form>";

require_once("footer.php");

?>