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

            // fill response with more information
            $command = "
            data/DbRequest?keys=user.read,content.read,manymany.read,blocnote.read
            ";
            AdminCommand::run($command);

            // setup login with keyApi
            Form::addJson("login", Form::filterInput("keyApi"));

            Form::setFeedback("Welcome Admin");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }
    }
}