<?php
/**
 * author:  Long Hai LH
 * date:    2020-10-08 19:52:41
 * licence: MIT
 */

class Framework
{
    static function start ()
    {
        Request::parse();
        Response::send();

    }
    //@end
}