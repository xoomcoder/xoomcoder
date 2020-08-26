<?php

class Test 
{
    static function log ($message)
    {
        // error_log($message);
        if (Form::$jsonsa["debug"] ?? false) {
            Form::$jsonsa["debug"] .= "\n$message";  
        }
        else {
            Form::$jsonsa["debug"] = "\n$message";  
        }

    }
}