<?php

class ApiAdmin
{
    static function doCommand ()
    {
        if (Form::checkAdminApiKey())
        {
            Form::setFeedback("...");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }
    }

    static function checkApiKey ()
    {
        if (Form::checkAdminApiKey())
        {
            Form::setFeedback("Welcome Admin");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }
    }
}