<?php

// Start Object Oriented Programming 
// with class Xoom
require __DIR__ . "/xoom/class/Xoom.php";
Xoom::start();
Test::log("hello");

// https://www.php.net/manual/fr/control-structures.foreach.php
foreach($template as $file)
{
    // https://www.php.net/manual/fr/function.require-once.php
    require_once "xoom-templates/$file.php";
}