<?php

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    static function sendPlain ($to, $subject, $message)
    {
        // ionos hosting put bad labels... 
        $headers = [];
        if (class_exists("Config") && isset(Config::$adminEmail)) {
            $headers = [
                'From'      => Config::$adminEmail,
                'Reply-To'  => Config::$adminEmail,
                'X-Mailer' => 'PHP/' . phpversion(),    // does it help not be tagged as spam ???
            ];
        }

        // https://www.php.net/manual/fr/function.mail.php
        @mail($to, $subject, $message, $headers);
    }

    static function send ($to, $subject, $message)
    {
        // remove html tags
        $messagePlain = strip_tags($message);

        $mail = new PHPMailer();
        // FIXME: add config 
        $mail->setFrom(Config::$adminEmail ?? "no-reply@xoomcoder.com", "XoomCoder.com");
        $mail->addReplyTo(Config::$adminEmail ?? "no-reply@xoomcoder.com", "XoomCoder.com");

        $mail->addAddress($to);               // Name is optional
        
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = $messagePlain;
        
        $mail->send();
        
    }

}


