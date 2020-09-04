<?php

class ApiAdmin
{
    static function doCommand ()
    {
        if (Form::checkAdminApiKey())
        {
            // security: no filter as may contain plenty of different code
            $command    = Form::filterInput("command");
            $command2   = Form::filterInput("command2");
            // https://www.php.net/manual/fr/function.explode.php
            AdminCommand::run($command);
            AdminCommand::run($command2, false);

            $now = date("Y-m-d H:i:s");
            Form::setFeedback("($now)...");
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