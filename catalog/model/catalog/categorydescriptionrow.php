<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 15:37
 * To change this template use File | Settings | File Templates.
 */

class CatalogCategorydescriptionRow extends DbRow{


    public $name;
    public $ID;
    public $category_id;
    public $primaryKey = 'category_description_id';

    public $map = array(
        'name' => array(
            'type' => 'string',
            'column' => 'name',
            'relation' => false,
            'foreignTable' => false,
            'required' => true,
        ),

    );

    function __construct($row = false)
    {
        $this->name = $row['name'];
        $this->ID = $row['category_id'];
    }


}