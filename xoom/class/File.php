<?php

class File
{
    static function create ($filename, $content)
    {
        $to = Xoom::$rootdir . "/$filename";
        file_put_contents($to, $content);
    }

    static function list ($search)
    {
        $files = glob(Xoom::$rootdir . "/$search");
        return $files;
    }
}