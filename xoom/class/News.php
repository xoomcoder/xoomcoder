<?php

class News 
{
    static function getPhotos ($tag, $debug=false)
    {
        ob_start();

        $mediafile = Xoom::$rootdir . "/public/assets/square/$tag.jpg";
        if (is_file($mediafile)) return;

        $api    =  Config::$mediaAPI ?? "";
        $url0   = Config::$mediaURL ?? "";
        if ($api && $url0) {
            $options = array('http' => array(
                'method'  => 'GET',
                'header' => "Authorization: $api",
            ));
            // https://www.php.net/manual/fr/function.http-build-query.php
            $query= http_build_query([
                "per_page" => 10, 
                "query" => $tag,
                ]);
            $url = "$url0?$query"; 
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            if ($response) {
                $found = json_decode($response, true);
                $photos = $found["photos"] ?? [];
                $selects    = [];
                foreach($photos as $p) {
                    $selects[] = $p["src"]["original"];
                }

                // get one

                $choice =
                    $selects[rand(0, count($selects) -1)] 
                    ."?auto=compress&cs=tinysrgb&dpr=1&fit=crop&w=640&h=640";
                $content = file_get_contents($choice);
                if ($content) {
                    file_put_contents($mediafile, $content);
                }
            }
        }
        $log = ob_get_clean();
        if ($debug) echo $log;
    }

    // static methods
    static function showBloc ()
    {
        $articles = glob(Xoom::$rootdir . "/../xoomcoder-website/markdown/article-*.md");
        usort($articles, function ($a, $b) {
            // https://www.php.net/manual/fr/function.filemtime
            return filemtime($a) < filemtime($b);
        });
        $html       = "";
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