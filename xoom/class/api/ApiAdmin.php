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

            // FIXME: SQL should not be here (MVC...)
            $sql = Model::getSql("user.read");

            $command =
            <<<x
            DbRequest?json=users&bloc=sql2

            @bloc sql2
            $sql
            @bloc
            
            x;

            AdminCommand::run($command);

            Form::setFeedback("Welcome Admin");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }
    }
}