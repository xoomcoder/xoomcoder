<?php

class File
{
    static function create ($filename, $content, $overwrite=true)
    {
        $to = Xoom::$rootdir . "/$filename";
        
        if (!$overwrite && is_file($to)) return;

        file_put_contents($to, $content);
    }

    static function list ($search)
    {
        $files = glob(Xoom::$rootdir . "/$search");
        return $files;
    }

    static function content ($path)
    {
        $content = file_get_contents(Xoom::$rootdir . "/$path");
        return $content;
    }

    static function buildClass ($name)
    {
        $code = File::content("xoom/etc/ex-Class.php");
        $dicoas = [
            "MyClass"   => $name,
            "@AUTHOR"   => Config::$adminName       ?? "X",
            "@LICENCE"  => Config::$adminLicence    ?? "MIT",
            "@DATETIME" => date("Y-m-d H:i:s"),
        ];

        $classCode = str_replace(array_keys($dicoas), array_values($dicoas), $code);
        File::create("xoom/class/$name.php", $classCode, false);
    }
}