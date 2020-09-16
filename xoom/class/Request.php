<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-11 13:12:23
 * licence: MIT
 */

class Request
{
    static $path      = "";
    static $filename  = "";
    static $extension = "html";
    static $bid  = "";

    static function parse()
    {
        $uri = $_SERVER["REQUEST_URI"];
        // https://www.php.net/manual/fr/function.parse-url.php
        // https://www.php.net/manual/fr/function.extract.php
        extract(parse_url($uri));
        // https://www.php.net/manual/fr/function.pathinfo.php
        extract(pathinfo($path));

        // https://www.php.net/manual/fr/function.in-array
        if (in_array($filename, ["/", ""])) $filename = "index";

        // parse url to extract -- suffix
        // https://www.php.net/manual/fr/function.explode.php
        $urlparts           = explode("--", $filename);
        $filename           = $urlparts[0];
        Request::$bid       = $urlparts[1] ?? "";

        // SEO: help google against duplicate content
        Xoom::$canonical = ($filename == "index") ? "" : $filename;

        // store filename
        Xoom::$filename = $filename;

        Request::$path      = $path;
        Request::$filename  = $filename;
        Request::$extension = $extension ?? "html";
        
        Xoom::setConfig("path",         $path);
        Xoom::setConfig("filename",     $filename);
        Xoom::setConfig("extension",    Request::$extension);
        Xoom::setConfig("bid",          Request::$bid);
    }

    //@end
}