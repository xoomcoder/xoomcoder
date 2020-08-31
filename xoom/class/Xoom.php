<?php

class Xoom
{
    // static properties
    static $rootdir     = "";
    static $template    = [];
    static $filename    = "";
    static $canonical   = "";
    static $configas    = [];

    // static methods

    static function start($rootdir)
    {
        error_reporting(E_ALL);
        ini_set("error_display", "1");

        // store the root dir for all project
        Xoom::$rootdir = $rootdir;

        // https://www.php.net/manual/fr/function.spl-autoload-register.php
        spl_autoload_register("Xoom::loadClass");

        Xoom::$configas["rootdir"] = Xoom::$rootdir;

        Xoom::loadConfig();

        Xoom::getRequest();

        // build the page to the browser
        Xoom::sendResponse();
    }

    static function loadClass($classname)
    {
        // https://www.php.net/manual/fr/function.glob.php
        $toFile = glob(__DIR__ . "/$classname.php");

        // https://www.php.net/manual/fr/function.count.php
        $result = count($toFile) ? require $toFile[0] : 0;
    }

    static function getRequest()
    {
        $uri = $_SERVER["REQUEST_URI"];
        // https://www.php.net/manual/fr/function.parse-url.php
        // https://www.php.net/manual/fr/function.extract.php
        extract(parse_url($uri));
        // https://www.php.net/manual/fr/function.pathinfo.php
        extract(pathinfo($path));

        // https://www.php.net/manual/fr/function.in-array
        if (in_array($filename, ["/", ""])) $filename = "index";

        // SEO: help google agaisnt duplicate content
        Xoom::$canonical = ($filename == "index") ? "/" : $filename;

        // store filename
        Xoom::$filename = $filename;
        
        // if there's a page
        $pagefile = Xoom::$rootdir . "/xoom-pages/$filename.php";
        if (is_file($pagefile)) {
            // special template
            include $pagefile;
        } else {
            // default template
            $contentfile = Xoom::$rootdir . "/xoom-templates/contenu-$filename.php";
            if (is_file($contentfile)) Xoom::$template = [ "header", "contenu-$filename", "footer" ];
        }
    }

    static function sendResponse()
    {
        // https://www.php.net/manual/fr/control-structures.foreach.php
        foreach (Xoom::$template as $file) {
            // https://www.php.net/manual/fr/function.require-once.php
            require_once Xoom::$rootdir . "/xoom-templates/$file.php";
        }
    }

    static function showBodyClass ()
    {
        echo Xoom::$filename;
    }

    static function loadConfig ()
    {
        $files = File::list("xoom-data/my-config-*.json");
        foreach($files as $file) {
            $json           = file_get_contents($file);
            // https://www.php.net/manual/fr/function.json-decode.php
            $configas       = json_decode($json, true);
            // https://www.php.net/manual/fr/function.array-merge.php
            // $result = array_merge(Xoom::$configas, $configas);
            // warning: last values will prevail
            Xoom::$configas = $configas + Xoom::$configas;
        }
    }
}
