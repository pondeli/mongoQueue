<?php
/**
 * Created by PhpStorm.
 * User: we
 * Date: 1.2.15
 * Time: 11:57
 */

namespace Runner;


class Runner {

    /**@var \MongoQueue\MongoQueue */
    private $scriptQueue = NULL;


    /**
     * @param \MongoQueue\MongoQueue $queue
     */
    public function setScriptQueue(\MongoQueue\MongoQueue $queue)
    {
        $this->scriptQueue = $queue;
    }


    /**
     *
     */
    private function isValidItem()
    {
        //@TODO
    }

    public function isRunnable(\MongoQueue\Item $item)
    {
        $path = 'runner/scripts/'.$item->value;
        if(file_exists($path)){
            return true;
        }
        return false;
    }


    /**
     *
     */
    public function run()
    {
        while($this->scriptQueue->valid()){
            $itemx = (object) $this->scriptQueue->current();
            if($this->isRunnable($itemx)){
                $command = "nohup php runner/scripts/run.php $itemx->value >../temp/scripts/run.out 2>&1 &";
                $pid = exec($command);
            }
            $this->scriptQueue->next();
        }

    }
}