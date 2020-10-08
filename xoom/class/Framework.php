<?php
/**
 * author:  Long Hai LH
 * date:    2020-10-08 19:52:41
 * licence: MIT
 */

class Framework
{
    static $asteps  = [];
    static $consons = [];
    static $voyels  = [];
    static $start   = 0;
    static $end     = 10000;
    static $step    = 100;

    static function start ()
    {
        Framework::$consons = str_split("bcdfghjklmnpqrstvwxz");
        Framework::$voyels = str_split("aeiou");

        Framework::add("bava", "Request::parse");
        Framework::add("bavu", "Response::send");
        // run all todos
        Framework::run();
    }

    static function add ($key, $todo, $name="") {
        if (($key != "") && ($todo != "")) {
            if ($name == "") $name = $todo;
            Framework::$asteps[$key][$name] = $todo;
        }
    }

    static function run ()
    {
        for($s=Framework::$start; $s<Framework::$end; $s+=Framework::$step) {
            $key = Framework::step2key($s);
            if ($todos = Framework::$asteps[$key] ?? false) {
                foreach($todos as $name => $todo) {
                    if (is_callable($todo)) {
                        $todo();
                    }
                }
            }
        }
    }

    static function step2key ($step)
    {
        $res = "";
        $current = intval($step);
        while($current > 0) {
            $mod    = $current % 100;
            $c      = $mod % 20;
            $conson = Framework::$consons[$c];
            $voyel  = Framework::$voyels[($mod - $c) / 20];

            $res .= "$conson$voyel";

            $current = ($current - $mod) / 100 ;
        }
        if ($step == 0)  $res  = "baba";
        elseif ($step < 100) $res .= "ba";

        return $res;
    }

    //@end
}