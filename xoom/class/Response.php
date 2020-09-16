<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-11 13:53:30
 * licence: MIT
 */

class Response
{
    static $contents = [];

    static function findTemplate()
    {
        // if there's a page
        extract(Xoom::getConfig("rootdir,contentdir,filename,bid"));
        $pages = [
            "$contentdir/templates/pages/$filename.php",
            "$rootdir/xoom-pages/$filename.php",
        ];
        $foundpage = false;
        foreach($pages as $pagefile) {
            if (is_file($pagefile)) {
                // special template
                include $pagefile;
                $foundpage = true;
                break; // only the first
            }            
        }
        if (!$foundpage) {
            extract(Xoom::getConfig("rootdir,contentdir"));
            // FIXME: add folders in config files
            $contents = [
                "$contentdir/templates/content/$filename.php",  
                "$rootdir/xoom-templates/contenu-$filename.php",
            ];
            foreach($contents as $contentfile) {
                if (is_file($contentfile)) {
                    Xoom::$template = [ 
                        "$rootdir/xoom-templates/header.php", 
                        $contentfile, 
                        "$rootdir/xoom-templates/footer.php", 
                    ];
                    $foundpage = true;
                    break; // only the first
                }
            }
        }
        if (!$foundpage) {
            if ($bid != "") {
                $geocms_id = Response::name2id($bid);
                $lines = Model::read("geocms", "id", $geocms_id);
            }
            else {
                $lines = Model::read("geocms", "uri", $filename);
            }
            foreach($lines as $line) {
                Response::$contents["dbline"] = $line;

                Xoom::$template = [ 
                    "$rootdir/xoom-templates/default.php", 
                ];
        }
        }
    }

    static function name2id ($bid) 
    {
        $res = 0;
        $letters =[
            "bcdfghjklmnpqrstvxz",
            "aeiou",
        ];
        $step   = 0;
        $factor = 1;
        foreach(str_split($bid) as $letter) {
            $pos = strpos($letters[$step], $letter);
            if ($pos !== false) {
                $res += $pos * $factor;
            }
            $step = ($step + 1) % 2;
            if ($step%2 == 1)
                $factor = $factor * 20;
            else
                $factor = $factor * 5;
        }
        return $res;
    }


    static function id2name ($id)
    {
        $res = "";
        $current = intval($id);
        $consons = str_split("bcdfghjklmnpqrstvxz");
        $voyels = str_split("aeiou");
        while($current > 0) {
            $mod = $current % 100;
            $c = $mod % 20;
            $conson = $consons[$c];
            $voyel  = $voyels[($mod - $c) / 20];

            $res .= "$conson$voyel";

            $current = ($current - $mod) / 100 ;
        }
        return $res;
    }

    static function send ()
    {
        if (Request::$extension == "html") {
            Response::findTemplate();
            // build the page to the browser
            Xoom::sendResponse();        
        }
        elseif (Request::$extension == "vjs") {
            header("Content-Type: application/javascript");

            Response::findTemplate();
            // build the page to the browser
            Xoom::sendResponse();        
        }
        elseif (is_file(Xoom::$rootdir . "/public" .Request::$path)) {
            // FIXME: better code to manage local mode
            // local mode where php is main router
            $file = Xoom::$rootdir . "/public" . Request::$path;
            // https://www.php.net/manual/fr/function.mime-content-type.php
            $mimes = [
                "js"    => "application/javascript",
                "json"  => "application/json",
                "css"   => "text/css",
                "svg"   => "image/svg+xml",
            ];
            $mimetype = $mimes[Request::$extension] ?? mime_content_type($file);

            // router can help fix some bad urls 
            // but don't index bad urls
            header("X-Robots-Tag: noindex");

            header("Content-Type: $mimetype");
            readfile($file);
        }
        elseif (Request::$extension == "jpg") {
            $file = News::getPhotos(Request::$filename);
            if (is_file($file)) {
                header("Content-Type: image/jpeg");
                readfile($file);
            }
        }
        else {
            // https://www.php.net/manual/fr/function.header.php
            header("HTTP/1.1 404 Not Found");
            echo Request::$path;
        }

    }

    static function Htmlheader ()
    {
        $canonical = Xoom::$canonical;
        $uri = "https://xoomcoder.com/$canonical";        

        $geocms = Response::$contents["dbline"] ?? []; 
        if (!empty($geocms)) {
            extract($geocms);
            $bid    = Response::id2name($id);

            $res = 
            <<<x
            <meta name="description" content="$title - XoomCoder - Formation Développeur Fullstack à Distance">
            <title>$title - XoomCoder Formation</title>
            <link rel="canonical" href="$uri--$bid">
            x;
    
        }
        else {
            if ($canonical == "index") $canonical = "Accueil";
            $res = 
            <<<x
            <meta name="description" content="$canonical - XoomCoder - Formation Développeur Fullstack à Distance">
            <title>$canonical - XoomCoder * Formation Développeur Fullstack à Distance</title>
            <link rel="canonical" href="$uri">
            x;
        }

        echo $res;
    }
    //@end
}