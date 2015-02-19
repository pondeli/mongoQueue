<?php
/**
 * Created by PhpStorm.
 * User: we
 * Date: 10.2.15
 * Time: 15:17
 */


/**
 * Class MongoQueueTest
 * php vendor/bin/phpunit tests/
 */
class MongoQueueTest extends PHPUnit_Framework_TestCase{


    private static $nameDb = '';
    private static $nameCollection = '';
    /**@var \MongoClient */
    private static $mongoClient = NULL;
    /**@var \MongoQueue\MongoQueue */
    private $testQueue = NULL;


    public static function setUpBeforeClass()
    {
        //vola se na zacatku testovani
        self::$nameDb = self::generateRandomString();
        self::$nameCollection = self::generateRandomString();
        self::$mongoClient = new MongoClient();
    }


    public static function tearDownAfterClass()
    {
        //vola se na konci testovani
        self::$nameDb = '';
        self::$nameCollection = '';
    }

    /**
     * @param int $length
     * @return string
     */
    private static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function dbExist()
    {
        $list = self::$mongoClient->listDBs();
        foreach($list['databases'] as $key => $value)
        {
            if($list['databases'][$key]['name'] === self::$nameDb){
                return true;
            }
        }
        return false;
    }

    public function setUp()
    {
        // volána PŘED každým jednotlivým testem
        $this->testQueue = new \MongoQueue\MongoQueue(self::$nameDb, self::$nameCollection);
    }


    public function tearDown()
    {
        // volána PO každým jednotlivým testem
        $this->testQueue = NULL;
        if($this->dbExist()){
            self::$mongoClient->{self::$nameDb}->drop();
        }
    }


    //**********************************************************************************************************
    //**********************************************************************************************************
    //**********************************************************************************************************

    /**
     * @expectedException MongoException
     */
    public function testExceptionCreateQueue()
    {
        $testQueue = new \MongoQueue\MongoQueue('testQueueDb', '.');
    }


    public function testCreateDatabase()
    {
        $this->assertEquals(true, $this->dbExist());
    }

    public function testInsert()
    {
        $item = new \MongoQueue\Item();
        $item->setPriority(1);
        $item->value = 'a';
        $this->testQueue->insert($item);

        $itemx = (object) self::$mongoClient->{self::$nameDb}->{self::$nameCollection}->findOne();

        $this->assertEquals($item->getPriority(), $itemx->priority);
        $this->assertEquals($item->value, $itemx->value);
        $this->assertEquals($item->getState(), $itemx->state);
    }


    private function createItems()
    {
        $item2 = new \MongoQueue\Item();
        $item2->setPriority(3);
        $item2->value = 'c';

        $item = new \MongoQueue\Item();
        $item->setPriority(1);
        $item->value = 'a';

        $item1 = new \MongoQueue\Item();
        $item1->setPriority(2);
        $item1->value = 'b';

        $item3 = new \MongoQueue\Item();
        $item3->setPriority(8);
        $item3->value = 'd';

        $this->testQueue->insert($item);
        $this->testQueue->insert($item2);
        $this->testQueue->insert($item3);
        $this->testQueue->insert($item1);
    }


    public function testLoad()
    {
        $this->createItems();
        $newTestQueue = new \MongoQueue\MongoQueue(self::$nameDb, self::$nameCollection);

        $beforeItemPriority = 10000;
        while($newTestQueue->valid()){
            $itemx = (object) $newTestQueue->current();
            $this->assertEquals(true, $itemx->getPriority() < $beforeItemPriority);
            $beforeItemPriority = $itemx->getPriority();
            $newTestQueue->next();
        }
    }
}