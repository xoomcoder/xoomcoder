<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-01 13:15:46
 * licence: MIT
 */

class ApiUser
{
    static function register ()
    {
        // prepare insert SQL
        $tokenas    = [
            "login"     => Form::filterText("login"),
            "email"     => Form::filterEmail("email"),
            "password"  => Form::filterPassword("password"),
        ];

        if (Form::isOK()) {
            extract($tokenas);
            // complete data
            $now                        = date("Y-m-d H:i:s");
            $tokenas["dateCreation"]    = $now;
            $tokenas["level"]           = 0;
            // add in SQL
            Model::insert("user", $tokenas);

            $ip  = $_SERVER["REMOTE_ADDR"];
            // send a email
            $message = 
            <<<x
            Hello,

            Un nouvel membre vient de rejoindre la communauté.
            login: $login
            email: $email
            date: $now
            ip: $ip

            x;

            Email::send(Config::$adminEmail, "(register) $login / $email", $message);

            // WELCOME MAIL
            $welcome = 
            <<<x
            Bienvenue,

            Merci de votre inscription.
            Vous allez recevoir un autre avec un lien pour activer votre compte.

            Vos informations d'inscription:
            login: $login
            email: $email
            date: $now
            ip: $ip

            A bientôt,
            Long Hai
            https://xoomcoder.com/contact
            
            x;

            Email::send($email, "Inscription de $login sur XoomCoder.com", $welcome);

            Form::setFeedback("Merci. Vous allez recevoir un mail d'activation. Votre compte est créé. Avant de vous connecter, il faut activer votre compte.");
        }

    }
    //@end
}