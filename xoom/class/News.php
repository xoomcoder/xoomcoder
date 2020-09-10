<?php

class News {

    // static methods
    static function showBloc ()
    {
        $articles = glob(Xoom::$rootdir . "/../xoomcoder-website/markdown/article-*.md");
        $html = "";
        foreach($articles as $article) {
            extract(pathinfo($article));
            extract(View::showBlocMD($filename));
            // $result and $meta
            $class = $meta["class"] ?? "";

            $html .= 
            <<<x
            <article class="$class">
                $result
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