<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 02.04.14
 * Time: 09:42
 * To change this template use File | Settings | File Templates.
 */

class ModelCatalogCategoryQuantityDiscount extends Model{

    public function addDiscount($category_id,$data)
    {
        $this->db->query("INSERT INTO `".DB_PREFIX."category_quantity_discount` SET
         `category_id` = '".(int)$category_id."',
         `from` = '".(int)$data['from']."',
         `to` = '".(int)$data['to']."',
         `percent` = '".(float)$data['percent']."'
          ");

        return $this->db->getLastId();
    }

    public function getDiscountByCategoryId($category_id)
    {
        $q = $this->db->query("SELECT * FROM `".DB_PREFIX."category_quantity_discount` WHERE
         category_id = '".(int)$category_id."'

          ");

        return $q->rows;
    }

    public function deleteDiscounts($category_id)
    {
        $this->db->query("DELETE FROM `".DB_PREFIX."category_quantity_discount` WHERE
         category_id = '".(int)$category_id."'

          ");
    }





}