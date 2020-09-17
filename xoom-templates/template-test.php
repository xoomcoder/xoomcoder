<?php

$geocms = Response::$contents["dbline"] ?? []; 
extract($geocms);

include __DIR__ . "/header.php";

$article = News::buildHtml($geocms, false); 
$debug = json_encode($geocms, JSON_PRETTY_PRINT);

echo 
<<<x
    <section class="single">
    <h1>TEST</h1>
    <pre>
    $debug
    </pre>
    $article
    </section>
x;

include __DIR__ . "/footer.php";

?>