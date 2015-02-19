<?php
/**
 * Created by PhpStorm.
 * User: we
 * Date: 10.2.15
 * Time: 14:43
 */

namespace MongoQueue;


class AItem {


    protected $priority;
    protected $state;
    public $value;


    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }


    /**
     * @param Int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }


    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}