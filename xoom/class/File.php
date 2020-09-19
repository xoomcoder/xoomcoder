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
        extract(Xoom::getConfig("adminName,adminLicence"));
        $dicoas = [
            "MyClass"   => $name,
            "@AUTHOR"   => $adminName       ?? "X",
            "@LICENCE"  => $adminLicence    ?? "MIT",
            "@DATETIME" => date("Y-m-d H:i:s"),
        ];

        $classCode = str_replace(array_keys($dicoas), array_values($dicoas), $code);
        File::create("xoom/class/$name.php", $classCode, false);
    }

    /**
     * warning: very dangerous
     */
    static function delete ($path, $read=false)
    {
        $content = "";
        $to = Xoom::$rootdir . "/$path";

        if ($read) {
            $content = file_get_contents($to);
        }

        unlink($to);
        return $content;

    }

    static function move ($path, $newpath, $read=false)
    {
        $content = "";
        $from   = Xoom::$rootdir . "/$path";
        $to     = Xoom::$rootdir . "/$newpath";

        if ($read) {
            $content = file_get_contents($to);
        }

        // https://www.php.net/manual/fr/function.rename.php
        rename($from, $to);
        return $content;

    }

    static function createDir ($dirname, $recursive=true)
    {
        $to = Xoom::$rootdir . "/$dirname";
        
        if (is_dir($to)) return;

        mkdir($to, 0777, $recursive);
    }

    /**
     * warning: very dangerous
     * folder must be empty first
     */
    static function deleteDir ($dirname)
    {
        $to = Xoom::$rootdir . "/$dirname";
        
        if (!is_dir($to)) return;

        rmdir($to);
    }

    /**
     * https://stackoverflow.com/questions/1017599/how-do-i-remove-accents-from-characters-in-a-php-string
     */
    static function removeAccents ($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;   // or add this : mb_strtoupper($str); for uppercase :)
    }

    static function getUpload ($tmp_name, $name)
    {
        $nameOK = File::removeAccents($name);
        $nameOK = strtolower(preg_replace("/[^a-zA-Z0-9\.]/", "-", $nameOK));
        $nameOK = preg_replace("/[-]{2+}/", "-", $nameOK);    
        // https://www.php.net/manual/fr/function.move-uploaded-file.php
        move_uploaded_file($tmp_name, Xoom::$rootdir . "/public/assets/media/" . $nameOK);

    }

    static function filterName ($name)
    {
        // $name has extension filename.ext

        $nameOK = trim($name);
        $nameOK = rawurldecode($nameOK);    // accents from URLs...
        $nameOK = File::removeAccents($nameOK);
        $nameOK = strtolower(preg_replace("/[^a-zA-Z0-9\.]/", "-", $nameOK));
        $nameOK = preg_replace("/[-]+/", "-", $nameOK);
        // FIXME: remove ending - in filename
        $nameOK = preg_replace("/(-\.)/", ".", $nameOK);
        $nameOK = trim($nameOK, "-");

        return $nameOK;
    }

    /**
     * danger: check security before deleting file
     */
    static function deleteMedia ($id)
    {
        extract(Xoom::getConfig("rootdir"));

        $searchBid = Response::id2name($id);
        $searchMedia = "$rootdir/xoom-data/media/*/my-*-$searchBid.*";
        $fileMedia = glob($searchMedia);
        foreach($fileMedia as $file) {
            unlink($file);
        }

    }

}