<?php

// DUMMY Email class
class EmailDev
{
    static $emails = [];

    static function send ($to, $subject, $message)
    {
        // https://www.php.net/manual/fr/function.mail.php
        // @mail($to, $subject, $message);
        EmailDev::$emails[] = 
        <<<x
        to: $to
        subject: $subject
        $message

        x;

        Form::$jsonsa["mails"] = EmailDev::$emails;
    }
}