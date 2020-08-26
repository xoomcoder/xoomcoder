<?php

class Xoom
{
    static function start ()
    {
        // https://www.php.net/manual/fr/function.spl-autoload-register.php
        spl_autoload_register("Xoom::loadClass");
    }

    static function loadClass ($classname)
    {
        // https://www.php.net/manual/fr/function.glob.php
        $toFile = glob(__DIR__ . "/$classname.php");

        // https://www.php.net/manual/fr/function.count.php
        $result = count($toFile) ? require $toFile[0] : 0;

    }
}