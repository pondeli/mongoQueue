<?php


include '/var/www/html/mongo/app/runner/SoapExampleRunner.php';

echo 'SoapExampleRunner';
$reflector = new \Runner\SoapExampleRunner();
//@TODO
var_dump($reflector->run());
