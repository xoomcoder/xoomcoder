<?php

class Form
{
    // static properties
    static $jsonsa = [];

    // static methods
    static function filterInput($name, $default="")
    {
        $result = $_REQUEST["$name"] ?? $default;
        // https://www.php.net/manual/fr/function.strip-tags.php
        $result = strip_tags($result);
        // https://www.php.net/manual/fr/function.trim.php
        $result = trim($result);

        return $result;
    }

    static function filterLetter ($name, $default="")
    {
        $result = Form::filterInput($name, $default);
        // https://www.php.net/manual/fr/function.preg-replace.php
        $result = preg_replace("/[^a-zA-Z0-9]/", "", $result);

        return $result;
    }

    static function process ()
    {
        // debug
        Form::$jsonsa["request"] = $_REQUEST;

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
    }

    static function sendJSON ()
    {
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
        }
        if (password_verify(Config::$adminPH, $hash)) {
            $result = true;
        }
        return $result;
    }

    static function addJson ($key, $value)
    {
        Form::$jsonsa["$key"] = $value;
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
        $logsa["ip"]        = $_SERVER["REMOTE_ADDR"];
        $logsa["from"]      = $_SERVER["HTTP_REFERER"];
        $logsa["ua"]        = $_SERVER["HTTP_USER_AGENT"];

        // security: remove confidential data
        $filterPost = $_POST;
        // https://www.php.net/manual/fr/function.unset.php
        if (isset($filterPost['keyApi'])) {
            $filterPost['keyApi'] = md5($filterPost['keyApi']);
        }

        $logsa["post"]      = $filterPost;
    
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

    static function filterUpload ($nameStart)
    {
        if (!empty($_FILES)) {
            Form::addJson("files", $_FILES);

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
}