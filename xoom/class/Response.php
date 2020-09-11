<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-11 13:53:30
 * licence: MIT
 */

class Response
{
    static function findTemplate()
    {
        // if there's a page
        extract(Xoom::getConfig("rootdir,contentdir,filename"));
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
                    break; // only the first
                }
            }
        }

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
    //@end
}