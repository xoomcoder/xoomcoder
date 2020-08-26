<?php

/**
 * security: this is a public class
 */
class ApiContact
{

    static function message ()
    {
        Form::$jsonsa["feedback"] = "Merci pour votre message";

        $nom        = Form::filterInput("nom");
        $email      = Form::filterInput("email");
        $message    = Form::filterInput("message");

        if ("$nom$email$message" != "") {
            // send a email
            Email::send("contact@woomcoder.com", "message de: $nom / $email", $message);
        }
    }
}