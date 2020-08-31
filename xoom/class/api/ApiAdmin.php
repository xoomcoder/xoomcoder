<?php

class ApiAdmin
{
    static function doCommand ()
    {
        if (Form::checkAdminApiKey())
        {

            $command = Form::filterInput("command");
            // https://www.php.net/manual/fr/function.explode.php
            AdminCommand::run($command);

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
            // setup login
            Form::addJson("login", date("Y-m-d H:i:s"));

            Form::setFeedback("Welcome Admin");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }
    }
}