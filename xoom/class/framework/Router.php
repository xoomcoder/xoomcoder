<?php
/**
 * author:  Long Hai LH
 * date:    2020-10-08 21:42:25
 * licence: MIT
 */

class Router
{
    static function build ()
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
    
    //@end
}