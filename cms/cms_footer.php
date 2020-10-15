<?php

require_once("cms_functions.php");

//db_close($res, $mysqli);

$c=date('Y');

echo "</div>
<div class=\"footer\">
<p>&copy; Christopher J Nutter, ".$c."</p>
<p><a href=\"".$cms_logoutSubmit."\">Logout</a></p>
</div>
</body>
</html>";

?>