<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 02.04.14
 * Time: 09:42
 * To change this template use File | Settings | File Templates.
 */

class ModelCatalogProductQuantityDiscount extends Model{

    public function addDiscount($product_id,$data)
    {
        $this->db->query("INSERT INTO `".DB_PREFIX."product_quantity_discount` SET
         `product_id` = '".(int)$product_id."',
         `from` = '".(int)$data['from']."',
         `to` = '".(int)$data['to']."',
         `percent` = '".(float)$data['percent']."'
          ");

        return $this->db->getLastId();
    }

    public function getDiscountByProductId($product_id)
    {
        $q = $this->db->query("SELECT * FROM `".DB_PREFIX."product_quantity_discount` WHERE
         product_id = '".(int)$product_id."'

          ");

        return $q->rows;
    }

    public function deleteDiscounts($product_id)
    {
        $this->db->query("DELETE FROM `".DB_PREFIX."product_quantity_discount` WHERE
         product_id = '".(int)$product_id."'

          ");
    }





}