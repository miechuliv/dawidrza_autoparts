<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 12:52
 * To change this template use File | Settings | File Templates.
 */

class DbLimit {

    public $start;
    public $stop;

    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    public function setStop($stop)
    {
        $this->stop = $stop;
        return $this;
    }



}