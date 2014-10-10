<?php
abstract class Model {
	protected $registry;

    protected $tableName;
    protected $id;
    protected $defaultSort;
    protected $defaultJoins;
    protected $tableAlias;


    public function setTableAlias($tableAlias)
    {
        $this->tableAlias = $tableAlias;
    }

    


    public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function __get($key) {
		return $this->registry->get($key);
	}

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

    public function getOne($id,$select = false)
    {
        $sql = 'SELECT ';

        if($select)
        {
            $sql .= $select;
        }
        else
        {
            $sql .= ' * ';
        }

        $sql .= ' FROM '.DB_PREFIX.$this->tableName;


        $sql .= " WHERE `".$this->id."` = '".(int)$id."' ";

        $q = $this->db->query($sql);

        return $q->rows;

    }



    public function getMany(DbQBuilder $q)
    {
        $sql = 'SELECT * ';

       /* if($q->)
        {
            $sql .= $select;
        }
        else
        {
            $sql .= ' * ';
        } */

        $sql .= ' FROM '.DB_PREFIX.'`'.$this->tableName.'` '.$this->tableAlias;

        if($this->defaultJoins)
        {
            foreach($this->defaultJoins as $join)
            {
                $q->addJoin($join);
            }
        }

        if(!empty($q->joins))
        {
            foreach($q->joins as $join)
            {
                $sql .= ' '.$join->type.' JOIN `'.$join->tableName.'` '.$join->alias.' ON('.$this->tableAlias.'.'.$join->key.'='.$join->alias.'.'.$join->key.') ';
            }
        }

        if(!empty($q->wheres))
        {
            foreach($q->wheres as $key => $param)
            {
                if($key === 0)
                {
                    $sql .= ' WHERE ';


                }
                else
                {
                    $sql .= ' AND ';
                }

                if(isset($param->alias))
                {
                    $param->column = $param->alias.".`".$param->column ."`";
                }
                else
                {
                    $param->column  = "`".$param->column ."`";
                }

                if($param->type == 'string')
                {
                    $sql .= " ".$param->column." ".$param->relation." '".$this->db->escape($param->value)."' ";
                }
                elseif($param->type == 'float')
                {
                    $sql .= " ".$param->column." ".$param->relation." '".(float)$param->value."' ";
                }
                else
                {
                    $sql .= " ".$param->column." ".$param->relation." '".(int)$param->value."' ";
                }
            }
        }





        if($this->defaultSort)
        {
            foreach($this->defaultSort as $sort)
            {
                $q->addSorts($sort);
            }

        }

        if(!empty($q->sorts))
        {

            foreach($q->sorts as $key => $s)
            {

                if(isset($s->alias))
                {
                    $s->column = $s->alias.".`".$s->column."`";
                }
                else
                {
                    $s->column = "`".$s->column."`";
                }

                if($key === 0 )
                {
                    $sql .= " ORDER BY ".$s->column." ".$s->order;
                }
                else
                {
                    $sql .= ", ".$s->column." ".$s->order;
                }
            }

        }

        if($q->limit)
        {
            $sql .= " LIMIT " . (int)$q->limit->start . "," . (int)$q->limit->stop;
        }



        $q = $this->db->query($sql);


            return $q->rows;



    }

    public function save(DbRow $row)
    {
        $resp = array();

        if($row->ID==false)
        {
            $resp = $this->getOne($row->ID);
        }


        if(!empty($resp))
        {

            // update
            $sql = 'UPDATE '.DB_PREFIX.'`'.$this->tableName.'`';

        }
        else
        {
            // save
            $sql = 'INSERT INTO '.DB_PREFIX.'`'.$this->tableName.'`';
        }

        $fields = $row->map;

        if($fields)
        {
            $sql .= ' SET ';
        }

        $i = 0;

        foreach($fields as $key => $field)
        {
            if($i!==0)
            {
                if($field['type'] != 'objArray')
                {
                    $sql .= ' , ';
                }



            }
            else
            {
                $i++;
            }

            if($field['type'] == 'string')
            {
                $sql .= " `".$field['column']."` = '".$this->db->escape($row->$field['column'])."' ";
            }
            if($field['type'] == 'float')
            {
                $sql .= " `".$field['column']."` = '".(float)$row->$field['column']."' ";
            }
            if($field['type'] == 'int')
            {
                $sql .= " `".$field['column']."` = '".(int)$row->$field['column']."' ";
            }





        }

        if(!empty($resp))
        {

            // update
            $sql .= " WHERE `".$row->primaryKey."` = '".(int)$row->ID."'  ";

        }



        $this->db->query($sql);

        $id = $this->db->getLastId();

        /* foreach($fields as $field)
        {
            if($field['type'] == 'objArray')
            {
                foreach($row->$field['foreignTable'] as $row)
                {

                    $row->$field['relation'] = $id;
                    $this->save($row);
                }
            }
        } */

        return $id;


    }

    public function addDefaultSort($defaultSort)
    {
        $this->defaultSort[] = $defaultSort;
    }

    public function addDefaultJoins($defaultJoins)
    {
        $this->defaultJoins[] = $defaultJoins;
    }



}
?>