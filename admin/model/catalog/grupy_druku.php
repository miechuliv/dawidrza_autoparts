<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 07.04.14
 * Time: 14:01
 * To change this template use File | Settings | File Templates.
 */

class ModelCatalogGrupyDruku extends Model{

    public function saveGrupyDruku($data)
    {
        $this->db->query("INSERT INTO  `".DB_PREFIX."grupy_druku` SET
         `grupa_druku` = '".$this->db->escape($data['grupa_druku'])."',
         `from` = '".(int)$data['from']."',
          `to` = '".(int)$data['to']."',
          `colors` = '".(int)$data['colors']."',
          `price` = '".(float)$data['price']."',
           `per_item` = '".(int)$data['per_item']."'  ");

        return $this->db->getLastId();
    }

    public function deleteGrupyDruku()
    {
        $this->db->query("DELETE FROM  `".DB_PREFIX."grupy_druku` ");
    }

    public function getGrupyDruku()
    {
        $q = $this->db->query("SELECT * FROM `".DB_PREFIX."grupy_druku` ");

        return $q->rows;
    }

}