<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 31.07.13
 * Time: 11:53
 * To change this template use File | Settings | File Templates.
 */

class ModelSpecyficCodes extends Model{


      public function addCode($product_id,$codes)
      {
           foreach($codes as $code)
           {
               $this->db->query("INSERT INTO `product_to_code` SET product_id='".(int)$product_id."', code='".$code."' ");

           }
      }

    public function addEngineCode($product_id,$codes)
    {
        foreach($codes as $code)
        {
            $this->db->query("INSERT INTO `product_to_engine` SET product_id='".(int)$product_id."', engine_code='".$code."' ");

        }
    }
}