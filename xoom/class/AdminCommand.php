<?php

class AdminCommand
{
    static $commands    = [];
    static $blocas       = [];

    static function run ($script)
    {
        $lines = explode("\n", $script);

        $bloccode  = "";
        $blocname  = "";
        foreach($lines as $index => $line) {
            $line = trim($line);

            if ($line) {
                if ("@bloc" == substr($line, 0, 5)) {
                    if ($line != "@bloc") {  // start bloc
                        $blocname = trim(str_replace("@bloc", "", $line));
                    }
                    else {  // end bloc
                        AdminCommand::$blocas[$blocname] = $bloccode; // add new bloc
                        $blocname = "";
                        $bloccode = "";     // reset
                    }
                }
                else if ($blocname != "") {     // inside bloc
                    $bloccode .= "$line\n";     // add newline back
                }
                else {
                    AdminCommand::$commands[] = $line;
                }
            }
        }

        Form::addJson("commandBlocas", AdminCommand::$blocas);

        foreach(AdminCommand::$commands as $line) {
            AdminCommand::process($line);
        }
    }

    static function process ($command)
    {
        static $index = 0;

        // https://www.php.net/manual/fr/function.parse-url.php
        extract(parse_url($command));

        $code = "AdminCommand::api$path";
        // https://www.php.net/manual/fr/function.is-callable
        if (is_callable($code)) {
            $paramsa = [];
            parse_str($query ?? "", $paramsa);
            $code($paramsa);    
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

    /**
     * remove keys starting with _
     */
    static function apiConfigAdd ($paramsa)
    {
        $to = $paramsa["_to"] ?? "";
        $to = preg_replace("/[^a-zA-Z0-9-]/", "", $to);
        $to = strtolower($to);

        if ($to != "") {
            // https://www.php.net/manual/fr/function.array-filter.php
            $filteras = array_filter($paramsa, function($v, $k) {
                if ($k[0] == "_") return false;
                return true;
            }, ARRAY_FILTER_USE_BOTH);

            Form::addJson("commandConfigAdd", $filteras);
            $json = json_encode($filteras, JSON_PRETTY_PRINT);
            File::create("xoom-data/my-config-$to.json", $json);               
        }
    }

    static function apiConfigRead ()
    {
        Form::addJson("commandConfigRead", Xoom::$configas);

    }

    static function apiBuildClass ($paramas)
    {
        extract($paramas);
        if ("" != ($name ?? "")) {
            File::buildClass($name);
        }
    }

    static function apiDbCreate ($paramas)
    {
        extract($paramas);
        if ($dbname ?? false) {
            $sql =
            <<<x
            create database $dbname;
            x;
            Model::sendSql($sql);
        }
    }

    static function apiDbrequest ($paramas)
    {
        extract($paramas);
        if ($request ?? false) {

            $pdoStatement = Model::sendSql($request);

            if ($read ?? false) {
                // https://www.php.net/manual/fr/pdostatement.fetchall.php
                $resultas = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
                Form::addJson("commandDbRequest", $resultas);
            }
        }

    }


}