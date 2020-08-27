<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
* {
    box-sizing: border-box;
    width:100%;
}
html, body {
    font-size:16px;
    padding:0;
    margin:0;
}

/* https://developer.mozilla.org/fr/docs/Web/CSS/white-space */
pre {
    margin-top:8vmax;
    padding:2rem;
    max-width:30%;
    white-space:pre-line;
}    
.logo {
    position:fixed;
    left:1rem;
    top:1rem;
    width:5vmax;
    height:5vmax;
    object-fit:contain;
    z-index:999;
}
#lightbox {
    opacity:1;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:99;
}
.finish {
    opacity:0 !important;
    transition:2s all ease-in;
}

    </style>
</head>
<body>
    <div id="lightbox">
        <video id="jingle" src="assets/video/xoomcoder-jump5.mp4"></video>
    </div>
    <img id="logo" class="logo" src="assets/img/xoomcoder.svg" alt="zoomcoder">
    <pre>
<?php 

$markfile = "my-active.md";

$content = file_get_contents("../doc/$markfile");

$taDico = [
    "###" => "\n🔊🔊🔊",
    "##" => "\n🔊🔊",
    "#"  => "\n🔊",
    "```php"    => '<textarea cols="80" rows="10">',
    "```"    => "</textarea>",
];

$content = str_replace(array_keys($taDico), array_values($taDico), $content);

echo $content;

?>    
    </pre> 
    
    <script type="module">
function addAction(selector, eventname, callback)
{
    var list = document.querySelectorAll(selector);
    for(var l=0; l < list.length; l++) {
        var el = list[l];
        el.addEventListener(eventname, callback);
    }    
}
addAction("#logo", "click", function(){
    lightbox.classList.remove("finish");
    jingle.play();    
});

addAction("#jingle", "ended", function(){
    console.log("the end")
    lightbox.classList.add("finish");
    var toolbar = document.querySelector("iframe");
    if (toolbar) {
        console.log(toolbar);
        var elmnt = toolbar.contentWindow.document.getElementsByTagName("button")[0];
        console.log(toolbar.contentWindow.document);
        console.log(elmnt);
        if (elmnt) elmnt.click();

        var read = toolbar.contentWindow.document.querySelector("#bar-read");
        console.log(read);
        if (read) read.click();
    }
});
    </script>
</body>
</html>