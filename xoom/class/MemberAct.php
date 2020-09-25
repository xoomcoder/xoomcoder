<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-04 13:01:40
 * licence: MIT
 */

class MemberAct
{
    static $commands     = [];
    static $blocas       = [];

    static function run ($note, $save=false, $reset=false)
    {
        if ($reset)
            MemberAct::$commands = [];
            
        if ($save)
            MemberAct::save("", $note); // title ?

        $lines = explode("\n", $note);

        $bloccode  = "";
        $blocname  = "";
        foreach($lines as $index => $line0) {

            $line = trim($line0);

            if ($line) {
                if ("@bloc" == substr($line, 0, 5)) {
                    if ($line != "@bloc") {  // start bloc
                        $blocname = trim(str_replace("@bloc", "", $line));
                    }
                    else {  // end bloc
                        MemberAct::$blocas[$blocname] = $bloccode; // add new bloc
                        $blocname = "";
                        $bloccode = "";     // reset
                    }
                }
                else if ($blocname != "") {     // inside bloc
                    $bloccode .= "$line0\n";    // keep raw line and add newline back
                }
                else {
                    MemberAct::$commands[] = $line;
                }
            }
        }
        // FIXME: case of non terminated bloc with @bloc ?
        // should it be ignored ? as comment ?

        Form::addJson("debug_commandBlocas", AdminCommand::$blocas);
        Form::addJson("debug_commands", AdminCommand::$commands);

        foreach(MemberAct::$commands as $line) {
            MemberAct::process($line);
        }
    }

    static function save ($title, $note)
    {
        $script = trim($note);
        if($script != "") {
            $md5 = md5($script);
            $blocnotes = Model::read("blocnote", "md5", $md5);
            $now = date("Y-m-d H:i:s");
            foreach($blocnotes as $bn) {
                $titleInput = $title; // keep it safe
                extract($bn);
                
                Model::update("blocnote", 
                    [   "nbrun"         => 1+intval($nbrun), 
                        "title"         => $titleInput, 
                        "dateLastRun"   => $now,
                    ], 
                    $id);
            }
            if (empty($bn)) {
                // hack
                $lat = Form::filterText("lat");
                $lng = Form::filterText("lng");
                $json = json_encode([ "lat" => $lat, "lng" => $lng ], JSON_PRETTY_PRINT);
                $code = "$script";
                extract(Controller::$user);
                Model::insert("blocnote", 
                    [   "code"              => $code, 
                        "md5"               => $md5, 
                        "title"             => $title, 
                        "datePublication"   => $now, 
                        "dateLastRun"       => $now, 
                        "nbRun"             => 1,
                        "id_user"           => $id ?? null,
                        "username"          => $login ?? null,
                        "json"              => $json, 
                    ]);
            }    
        }

    }

    static function process ($command)
    {
        static $index = 0;

        // https://www.php.net/manual/fr/function.parse-url.php
        extract(parse_url("$command"));
        extract(pathinfo("/" . ($path ?? ""))); // prepend / for dirname

        $code = "MemberAct::api$filename";
        // https://www.php.net/manual/fr/function.is-callable
        if (is_callable($code)) {
            $paramas = [];
            parse_str($query ?? "", $paramas);
            if ($dirname != "/") {
                $paramas["json"] = trim($dirname, "/");
            }
            // run the command
            $code($paramas);    
        }

        Form::addJson("debug_line$index", "$dirname/$code/$command");
        $index++;
    }

    static function apiDbDelete ($paramas)
    {
        extract(Controller::$user);
        $iduser0 = $id ?? 0;

        extract($paramas);
        if (($table ?? false) && ($id ?? false)) {
            $notes = Model::read($table, "id", $id);
            // check if line belongs to user
            foreach($notes as $note) {
                extract($note);
                $iduser = intval($id_user ?? 0);
                if (($iduser > 0) && ($iduser == $iduser0)) {
                    // ok this is his notes
                    Model::delete($table, $id);
                    File::deleteMedia($id);
                }
            }
        }
    }

    static function apiDbRead ($paramas)
    {
        extract($paramas);
        if (($table ?? false) && ("" != ($json ?? ""))) {
            // filter on id_user
            extract(Controller::$user);

            // FIXME: make it config
            $orderby = "";
            if ($table == "geocms") {
                $orderby = "datePublication DESC, priority DESC, id DESC";
                $resultas = Cms::read($category ?? "news", $orderby);
            }
            else {
                $resultas = Model::read($table);
            }
            Form::mergeJson($json, [$table => $resultas]);
        }
    }

    //@end
}