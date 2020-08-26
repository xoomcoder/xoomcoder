<?php

class Email
{
    static function send ($to, $subject, $message)
    {
        // ionos hosting put bad labels... 
        $headers = [];
        if (class_exists("Config") && isset(Config::$adminEmail)) {
            $headers = [
                'From'      => Config::$adminEmail,
                'Reply-To'  => Config::$adminEmail,
            ];
        }

        // https://www.php.net/manual/fr/function.mail.php
        @mail($to, $subject, $message, $headers);
    }
}