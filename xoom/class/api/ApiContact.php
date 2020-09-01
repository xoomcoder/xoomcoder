<?php

/**
 * security: this is a public class
 */
class ApiContact
{

    static function message ()
    {
        $nom        = Form::filterText("nom");
        $email      = Form::filterEmail("email");
        $message    = Form::filterText("message");

        if (Form::isOK()) {
            $now = date("Y-m-d H:i:s");
            $ip  = $_SERVER["REMOTE_ADDR"];

            $messageAdmin =
            <<<x
            nom: $nom
            email: $email
            date: $now
            ip: $ip
            message:
            $message

            x;
            // send a email
            Email::send(Config::$adminEmail, "(contact) $nom / $email", $messageAdmin);
            Form::$jsonsa["feedback"] = "Merci pour votre message.";
        }
        else {
            Form::$jsonsa["feedback"] = "Informations manquantes.";
        }
    }
}