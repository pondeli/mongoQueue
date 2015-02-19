<?php
/**
 * Created by PhpStorm.
 * User: we
 * Date: 10.2.15
 * Time: 12:49
 */

namespace MongoQueue;

/**
 * Class MongoQueue
 *
 * @package MongoQueue
 */
class MongoQueue extends \SplPriorityQueue
{

    private $collection;
    private $allowedItemStates = array();


    //@TODO přidat config misto statiky
    public static $QUEUE_STATE_CREATED = 0;
    public static $QUEUE_STATE_WAITING = 1;
    public static $QUEUE_STATE_RUNNING = 2;
    public static $QUEUE_STATE_TERMINATED = 3;
    public static $QUEUE_STATE_BLOCKED = 4;


    /**
     * @return array
     */
    public function getAllIitemStates()
    {
        return array(
            self::$QUEUE_STATE_RUNNING,
            self::$QUEUE_STATE_CREATED,
            self::$QUEUE_STATE_TERMINATED,
            self::$QUEUE_STATE_BLOCKED,
            self::$QUEUE_STATE_WAITING
        );
    }


    /**
     * @param array $states
     */
    public function setAllowedItemStates(Array $states)
    {
        $this->allowedItemStates = $states;
    }

    /**
     * metoda načte do fronty itemy z db
     */
    private function load()
    {
        $cursor = $this->collection->find();
        /**@var AItem $item*/
        foreach ($cursor as $itemData) {
            if(in_array($itemData['state'],$this->allowedItemStates)){
                $item = new Item();
                $item->setPriority($itemData['priority']);
                $item->getState($itemData['state']);
                $item->value = $itemData['value'];
                $this->insert($item, false);
            }
        }
    }

    /**
     * @param $nameDb
     */
    public function __construct($nameDb, $collection)
    {

        $this->allowedItemStates = $this->getAllIitemStates();
        try
        {
            $m = new \MongoClient(); // connect
            $db = $m->selectDB($nameDb);
            $this->collection = $db->$collection;
            $this->load();
        }
        catch ( \MongoConnectionException $e )
        {
            echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
            exit();
        }
    }

    /**
     * @param mixed $priority1
     * @param mixed $priority2
     * @return int
     */
    public function compare($priority1, $priority2)
    {
        //@TODO
        $result = parent::compare($priority1, $priority2);
        return $result;
    }


    /**
     * @return mixed
     */
    public function extract()
    {
        //@TODO
        $result = parent::extract();
        return $result;
    }


    /**
     * @param Item $item
     */
    public function insert(Item $item, $mongo = true)
    {
        parent::insert($item, $item->getPriority());
        if($mongo){
            $itemArray = \iterator_to_array($item);
            $this->collection->insert($itemArray);
        }
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        //@TODO
        $result = parent::isEmpty();
        return $result;
    }
}