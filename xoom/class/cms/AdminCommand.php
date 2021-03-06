<?php

class AdminCommand
{
    static $commands     = [];
    static $blocas       = [];

    static function getBloc($name, $default="")
    {
        return AdminCommand::$blocas[$name] ?? $default;
    }

    static function run ($script, $save=true, $reset=false)
    {
        if ($reset)
            AdminCommand::$commands = [];
            
        if ($save)
            AdminCommand::save($script);

        $lines = explode("\n", $script);

        $bloccode  = "";
        $blocname  = "";
        foreach($lines as $index => $line0) {

            $line = trim($line0);

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
                // keep empty lines for markdown syntax
                $bloccode .= "$line0\n";    // keep raw line and add newline back
            }
            else if ($line) {
                AdminCommand::$commands[] = $line;
            }
        }
        // FIXME: case of non terminated bloc with @bloc ?
        // should it be ignored ? as comment ?

        Form::addJson("debug_commandBlocas", AdminCommand::$blocas);
        Form::addJson("debug_commands", AdminCommand::$commands);

        foreach(AdminCommand::$commands as $line) {
            AdminCommand::process($line);
        }
    }

    static function runLocal ($script)
    {
        $blocas = [];
        $commands = [];

        $lines = explode("\n", $script);

        $bloccode  = "";
        $blocname  = "";
        foreach($lines as $index => $line0) {

            $line = trim($line0);

            if ("@bloc" == substr($line, 0, 5)) {
                if ($line != "@bloc") {  // start bloc
                    $blocname = trim(str_replace("@bloc", "", $line));
                }
                else {  // end bloc
                    $blocas[$blocname] = $bloccode; // add new bloc
                    $blocname = "";
                    $bloccode = "";     // reset
                }
            }
            else if ($blocname != "") {     // inside bloc
                // keep empty lines for markdown syntax
                $bloccode .= "$line0\n";    // keep raw line and add newline back
            }
            else if ($line) {
                $commands[] = $line;
            }
        }
        // FIXME: case of non terminated bloc with @bloc ?
        // should it be ignored ? as comment ?

        foreach($commands as $line) {
            AdminCommand::process($line, $blocas);  // add local blocs
        }
    }

    static function save ($script)
    {
        $script = trim($script);
        if($script != "") {
            $md5 = md5($script);
            $blocnotes = Model::read("blocnote", "md5", $md5);
            $now = date("Y-m-d H:i:s");
            foreach($blocnotes as $bn) {
                extract($bn);
                Model::update("blocnote", 
                    [   "nbrun"         => 1+intval($nbrun), 
                        "dateLastRun"   => $now ], 
                    $id);
            }
            if (empty($bn)) {
                Model::insert("blocnote", 
                    [   "code"              => $script, 
                        "md5"               => $md5, 
                        "datePublication"   => $now, 
                        "dateLastRun"       => $now, 
                        "nbRun"             => 1 ]);
            }    
        }
    }

    static function process ($command, $blocs=null)
    {
        static $index = 0;
        // https://www.php.net/manual/fr/function.parse-url.php
        extract(parse_url("$command"));
        extract(pathinfo("/" . ($path ?? ""))); // prepend / for dirname

        $code = "AdminCommand::api$filename";
        // https://www.php.net/manual/fr/function.is-callable
        if (is_callable($code)) {
            $paramas = [];
            parse_str($query ?? "", $paramas);
            if ($dirname != "/") {
                $paramas["json"] = trim($dirname, "/");
            }
            if ($blocs != null) {
                $paramas["localbloc"] = $blocs;
            }

            $code($paramas);    
        }

        Form::addJson("debug_line$index", "$dirname/$code/$command");
        $index++;
    }

    static function apiTime ()
    {
        Form::addJson("debug_commandTime", date("Y-m-d H:i:s"));
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
            Form::addJson("debug_commandLogReset-$index", $logfile);
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

            Form::addJson("debug_commandConfigAdd", $filteras);
            $json = json_encode($filteras, JSON_PRETTY_PRINT);
            File::create("xoom-data/my-config-$to.json", $json);               
        }
    }

    static function apiConfigRead ()
    {
        Form::addJson("debug_commandConfigRead", Xoom::$configas);

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

    static function apiDbRequest ($paramas)
    {
        extract($paramas);
        if ($bloc ?? false) {
            $request = AdminCommand::$blocas[$bloc] ?? "";
            if ($request != "") {
                $pdoStatement = Model::sendSql($request);
    
                if ( ("" != ($json ?? "")) && ($pdoStatement != null) ) {
                    // https://www.php.net/manual/fr/pdostatement.fetchall.php
                    $resultas = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
                    Form::addJson($json, $resultas);
                }    
            }
        }

        if ($key ?? false) {
            $request = Model::getSql($key);
            if ($request != "") {
                $pdoStatement = Model::sendSql($request);
    
                if ( ("" != ($json ?? "")) && ($pdoStatement != null) ) {
                    // https://www.php.net/manual/fr/pdostatement.fetchall.php
                    $resultas = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
                    Form::addJson($json, $resultas);
                }    
            }
        }

        if ($keys ?? false) {
            Form::addJson("debug_0", $keys);
            $keylist = explode(",", $keys);
            if (!empty($keylist)) {
                $results = [];
                foreach($keylist as $k) {
                    $request = Model::getSql($k);
                    if ($request != "") {
                        $pdoStatement = Model::sendSql($request);
            
                        if ( ("" != ($json ?? "")) && ($pdoStatement != null) ) {
                            // https://www.php.net/manual/fr/pdostatement.fetchall.php
                            extract(pathinfo($k));
                            $results[$filename ?? $k] = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
                        }    
                    }
                }
                // build an array
                Form::mergeJson($json, $results);
            }
        }

    }

    static function apiDbRead ($paramas)
    {
        extract($paramas);
        if (($table ?? false) && ("" != ($json ?? ""))) {
            $resultas = Model::read($table);
            Form::addJson($json, $resultas);
        }
    }

    static function apiDbDelete ($paramas)
    {
        extract($paramas);
        if (($table ?? false) && ($id ?? false)) {
            Model::delete($table, $id);
        }
    }

    static function apiDbInsert ($paramas)
    {
        extract($paramas);
        if (($bloc ?? false) && ($table ?? false)) {
            $code    = AdminCommand::$blocas[$bloc] ?? "{}";
            $tokenas = json_decode($code, true);
            Model::insert($table, $tokenas);
        }
    }

    static function apiDbUpdate ($paramas)
    {
        extract($paramas);
        if (($bloc ?? false) && ($table ?? false) && ($id ?? false)) {
            $code    = AdminCommand::$blocas[$bloc] ?? "{}";
            $tokenas = json_decode($code, true);
            Model::update($table, $tokenas, $id);
        }
    }

    static function apiFileWrite ($paramas)
    {
        extract($paramas);
        if (($bloc ?? false) && ($filename ?? false)) {

            $code = AdminCommand::$blocas[$bloc] ?? "";

            File::create($filename, $code);

            Form::addJson("debug_commandFileWrite", $filename);
        }
    }

    static function apiFileRead ($paramas)
    {
        extract($paramas);
        if (($json ?? false) && ($filename ?? false)) {
            $code = File::content($filename);
            Form::addJson($json, $code);
        }
    }

    static function apiFileDelete ($paramas)
    {
        extract($paramas);
        if ($filename ?? false) {
            $code = File::delete($filename, true);
            if ("" != ($json ?? "")) {
                Form::addJson($json, $code);
            }
        }
    }

    static function apiFileMove ($paramas)
    {
        extract($paramas);
        if (($filename ?? false) && ($newname ?? false)) {
            $code = File::move($filename, $newname, true);
            if ("" != ($json ?? "")) {
                Form::addJson($json, $code);
            }
        }
    }

    static function apiDirCreate ($paramas)
    {
        extract($paramas);
        if ($dirname ?? false) {
            $code = File::createDir($dirname, $recursive ?? true);
            if ("" != ($json ?? "")) {
                Form::addJson($json, $code);
            }
        }
    }

    static function apiDirDelete ($paramas)
    {
        extract($paramas);
        if ($dirname ?? false) {
            $code = File::deleteDir($dirname);
            if ("" != ($json ?? "")) {
                Form::addJson($json, $code);
            }
        }
    }

    static function apiFileUpload ($paramas)
    {
        extract($paramas);
        if ($name ?? false) {
            Form::filterUpload($name);
        }
    }

    static function apiDirList ($paramas)
    {
        extract($paramas);
        if ($name ?? false) {
            $files = File::list($name);
            if ("" != ($json ?? "")) {
                Form::addJson($json, $files);
            }
        }
    }

    static function apiMail ($paramas)
    {
        extract($paramas);
        if (($bloc ?? false) && ($to ?? false)) {

            $code = AdminCommand::$blocas[$bloc] ?? "";

            Email::send($to, $subject ?? "", $code);

            Form::addJson("debug_mailcode", $code);
        }

    }

    /**
     * warning: very dangerous
     */
    static function apiPhpEval ($paramas)
    {
        extract($paramas);
        if ($bloc ?? false) {

            if ($localbloc ?? false) {
                // try local blocs
                $code = $localbloc[$bloc] ?? "";
            }
            else {
                $code = AdminCommand::$blocas[$bloc] ?? "";
            }
            ob_start();

            // https://www.php.net/manual/fr/function.eval.php
            eval($code);

            $output = ob_get_clean();

            if ("" != ($json ?? "")) {
                Form::addJson($json, $output);
            }

            if ($echo ?? false) {
                echo $output;
            }
        }
    }

    static function apiAdd ($paramas)
    {
        extract($paramas);
        if ($bloc ?? false) {
            $code = AdminCommand::$blocas[$bloc] ?? "";
            if ("" != ($json ?? "")) {
                Form::appendJson($json, $code);
            }

        }
    }

    static function apiPhotoLoad ($paramas)
    {
        extract($paramas);
        if ($tag ?? false) {
            News::getPhotos($tag);
        }
    }
}