<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 14:17
 * To change this template use File | Settings | File Templates.
 */

class CatalogCategoryRow extends DbRow{

    public $top;
    public $category_description;
    public $ID;
    public $primaryKey = 'category_id';

    public $map = array(
        'top' => array(
            'type' => 'int',
            'column' => 'top',
            'relation' => false,
            'foreignTable' => false,
            'required' => false,
        ),
        'column' => array(
            'type' => 'int',
            'column' => 'column',
            'relation' => false,
            'foreignTable' => false,
            'required' => false,
        ),
        'description' => array(
            'type' => 'objArray',
            'column' => false,
            'relation' => 'category_id',
            'foreignTable' => 'category_description',
            'required' => true,
        ),
    );

    function __construct($row = false)
    {
        if($row)
        {
            $this->top = $row['top'];
            $this->column = $row['column'];
            $this->ID = $row['category_id'];


            $this->description[] = new CatalogCategorydescriptionRow(array(
                'name' => $row['name'],
                'category_id' => $row['category_id'],

            ));
        }

    }

    function addDescription(CatalogCategorydescriptionRow $row)
    {
        $this->category_description[] = $row;
    }




}