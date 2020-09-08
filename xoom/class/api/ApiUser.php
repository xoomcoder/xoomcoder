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

                    // WELCOME MAIL
                    $welcome = 
                    <<<x
                    <pre>
                    Merci $login,

                    Votre compte est maintenant activé.
                    Vous pouvez vous connecter sur cette page.
                    <a href="https://xoomcoder.com/login">https://xoomcoder.com/login</a>

                    Vos informations de compte:
                    identifiant: $login
                    email: $email

                    A bientôt,
                    Long Hai
                    https://xoomcoder.com/contact
                    </pre>
                    x;

                    Email::send($email, "Activation de $login sur XoomCoder.com", $welcome);

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

    static function login ()
    {
        Form::filterEmail("email");
        $passwordInput = Form::filterPassword("password", "", false);

        if (Form::isOK()) {
            extract(Form::$formdatas);
            $users = Model::read("user", "email", $email);
            foreach($users as $user) {
                extract($user);
                if (password_verify($passwordInput, $password)) {
                    Form::setFeedback("Bienvenue $login.");
                    $now        = time();
                    $payload    = "$login,$level,$id,$now";
                    // FIXME: SQL read must get password each time to check signature...
                    $signature  = password_hash("$payload$password", PASSWORD_DEFAULT);
                    $loginToken = "$payload,$signature";
                    Form::addJson("loginToken", $loginToken);

                    $redirect  = "";
                    if ($level == 10)       $redirect = "studio";
                    if ($level == 100)       $redirect = "studio";
                    if ($redirect != "")    Form::addJson("redirect", $redirect);
                    
                }
                else {
                    Form::setFeedback("Désolé. Identifiants incorrects...");
                }
            }
            if (empty($user)) {
                Form::setFeedback("Désolé votre email n'a pas été trouvé.");
            }
        }
    }
 
    static function passwordLost ()
    {
        Form::filterEmail("email");

        if (Form::isOK()) {
            extract(Form::$formdatas);
            $users = Model::read("user", "email", $email);
            foreach($users as $user) {
                extract($user);
 
                $now        = time();
                $payload    = "$now";
                // FIXME: SQL read must get password each time to check signature...
                $signature  = password_hash("$payload$password", PASSWORD_DEFAULT);
                $loginToken = base64_encode(json_encode([ "payload" => $payload, "signature" => $signature ]));

                Form::setFeedback("Consultez votre boite email pour obtenir votre code.");
                // WELCOME MAIL
                $welcome = 
                <<<x
                <pre>
                Hello $login,

                Voici votre code pour changer le mot de passe de votre compte:
                $loginToken
                
                Avec ce code, vous pouvez changer votre mot de passe sur cette page
                <a href="https://xoomcoder.com/mdp-oublie?email=$email&key=$loginToken#mdp-change">https://xoomcoder.com/mdp-oublie?email=$email&key=$loginToken#mdp-change</a>

                A bientôt,
                Long Hai
                https://xoomcoder.com/contact
                </pre>
                x;

                Email::send($email, "Demande de changement de mot de passe sur XoomCoder.com", $welcome);

            }
            if (empty($user)) {
                Form::setFeedback("Désolé, votre email n'a pas été trouvé.");
            }
        }

    }

    static function passwordChange ()
    {
        Form::filterEmail("email");
        Form::filterPassword("password");
        Form::filterText("key");

        if (Form::isOK()) {
            extract(Form::$formdatas);
            $passwordInput = $password; // keep it for later
            $users = Model::read("user", "email", $email);
            foreach($users as $user) {
                extract($user);
                // FIXME: manage errors
                @extract(@json_decode(@base64_decode($key), true) ?? []);
                $payload = $payload ?? "";
                $signature = $signature ?? "";

                if ( !empty($payload) && !empty($signature) 
                        && @password_verify("$payload$password", $signature ?? "") ) {

                    $time0 = $payload;
                    if (time() < intval($time0 ?? 0) + 3600 * 24) {
                        Model::update("user", ["password" => $passwordInput], $id);
                        Form::setFeedback("Votre nouveau mot de passe est activé.");

                        // WELCOME MAIL
                        $welcome = 
                        <<<x
                        <pre>
                        Hello $login,

                        Votre nouveau mot de passe est maintenant activé.
                        <a href="https://xoomcoder.com/login">https://xoomcoder.com/login</a>

                        A bientôt,
                        Long Hai
                        https://xoomcoder.com/contact
                        </pre>
                        x;

                        Email::send($email, "Demande de changement de mot de passe sur XoomCoder.com", $welcome);
                    }
                    else {
                        Form::setFeedback("Désolé, le lien a expiré au bout de 24H.");
                    }
                }
                else {
                    Form::setFeedback("Désolé, le code est incorrect.");
                }
            }        
            if (empty($user)) {
                Form::setFeedback("Désolé, votre email n'a pas été trouvé.");
            }
        }
    }

    //@end
}