<?php

class News {

    // static methods
    static function showBloc ()
    {
        $articles = glob(Xoom::$rootdir . "/../xoomcoder-website/markdown/article-*.md");
        usort($articles, function ($a, $b) {
            // https://www.php.net/manual/fr/function.filemtime
            return filemtime($a) < filemtime($b);
        });
        $html = "";
        foreach($articles as $article) {
            extract(pathinfo($article));
            extract(View::showBlocMD($filename));
            // $result and $meta
            $class = $meta["class"] ?? "";

            $time = date("d/m/Y", filemtime($article));
            $html .= 
            <<<x
            <article class="$class">
                $result
                <small class="date">publié le: $time</small>
            </article>
            x;
        }

        echo $html;
    }

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