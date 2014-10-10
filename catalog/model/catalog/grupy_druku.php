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
          `price` = '".(float)$data['price']."' ");

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

    /*
     * koszt druku produktu na podstawie atrybutu grupaDruku, oraz tabeli grupaDruku ( ilość sztuk i kolor )
     */
    public function getProductKosztDruku($product_id , $quantity, $colors, $per_item = 1)
    {
        
        $grupaDruku = $this->db->query("SELECT * FROM `".DB_PREFIX."product_attribute`
         WHERE product_id = '".(int)$product_id."' AND attribute_id = '2'  ");



        if($grupaDruku->num_rows)
        {

            $q = $this->db->query("SELECT * FROM `".DB_PREFIX."grupy_druku`
             WHERE `from` <= '".(int)$quantity."' AND
             `grupa_druku` = '".$this->db->escape($grupaDruku->row['text'])."' AND
              `colors` = '".(int)$colors."'
              ORDER BY `from` DESC ");

            if($q->num_rows && $q->row['per_item'] == $per_item)
            {
                return $q->row['price'];
            }
        }

        return 0;
    }



}