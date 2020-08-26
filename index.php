<?php

// load local config
$localConfig = __DIR__ . "/my-config.php"; 
if (is_file($localConfig)) include($localConfig);

// Start Object Oriented Programming 
// with class Xoom
require __DIR__ . "/xoom/class/Xoom.php";
Xoom::start(__DIR__);

Test::log("hello");

