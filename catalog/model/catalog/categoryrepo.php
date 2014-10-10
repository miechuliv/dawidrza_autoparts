<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.01.14
 * Time: 14:05
 * To change this template use File | Settings | File Templates.
 */

class CatalogCategoryRepo {

    public $categories;

    function __construct($rows)
    {
            foreach($rows as $row)
            {
                $this->categories[] = new CatalogCategoryRow($row);
            }
    }

    public function addCategory(CatalogCategoryRow $category)
    {
        $this->categories[] = $category;
    }
}