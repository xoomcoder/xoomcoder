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

    static function sendStatic ()
    {
        extract(Xoom::getConfig("rootdir"));

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

    static function send404 ()
    {
        // https://www.php.net/manual/fr/function.header.php
        header("HTTP/1.1 404 Not Found");
        echo "Erreur 404: Page non trouvée " .Request::$path;
    }

    //@end
}