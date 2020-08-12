<?php

// ici on mettra le code pour déclencher la commande git pull
// https://www.php.net/manual/fr/function.passthru.php
$commande = "git pull";

passthru($commande);