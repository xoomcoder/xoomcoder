<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-11 13:53:30
 * licence: MIT
 */

class Response
{
    static function send ()
    {
        if (Request::$extension == "html") {
            Request::findTemplate();
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
            echo Xoom::$rootdir . "/public". Request::$path;
        }

    }
    //@end
}