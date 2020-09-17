<?php

// @bloc php

extract(Xoom::getConfig("rootdir"));

$geocms = Response::$contents["dbline"] ?? []; 
extract($geocms);
//print_r($geocms);
include "$rootdir/xoom-templates/header.php";

$article = News::buildHtml($geocms, false);       
echo 
<<<x

<section>
    <h2>AVANT</h2>
</section>
    
<section class="single">
    $article
</section>

<section>
    <h2>APRES</h2>
</section>

x;

include "$rootdir/xoom-templates/footer.php";

// @bloc

// PhpEval?bloc=php&echo=true

?>