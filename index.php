<?php

$localfiles = [
    "my-code",
    "my-install",
];

// load local config
foreach($localfiles as $lf) {
    $localConfig = __DIR__ . "/xoom-data/$lf.php"; 
    if (is_file($localConfig)) include($localConfig);

}

// add composer modules
$composerAutoloader = __DIR__ . "/vendor/autoload.php";
if (is_file($composerAutoloader)) require $composerAutoloader;

// Start Object Oriented Programming 
// with class Xoom
require __DIR__ . "/xoom/class/Xoom.php";
Xoom::start(__DIR__);

