<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 12:51
 * To change this template use File | Settings | File Templates.
 */

class DbSort {

    public $column;
    public $alias;
    public $order;

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function setColumn($column)
    {
        $this->column = $column;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }



}