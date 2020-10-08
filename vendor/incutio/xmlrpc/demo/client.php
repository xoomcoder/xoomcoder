<?php

require __DIR__ . '/../src/Incutio/Autoloader.php';
\Incutio\Autoloader::register();

use Incutio\XMLRPC\Client\Base as IXR_Client;

$client = new IXR_Client('http://scripts.incutio.com/xmlrpc/simpleserver.php');

if (!$client->query('test.getTime')) {
   die('An error occurred - '.$client->getErrorCode().":".$client->getErrorMessage());
}
print $client->getResponse();
// Prints the current time, according to our web server

if (!$client->query('test.add', 4, 5)) {
   die('An error occurred - '.$client->getErrorCode().":".$client->getErrorMessage());
}
print $client->getResponse();
// Prints '9'

if (!$client->query('test.addArray', array(3, 5, 7))) {
   die('An error occurred - '.$client->getErrorCode().":".$client->getErrorMessage());
}
print $client->getResponse();
// Prints '3 + 5 + 7 = 15'