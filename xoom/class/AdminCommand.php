<?php

class AdminCommand
{
    static function process ($command)
    {
        static $index = 0;

        $code = "AdminCommand::api$command";
        // https://www.php.net/manual/fr/function.is-callable
        if (is_callable($code)) {
            $code();    
        }

        Form::addJson("line$index", $command);
        $index++;
    }

    static function apiTime ()
    {
        Form::addJson("commandTime", date("Y-m-d H:i:s"));
    }
    
}