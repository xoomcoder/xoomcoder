<?php

class News {

    // static methods
    static function show ()
    {

        $articles = glob(Xoom::$rootdir . "/xoom-news/*.php");
        // https://www.php.net/manual/fr/function.array-reverse
        // get the last articles first
        $articles = array_reverse($articles);
        foreach($articles as $article)
        {
            include $article;
        }
    }
}