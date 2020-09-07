<?php

class News {

    // static methods
    static function show ()
    {
        // FIXME: PUT DIR IN CONFIG
        $articles = glob(Xoom::$rootdir . "/../xoomcoder-website/news/*.php");
        // https://www.php.net/manual/fr/function.array-reverse
        // get the last articles first
        $articles = array_reverse($articles);
        foreach($articles as $article)
        {
            include $article;
        }
    }
}