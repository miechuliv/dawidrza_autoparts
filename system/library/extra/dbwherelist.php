<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 12:32
 * To change this template use File | Settings | File Templates.
 */

class DbWhereList {

    public $wheres;

    public function addWhere(DbWhere $where)
    {
        $this->wheres[] = $where;
    }
}