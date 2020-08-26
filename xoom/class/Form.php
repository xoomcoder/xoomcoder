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
}