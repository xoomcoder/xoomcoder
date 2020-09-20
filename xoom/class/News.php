<?php

class News 
{
    static function getPhotos ($tag, $debug=false)
    {
        ob_start();
        $tag = File::filterName($tag);
        $mediafile = Xoom::$rootdir . "/public/assets/square/$tag.jpg";
        if (is_file($mediafile)) return $mediafile;

        $api    =  Config::$mediaAPI ?? "";
        $url0   = Config::$mediaURL ?? "";
        if ($api && $url0) {
            $options = array('http' => array(
                'method'  => 'GET',
                'header' => "Authorization: $api",
            ));
            // https://www.php.net/manual/fr/function.http-build-query.php
            $query= http_build_query([
                "per_page" => 100, 
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

        return $mediafile;
    }

    static function showCms ()
    {
        $users = Model::read("user", "level", 100);
        foreach($users as $user) {
            extract($user, EXTR_PREFIX_ALL, "user");
            // $notes = Model::read("geocms", "id_user", $user_id);
            $sql =
            <<<x
            SELECT * FROM geocms
            WHERE 
            id_user = :id_user
            AND 
            category = :category
            ORDER BY datePublication DESC

            x;

            $tokens = [
                "id_user"   => $user_id,
                "category"  => "news",
            ];
            $notes = Model::sendSql($sql, $tokens);

            $html = "";
            foreach($notes as $note) {
                $html .= News::buildHtml($note);       
            }
            echo $html;

        }
            
    }

    static function buildHtml ($geocms, $summary=true)
    {
        extract($geocms);
        // $code
        $codelength = mb_strlen($code);
        $bid    = Response::id2name($id);
        $seouri = File::filterName($title);
        
        $html = "";
        extract(View::parseBlocMD($code));
        // $result and $meta
        // custom html upgrade
        $filters = [
            "<img " => '<img loading="lazy" ',
            "<a "   => '<a rel="nofollow" ',
            "<pre><code "   => '<pre class="xcode"><code  ',
            "<h2>"   => '<h2><a href="/' . "$seouri--$bid" .'">',
            "</h2>"   => '</a></h2>',
        ];
        $class = $meta["class"] ?? "";

        if ($codelength < 2000) {
            // save seo crawl time and duplicate content
            $filters["<h2>"]  = '<h2><a href="/' . "$seouri--$bid" .'" rel="nofollow">';
        }
        $result = str_replace(array_keys($filters), array_values($filters), $result);

        if ($summary && ($codelength >= 2000)) {
            // make summary to avoid duplicate content
            $result = preg_replace("/<h2>.*</h2>/", "", $result, 1);

            //$titlelength = mb_strlen($title);
            $result = strip_tags($result);
            // remove title
            //$result = trim(substr($result, $titlelength, 1000));

            $result = 
            <<<x
            <h2><a href="/$seouri--$bid">$title</a></h2>
            <p>$result... <a href="/$seouri--$bid">lire la suite</a></p> 
            
            x;

            $class .= " summary";
        }

        // add cover image
        $cover  = $meta["cover"] ?? "";
        if ($cover) {
            // FIXME
            // p tags is to conform with markdown...
            $result = 
            <<<x
            <p><img src="/assets/square/$cover.jpg" alt="$cover cover"></p>
            $result
            x;
        }

        $time = date("d/m/Y", strtotime($datePublication));

        $html  .= 
        <<<x
        <article class="id-$id bid-$bid $class">
            $result
            <small class="date">publié le: $time</small>
        </article>
        x;
        
        return $html;
    }

    static function showBloc ()
    {
        extract(Xoom::getConfig("contentdir"));
        $articles = glob("$contentdir/markdown/article-*.md");
        usort($articles, function ($a, $b) {
            // https://www.php.net/manual/fr/function.filemtime
            return filemtime($a) < filemtime($b);
        });
        $html       = "";
        foreach($articles as $article) {
            extract(pathinfo($article));
            extract(View::showBlocMD($filename));
            // $result and $meta
            // custom html upgrade
            $filters = [
                "<img " => '<img loading="lazy" ',
                "<a "   => '<a rel="nofollow" ',
                "<pre><code "   => '<pre class="xcode"><code  ',
            ];
            $result = str_replace(array_keys($filters), array_values($filters), $result);
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
        extract(Xoom::getConfig("contentdir"));
        $articles = glob("$contentdir/news/*.php");
        // https://www.php.net/manual/fr/function.array-reverse
        // get the last articles first
        $articles = array_reverse($articles);
        foreach($articles as $article)
        {
            ob_start();
            include $article;
            $content = ob_get_clean();
            // add timestamp
            $time = date("d/m/Y", filemtime($article));
            $rep =
            <<<x
                <small class="date">mis à jour le: $time</small>
            </article>
            x;
            $content = str_replace("</article>", $rep, $content);
            echo $content;
        }
    }
}