<?php

class Form
{
    // static properties
    static $jsonsa      = [];
    static $errors      = [];
    static $formdatas   = [];

    // static methods
    static function isOK ()
    {
        $nberrors = count(Form::$errors);
        if ($nberrors > 0) {
            $msgerrrors = implode("\n", Form::$errors);
            Form::setFeedback("Votre formulaire contient $nberrors erreur(s). $msgerrrors");
        }
        // https://www.php.net/manual/fr/function.empty.php
        return (0 == $nberrors);
    }

    static function add ($key, $value) {
        Form::$formdatas[$key] = $value;
    }

    static function filterInput($name, $default="")
    {
        $result = $_REQUEST["$name"] ?? $default;
        // https://www.php.net/manual/fr/function.strip-tags.php
        $result = strip_tags($result);
        // https://www.php.net/manual/fr/function.trim.php
        $result = trim($result);

        return $result;
    }

    /**
     * dangerous: keep raw input with potential dangerous code
     */
    static function filterNone ($name, $default="")
    {
        $result = $_REQUEST["$name"] ?? $default;
        // https://www.php.net/manual/fr/function.trim.php
        $result = trim($result);

        if ($result == "") {
            Form::$errors[] = "texte vide";
        }
        
        Form::$formdatas[$name] = $result;

        return $result;
    }

    static function filterText ($name, $default="", $params="")
    {
        $options = [];
        parse_str($params, $options);
        extract($options);

        $result = Form::filterInput($name, $default);

        if (($result == "") && !($optional ?? false)) {
            Form::$errors[] = "texte vide";
        }
        
        Form::$formdatas[$name] = $result;

        return $result;
    }

    static function filterInt ($name, $default="", $params="")
    {
        $options = [];
        parse_str($params, $options);
        extract($options);

        $result = Form::filterInput($name, $default);
        $result = intval($result);
        
        Form::$formdatas[$name] = $result;

        return $result;
    }

    static function filterFloat ($name, $default="", $params="")
    {
        $options = [];
        parse_str($params, $options);
        extract($options);

        $result = Form::filterInput($name, $default);
        $result = floatval($result);
        
        Form::$formdatas[$name] = $result;

        return $result;
    }

    static function filterMd5 ($name, $default="")
    {
        $result = Form::filterInput($name, $default);

        if (mb_strlen($result) != 32) {
            Form::$errors[] = "longueur incorrecte";
        }
        // TODO: add hexa check
        
        Form::$formdatas[$name] = $result;

        return $result;
    }

    static function filterPassword ($name, $default="", $hash=true)
    {
        $result = Form::filterInput($name, $default);

        // passwords are md5 (32 chars..)
        if (mb_strlen($result) != 32) {
            Form::$errors[] = "mot de passe incorrect";
        }
        // hash password
        if ($hash) {
            $result = password_hash($result, PASSWORD_DEFAULT);
        }

        Form::$formdatas[$name] = $result;

        return $result;
    }

    static function filterLetter ($name, $default="")
    {
        $result = Form::filterInput($name, $default);
        // https://www.php.net/manual/fr/function.preg-replace.php
        $result = preg_replace("/[^a-zA-Z0-9]/", "", $result);

        if ($result == "") {
            Form::$errors[] = "texte vide";
        }
        Form::$formdatas[$name] = $result;
        return $result;
    }

    static function filterEmail ($name, $default="")
    {
        $result = Form::filterInput($name, $default);
        // https://www.php.net/manual/fr/function.preg-replace.php
        if ($result == "") {
            Form::$errors[] = "texte vide";
        }
        if (($result != "") && (false === filter_var($result, FILTER_VALIDATE_EMAIL))) {
            Form::$errors[] = "email incorrect";
        }
        Form::$formdatas[$name] = $result;
        return $result;
    }

    static function filterDatetime ($name, $default="")
    {
        $result = Form::filterInput($name, $default);
        // https://www.php.net/manual/fr/function.preg-replace.php
        if ($result == "") {
            Form::$errors[] = "date vide";
        }
        if ($result != "") {
            $result = date("Y-m-d H:i:s", strtotime($result));
        }
        Form::$formdatas[$name] = $result;
        return $result;
    }

    static function process ()
    {
        // debug
        Form::$jsonsa["debug_request"] = Form::hashConfidential($_REQUEST);

        $apiClass   = Form::filterInput("classApi");
        $apiMethod  = Form::filterInput("methodApi");
        
        // security: check Config
        // so api can use class Config freely
        if(!class_exists("Config") && ($apiClass != "Install")) return;

        // security: only give access to Api... classes
        $callback = "Api$apiClass::$apiMethod";
        // https://www.php.net/manual/fr/function.is-callable.php
        if (is_callable($callback))
        {
            $callback();
        }

        // debug
        Form::addJson("debug_form_errors", Form::$errors);
    }

    static function sendJSON ()
    {
        // debug
        Form::addJson("debug_errors_php", Xoom::$errors);
        Form::addJson("debug_sql", Model::$logs);

        // sort the keys
        // https://www.php.net/manual/fr/function.ksort.php
        ksort(Form::$jsonsa);

        echo json_encode(Form::$jsonsa, JSON_PRETTY_PRINT);
    }

    static function setFeedback ($message)
    {
        Form::$jsonsa["feedback"] = $message;
    }
    
    static function checkAdminApiKey ()
    {
        $result = false;
        $apikey = Form::filterInput("keyApi");
        if ($apikey != "") {
            $hash = base64_decode($apikey);
            if (password_verify(Config::$adminPH, $hash)) {
                $result = true;
            }
        }
        return $result;
    }

    static function addJson ($key, $value)
    {
        Form::$jsonsa["$key"] = $value;
    }

    static function appendJson ($key, $value)
    {
        Form::$jsonsa["$key"] = (Form::$jsonsa["$key"] ?? "") . $value;
    }

    static function mergeJson ($key, $value)
    {
        // $value will overwrite old values if already present
        Form::$jsonsa["$key"] = $value + (Form::$jsonsa["$key"] ?? []);
    }

    /**
     * no log or show of confidential data. 
     * replace data with md5 for search and comparison on text.
     */
    static function hashConfidential ($datas)
    {
        // security: remove confidential data
        $filterPost = $datas;
        // https://www.php.net/manual/fr/function.unset.php
        if (isset($filterPost['keyApi'])) {
            $filterPost['keyApi'] = md5($filterPost['keyApi']);
        }
        if (isset($filterPost['password'])) {
            $filterPost['password'] = md5($filterPost['password']);
        }
        return $filterPost;
    }

    static function log ()
    {
        $logsa              = [];

        // https://www.php.net/manual/fr/function.time.php
        // https://www.php.net/manual/fr/function.date.php
        $now                = time();
        $today              = date("Y-md");

        // https://www.php.net/manual/fr/reserved.variables.server.php
        $logsa["timestamp"] = $now;
        $logsa["datetime"]  = date("Y-m-d H:i:s", $now);
        $logsa["ip"]        = $_SERVER["REMOTE_ADDR"] ?? "";
        $logsa["from"]      = $_SERVER["HTTP_REFERER"] ?? "";
        $logsa["ua"]        = $_SERVER["HTTP_USER_AGENT"] ?? "";

        $logsa["post"]      = Form::hashConfidential($_POST);
    
        $b64  = Form::encode64($logsa);

        // append to the log file
        $logfile = Xoom::$rootdir . "/xoom-data/my-api-$today.log";
        // https://www.php.net/manual/fr/function.file-put-contents.php
        file_put_contents($logfile, "$b64\n", FILE_APPEND);
    }

    static function encode64 ($infosa)
    {
        $json = json_encode($infosa, JSON_PRETTY_PRINT);
        $b64  = base64_encode($json);
        return $b64;
    }

    static function filterMedia ($nameStart, $idLine)
    {
        $result = "";
        Form::addJson("debug_files", $_FILES);
        if (!empty($_FILES)) {
            foreach($_FILES as $nameInput => $upload) {
                if (0 === strpos($nameInput, $nameStart)) {
                    // https://www.php.net/manual/fr/features.file-upload.post-method.php
                    extract($upload);
                    // $tmp_name, $name, $error, $size, $type
                    $errors = [];
                    if ($error != 0) {
                        $errors[] = "network error";
                    }
                    if ($size > Controller::getSizeMax()) {
                        $errors[] = "size too big";
                    }
                    if ($name == "") {
                        $errors[] = "name is empty";
                    }
                    else {
                        extract(pathinfo($name));
                        $extOK = Controller::getExtensionOK();
                        $extension = strtolower($extension ?? "");
                        if (!in_array($extension, $extOK)) {
                            $errors[] = "extension is not authorized";
                        }
                    }

                    if (count($errors) == 0) {
                        extract(Xoom::getConfig("rootdir"));

                        // 
                        $name2bid = Controller::getMediaFilename($idLine);
                        $subfolder=substr($name2bid, 0, 1);

                        $targetname = "$rootdir/xoom-data/media/$subfolder/my-$name2bid.$extension";
                        if (is_dir("$rootdir/xoom-data/media/$subfolder")) {
                            // clean files with other extensions
                            File::deleteMedia($idLine);
                            // create the new media file
                            move_uploaded_file($tmp_name, $targetname);
                            // return for db line
                            $result = File::filterName($name);    
                        }
                    }
                }
            }
        }
        return $result;
    }

    static function filterUpload ($nameStart)
    {
        if (!empty($_FILES)) {
            Form::addJson("debug_files", $_FILES);

            File::createDir("public/assets/media");
            File::create("public/assets/media/index.php", "", false);

            foreach($_FILES as $nameInput => $upload) {
                if (0 === strpos($nameInput, $nameStart)) {
                    // https://www.php.net/manual/fr/features.file-upload.post-method.php
                    extract($upload);
                    // $tmp_name, $name, $error, $size, $type
                    $errors = [];
                    if ($error != 0) {
                        $errors[] = "network error";
                    }
                    if ($name == "") {
                        $errors[] = "name is empty";
                    }
                    if (count($errors) == 0) {
                        // 
                        File::getUpload($tmp_name, $name);
                    }
                }
            }
        }
    }

    static function checkUnique ($name, $table)
    {
        $result = false;
        $value = Form::$formdatas[$name] ?? "";
        if ($value != "") {
            $lines = Model::read($table, $name, $value);
            if (count($lines) < 2) {
                $result = true;
            }
        }
        if (!$result) {
            Form::$errors[] = "$name indisponible ($value).";
        }

        return $result;
    }

}