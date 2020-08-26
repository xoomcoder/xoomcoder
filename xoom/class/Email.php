<?php

class Email
{
    static function send ($to, $subject, $message)
    {
        // https://www.php.net/manual/fr/function.mail.php
        @mail($to, $subject, $message);
    }
}