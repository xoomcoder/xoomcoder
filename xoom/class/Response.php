<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-11 13:53:30
 * licence: MIT
 */

class Response
{
    static $contents = [];
    static $template = [];

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
                Response::$template = [ 
                    "$pagefile", 
                ];
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
                    Response::$template = [ 
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
            // WARNING: SHOULD FILTER MEMBER CONTENT
            if ($bid != "") {
                $geocms_id = Response::name2id($bid);
                $lines = Model::read("geocms", "id", $geocms_id, "priority DESC");
            }
            else {
                $lines = Model::read("geocms", "uri", $filename, "priority DESC");
            }
            foreach($lines as $line) {
                Response::$contents["dbline"] = $line;

                extract($line);
                // $template
                if ($template ?? false) {
                    // add some security
                    $template = pathinfo($template, PATHINFO_FILENAME);
                }
                else {
                    $template = "default";
                }

                Response::$template = [ 
                    "$rootdir/xoom-templates/template-$template.php", 
                ];
                break;  // only one
            }
        }
    }

    static function name2id ($bid) 
    {
        $res = 0;
        $letters =[
            "bcdfghjklmnpqrstvwxz",
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
        $consons = str_split("bcdfghjklmnpqrstvwxz");
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

    static function sendResponse()
    {
        // https://www.php.net/manual/fr/control-structures.foreach.php
        foreach (Response::$template as $file) {
            if (is_file($file)) {
                // https://www.php.net/manual/fr/function.require-once.php
                include $file;
            }
            else {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                // look in geocms if there's a template
                // $lines = Model::read("geocms", "template", $filename, "priority DESC");
                $sql =
                <<<x
                SELECT * FROM geocms
                WHERE
                template LIKE :template
                AND priority >= :priority
                ORDER BY priority DESC
                x;
                $tag = str_replace("template-", "", $filename);
                $tokens = [
                    "template" => "template%-$tag%",
                    "priority" => 100,
                ];

                $lines = [];
                $pdoStatement = Model::sendSql($sql, $tokens);
                if ($pdoStatement) $lines = $pdoStatement->fetchAll();
                //$pdoStatement->debugDumpParams();

                foreach($lines as $line) {
                    extract($line);
                    $priority = intval($priority ?? 0);
                    if ($priority >= 100) {             // security: template are built by webmasters
                        // warning: this is running PHP code stored in database...
                        AdminCommand::runLocal($code);
                    }
                }

                if(empty($line)) {
                    // no template found...
                }
            }
        }
    }

    static function send ()
    {
        extract(Xoom::getConfig("rootdir"));

        if (Request::$extension == "html") {
            Response::findTemplate();
            // build the page to the browser
            Response::sendResponse();        
        }
        elseif (Request::$extension == "vjs") {
            header("Content-Type: application/javascript");

            Response::findTemplate();
            // build the page to the browser
            Response::sendResponse();        
        }
        elseif (Request::$bid != "") {
            $searchBid = Request::$bid;
            $searchMedia = "$rootdir/xoom-data/media/*/my-*-$searchBid.*";
            $fileMedia = glob($searchMedia);
            foreach($fileMedia as $file) {
                $mimetype = Response::getMime($file);
                extract(pathinfo($file));

                header("X-Robots-Tag: noindex");
                // $ext = ($extension ?? false) ? ".$extension" : "";
                // header("Link: <https://xoomcoder.com/--$searchBid$ext" . '>; rel="canonical"');
                header("Content-Type: $mimetype");
                readfile($file);
            }
        }
        elseif (is_file("$rootdir/public" .Request::$path)) {
            // FIXME: better code to manage local mode
            // local mode where php is main router
            $file = "$rootdir/public" . Request::$path;
            // https://www.php.net/manual/fr/function.mime-content-type.php
            $mimetype = Response::getMime($file);

            // router can help fix some bad urls 
            // but don't index bad urls
            header("X-Robots-Tag: noindex");

            header("Content-Type: $mimetype");

            readfile($file);
        }
        elseif (Request::$extension == "jpg") {
            if (Request::$bid == "") {
                $file = News::getPhotos(Request::$filename);
                if (is_file($file)) {
                    header("Content-Type: image/jpeg");
                    readfile($file);
                }    
            }
        }
        else {
            // https://www.php.net/manual/fr/function.header.php
            header("HTTP/1.1 404 Not Found");
            echo "Erreur 404: Page non trouvée " .Request::$path;
        }

    }

    static function getMime ($file)
    {
        $mimes = [
            "js"    => "application/javascript",
            "json"  => "application/json",
            "css"   => "text/css",
            "svg"   => "image/svg+xml",
        ];
        $mimetype = $mimes[Request::$extension] ?? mime_content_type($file);
        return $mimetype;
    }

    static function Htmlheader ()
    {
        $canonical = Xoom::$canonical;
        $uri = "https://xoomcoder.com/$canonical";        

        $geocms = Response::$contents["dbline"] ?? []; 
        if (!empty($geocms)) {
            extract($geocms);
            $bid    = Response::id2name($id);

            $noindex = '<meta name="robots" content="index">';
            $codelength = mb_strlen($code);
            if ($codelength > 2000) {
                // $noindex = "";  // index content as big enough
            }

            $res = 
            <<<x
            $noindex

            <meta property="og:title" content="$title">
            <meta property="og:description" content="$title - XoomCoder - Formation Développeur Fullstack à Distance">
            <meta property="og:url" content="https://xoomcoder.com/$uri--$bid">
            <meta property="og:image" content="https://xoomcoder.com/$uri.jpg">
            <meta property="og:image:alt" content="$title">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="XoomCoder">

            <meta name="description" content="$title - XoomCoder - Formation Développeur Fullstack à Distance">
            <title>$title - XoomCoder Formation</title>
            <link rel="canonical" href="https://xoomcoder.com/$uri--$bid">
            x;
    
        }
        else {
            if ($canonical == "") $canonical = "Accueil";

            $title = $canonical;

            $res = 
            <<<x

            <meta property="og:title" content="$title">
            <meta property="og:description" content="$title - XoomCoder - Formation Développeur Fullstack à Distance">
            <meta property="og:url" content="$uri">
            <meta property="og:image" content="https://xoomcoder.com/$uri.jpg">
            <meta property="og:image:alt" content="$title">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="XoomCoder">

            <meta name="description" content="$canonical - XoomCoder - Formation Développeur Fullstack à Distance">
            <title>$canonical - XoomCoder * Formation Développeur Fullstack à Distance</title>
            <link rel="canonical" href="$uri">
            x;
        }

        echo $res;
    }


    //@end
}