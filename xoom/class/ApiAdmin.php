<?php

class ApiAdmin
{
    static function doCommand ()
    {
        Form::setFeedback("Hello");
    }

    static function checkApiKey ()
    {
        $apikey = Form::filterInput("apikey");
        if ($apikey != "") {
            $hash = base64_decode($apikey);
        }
        if (password_verify(Config::$adminPH, $hash)) {
            Form::setFeedback("...Welcome Admin...");
        }
        else {
            Form::setFeedback("Sorry...$apikey...");
        }
    }
}