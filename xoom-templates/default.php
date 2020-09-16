<?php

include __DIR__ . "/header.php";

$geocms = Response::$contents["dbline"] ?? []; 
extract($geocms);
$article = News::buildHtml($geocms);       
echo 
<<<x
    <section>
    $article
    </section>
x;

include __DIR__ . "/footer.php";

?>