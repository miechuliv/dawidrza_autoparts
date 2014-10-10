<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 12:32
 * To change this template use File | Settings | File Templates.
 */

class DbQBuilder {

    public $wheres;
    public $joins;
    public $sorts;
    public $limit;

    public function addWhere(DbWhere $where)
    {
        $this->wheres[] = $where;
    }

    public function addJoin(DbJoin $join)
    {
        $this->joins[] = $join;
    }

    public function addSorts(DbSort $sort)
    {
        $this->sorts[] = $sort;
    }

    public function setLimit(DbLimit $limit)
    {
        $this->limit = $limit;
    }


}