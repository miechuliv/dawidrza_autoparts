<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 12:50
 * To change this template use File | Settings | File Templates.
 */

class DbJoin {

    public $type;
    public $tableName;
    public $alias;
    public $key;

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


}