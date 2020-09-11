<?php

class Xoom
{
    // static properties
    static $rootdir     = "";
    static $template    = [];
    static $filename    = "";
    static $canonical   = "";
    static $configas    = [];
    static $errors      = [];
    static $sitedirs    = [];

    // static methods

    static function start($rootdir, $mode="web")
    {
        set_error_handler("Xoom::handleError");

        error_reporting(E_ALL);
        ini_set("error_display", "1");

        // store the root dir for all project
        Xoom::$rootdir = $rootdir;

        // https://www.php.net/manual/fr/function.spl-autoload-register.php
        spl_autoload_register("Xoom::loadClass");

        Xoom::$configas["rootdir"] = Xoom::$rootdir;
        // load all .json files
        Xoom::loadConfig();
        // complete config
        Xoom::completeConfig();

        if ($mode == "web") {
            Xoom::getRequest();
            // build the page to the browser
            Xoom::sendResponse();    
        }
        elseif ($mode == "xterm") {
            Terminal::runTerminal();
        }

    }


    static function handleError ($errno, $errstr, $errfile, $errline)
    {
        Xoom::$errors[] = [$errno, $errstr, $errfile, $errline];
    }

    static function loadClass($classname)
    {
        // https://www.php.net/manual/fr/function.glob.php
        $basedir = Xoom::$rootdir . "/xoom/class";
        $toFile = glob("$basedir/$classname.php");

        // https://www.php.net/manual/fr/function.count.php
        $result = count($toFile) ? require $toFile[0] : 0;

        // look also in subfolders
        if ($result == 0) {
            $toFile = glob("$basedir/*/$classname.php");

            // https://www.php.net/manual/fr/function.count.php
            $result = count($toFile) ? require $toFile[0] : 0;   
        }
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

        // SEO: help google against duplicate content
        Xoom::$canonical = ($filename == "index") ? "" : $filename;

        // store filename
        Xoom::$filename = $filename;
        
        // if there's a page
        extract(Xoom::getConfig("rootdir,contentdir"));
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

    static function sendResponse()
    {
        // https://www.php.net/manual/fr/control-structures.foreach.php
        foreach (Xoom::$template as $file) {
            // https://www.php.net/manual/fr/function.require-once.php
            require_once $file;
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

    static function getConfig($names)
    {
        $resultas = [];
        $listnames = explode(",", $names);
        foreach($listnames as $name) {
            $name = trim($name);
            $resultas[$name] = Xoom::$configas[$name] ?? "";
        }
        return $resultas;
    }

    static function setConfig($key, $value)
    {
        Xoom::$configas[$key] = $value;
    }

    static function completeConfig ()
    {
        // FIXME: code should be more general
        extract(Xoom::getConfig("rootdir,sitedir"));
        // https://www.php.net/manual/fr/function.realpath.php
        $contentdir = realpath("$rootdir/$sitedir");
        if ($contentdir !== false) {
            Xoom::setConfig("contentdir", $contentdir);
        }
    }
}
