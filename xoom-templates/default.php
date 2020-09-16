<?php

$geocms = Response::$contents["dbline"] ?? []; 
extract($geocms);

include __DIR__ . "/header.php";

$article = News::buildHtml($geocms);       
echo 
<<<x
    <section class="single">
    $article
    </section>
x;

include __DIR__ . "/footer.php";

?>