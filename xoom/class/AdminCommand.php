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

    static function apiLogRead ()
    {
        $today = date("Y-md");
        $logfile = Xoom::$rootdir . "/xoom-data/my-api-$today.log";
        $logs = [];
        if (is_file($logfile)) 
            // https://www.php.net/manual/fr/function.file.php
            $logs = file($logfile);

        Form::addJson("commandLogRead", $logs);
    }

    /**
     * REMOVE LOG FILES
     * SECURITY: CAN BE DANGEROUS
     */
    static function apiLogReset ()
    {
        $logfiles = glob(Xoom::$rootdir . "/xoom-data/my-api-*.log");
        foreach($logfiles as $index => $logfile) {
            // https://www.php.net/manual/fr/function.unlink.php
            unlink($logfile);
            Form::addJson("commandLogReset-$index", $logfile);
        }
    }
}