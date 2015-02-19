<?php
/**
 * Created by PhpStorm.
 * User: we
 * Date: 14.2.15
 * Time: 11:53
 */

namespace MongoQueue;


class Item extends AItem implements \IteratorAggregate {


    public function __construct()
    {
        $this->state = \MongoQueue\MongoQueue::$QUEUE_STATE_CREATED;
    }

    public function getIterator() {
        return new \ArrayIterator(array(
            'priority' => $this->priority,
            'state' => $this->state,
            'value' => $this->value
        ));
    }
}