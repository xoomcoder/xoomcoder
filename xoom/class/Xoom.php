<?php

class Xoom
{
    // static properties
    static $rootdir     = "";
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
            Request::parse();
            Response::send();
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
