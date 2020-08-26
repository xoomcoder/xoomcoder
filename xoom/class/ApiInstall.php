<?php

/**
 * security: this is a public class
 */
class ApiInstall
{

    static function startSite ()
    {
        $doInstall = false;
        Form::$jsonsa["feedback"] = "...";

        $email      = Form::filterInput("email");
        if ($email != "") {
            // check if file exists xoom-data/install.php
            $installfile = Xoom::$rootdir . "/xoom-data/my-install.php";
            if (is_file($installfile)) {
                if (isset(Config::$installActivation)) {
                    Form::$jsonsa["feedback"] = "Sorry...";
                }
                else  {
                    $doInstall = true;
                }
            }
            else {
                $doInstall = true;
            }
            
            if ($doInstall) {
                Form::$jsonsa["feedback"] = "Installation en cours...";
                // https://www.php.net/manual/en/function.file-get-contents
                $code = file_get_contents(Xoom::$rootdir . "/xoom/etc/ex-install.php");

                // https://www.php.net/manual/fr/function.sha1.php
                // https://www.php.net/manual/fr/function.password-hash.php
                $adminkey = sha1(password_hash($email, PASSWORD_DEFAULT));   // generate random key

                $dicosa = [
                    "ADMIN_EMAIL"   => $email,
                    "ADMIN_KEY"     => password_hash($adminkey, PASSWORD_DEFAULT),
                ];
                $code = str_replace(array_keys($dicosa), array_values($dicosa), $code);

                // https://www.php.net/manual/en/function.file-put-contents
                file_put_contents($installfile, $code);

                $mailbody = 
                <<<x
                Hello,

                Your admin key est: 
                $adminkey

                Keep it safe !

                x;

                Email::send($email, "your site is ready", $mailbody);
            }
        }
    }

    static function activateAdmin ()
    {
        $email      = Form::filterInput("email");
        $adminkey   = Form::filterInput("adminkey");
        if (class_exists("Config") 
            && isset(Config::$adminEmail) 
            && ($email == Config::$adminEmail ?? "")) {
            if (isset(Config::$adminPH) && password_verify($adminkey, Config::$adminPH)) {
                $now = date("Y-m-d H:i:s");
                $newcode = 'static $installActivation = "' . $now . '";';   // bad mix... :-/
                $extracode =
                <<<x
                $newcode
                    //@end
                x;
    
                $installfile = Xoom::$rootdir . "/xoom-data/my-install.php";
                $code = file_get_contents($installfile);
                $dicosa = [
                    "//@end"    => $extracode,
                ];
                $code = str_replace(array_keys($dicosa), array_values($dicosa), $code);
                // https://www.php.net/manual/en/function.file-put-contents
                file_put_contents($installfile, $code);   
                
                Form::$jsonsa["feedback"] = "Activation ok...";

            }

        }
    }
}
