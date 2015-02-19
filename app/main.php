<?php
/**
 * Created by PhpStorm.
 * User: we
 * Date: 6.2.15
 * Time: 17:22
 */


require '../vendor/autoload.php';

$scriptQueue = new MongoQueue\MongoQueue('test','test');



$mongoClient = new \MongoClient();



//$mongoClient->test->test->drop();
//
//$item3 = new \MongoQueue\Item();
//$item3->setPriority(8);
//$item3->value = 'SoapExample.php';
//
//$scriptQueue->insert($item3);


$runner = new Runner\Runner();
$runner->setScriptQueue($scriptQueue);
$runner->run();