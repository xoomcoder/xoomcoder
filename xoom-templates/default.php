<?php

include __DIR__ . "/header.php";

extract(Response::$contents["dbline"] ?? []);
$section = News::buildHtml($code);       
echo $section;

include __DIR__ . "/footer.php";

?>