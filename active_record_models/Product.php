<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 04.04.14
 * Time: 10:38
 * To change this template use File | Settings | File Templates.
 */
class Product extends ActiveRecord\Model
{
    // explicit table name since our table is not "books"
    static $table_name = 'product';

    // explicit pk since our pk is not "id"
    static $primary_key = 'product_id';

   


}
