<?php

include __DIR__ . "/header.php";

$geocms = Response::$contents["dbline"] ?? []; 
extract($geocms);
$section = News::buildHtml($geocms);       
echo $section;

include __DIR__ . "/footer.php";

?>