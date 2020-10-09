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

    static function send ()
    {
        extract(Xoom::getConfig("rootdir"));

        if (Request::$extension == "html") {
            Response::findTemplate();
            // build the page to the browser
            Cms::sendResponse();        
        }
        elseif (Request::$extension == "vjs") {
            header("Content-Type: application/javascript");

            Response::findTemplate();
            // build the page to the browser
            Cms::sendResponse();        
        }
        elseif (Request::$bid != "") {
            $searchBid = Request::$bid;
            $searchMedia = "$rootdir/xoom-data/media/*/my-*-$searchBid.*";
            $fileMedia = glob($searchMedia);
            $status = false;
            if (count($fileMedia) > 0) {
                $searchId = Response::name2id($searchBid);
                $lines = Model::read("geocms", "id", $searchId);
                foreach($lines as $line) {
                    extract($line);
                    // $status

                    // get b64
                    $b64 = Form::filterText("b64");
                    if ($datePublication == trim(base64_decode($b64))) $status = "public";
                }
            }
            if ($status == "public") {
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


    //@end
}