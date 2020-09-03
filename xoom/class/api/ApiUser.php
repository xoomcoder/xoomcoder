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

        Form::checkUnique("email", "user");
        Form::checkUnique("login", "user");

        if (Form::isOK()) {
            extract($tokenas);
            // complete data
            $now                        = date("Y-m-d H:i:s");
            $tokenas["dateCreation"]    = $now;
            $tokenas["level"]           = 0;

            // add in SQL
            Model::insert("user", $tokenas);

            // simple activation key with md5 from password hash and email
            $activationKey = md5("$email$password");

            $ip  = $_SERVER["REMOTE_ADDR"];
            // send a email
            $message = 
            <<<x
            <pre>
            Hello,

            Un nouvel membre vient de rejoindre la communauté.
            identifiant: $login
            email: $email
            activation: $activationKey
            date: $now
            ip: $ip
            </pre>
            x;

            Email::send(Config::$adminEmail, "(register) $login / $email", $message);

            // WELCOME MAIL
            $welcome = 
            <<<x
            <pre>
            Bienvenue $login,

            Merci de votre inscription.

            Voici votre code d'activation de votre compte:
            $activationKey
            
            Avec ce code, vous pouvez activer votre compte sur cette page
            <a href="https://xoomcoder.com/activation?email=$email&key=$activationKey">https://xoomcoder.com/activation?email=$email&key=$activationKey</a>

            Vos informations d'inscription:
            identifiant: $login
            email: $email
            date: $now
            ip: $ip

            A bientôt,
            Long Hai
            https://xoomcoder.com/contact
            </pre>
            x;

            Email::send($email, "Inscription de $login sur XoomCoder.com", $welcome);

            Form::setFeedback("Merci. Votre compte est créé. Vous allez recevoir un mail d'activation. Avant de vous connecter, il faut activer votre compte.");

            // debug
            Form::addJson("debug_sql", Model::$logs);
        }

    }

    static function activate ()
    {
        Form::filterEmail("email");
        Form::filterMd5("activationKey");

        if (Form::isOK()) {
            extract(Form::$formdatas);
            $users = Model::read("user", "email", $email);
            foreach($users as $user) {
                extract($user);
                $md5 = md5("$email$password");
                // only upgrade the user level if current level is 0
                if (($md5 == $activationKey) && ($level == 0)) {
                    Model::update("user", ["level" => 10 ], $id);
                    Form::setFeedback("Votre compte est maintenant activé.");
                }
                else {
                    Form::setFeedback("Désolé votre clé est incorrecte.");
                }
            }
            if (empty($user)) {
                Form::setFeedback("Désolé votre email n'a pas été trouvé.");
            }
        }
    }
    //@end
}