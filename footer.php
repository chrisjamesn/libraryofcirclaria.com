<?php

require_once("pages.php");

require_once($functions);

//db_close($res, $mysqli);

$c=date('Y');

require_once("donate.php");

echo "<br></div>
<div class=\"footer\">
<p>***All names of characters, places, and things are entirely ficticious and purely coincidental!***</p>
<p>Background: http://api.thumbr.it/whitenoise-361x370.png?background=c7af4dff&noise=996214&density=26&opacity=47.<br>Courtesy of: <a href=\"https://www.cssmatic.com/noise-texture\">https://www.cssmatic.com/noise-texture</a></p>
<p>&copy; Christopher J Nutter, ".$c."</p>
<p><a href=\"".$admin."\">Admin</a></p>
</div>
</body>
</html>";

?>