<?php

// https://www.php.net/manual/fr/control-structures.foreach.php
foreach($template as $file)
{
    // https://www.php.net/manual/fr/function.require-once.php
    require_once "xoom-templates/$file.php";
}