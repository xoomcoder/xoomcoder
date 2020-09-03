<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-03 21:05:06
 * licence: MIT
 */

class ApiMember
{
    static function run ()
    {
        $now = date("Y-m-d H:i:s");
        Form::setFeedback("Bienvenue... $now");

    }
    //@end
}