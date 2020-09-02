<?php

class Test 
{
    static function log ($message)
    {
        Form::appendJson("debug_log", "$message\n");
    }
}