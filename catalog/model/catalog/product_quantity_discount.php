<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 02.04.14
 * Time: 09:42
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class ModelCatalogProductQuantityDiscount
 * Wyliczanie ceny produktu zależnie od kupowanej ilości
 * W pierwszej wersji działało to na zasadzie znizke np:
 * ilosc , znizka w procentach
 * 100 , 10,
 * 200 ,15,
 * 500 i wiecej, 20
 * itp.
 * Teraz działa to inaczej: procent nie okresla znizki ale raczje marze sklepu na cene bazowo , niższa marża oznacza wyswietlenie nizszej ceny np:
 * ilosc , marza w procentach
 * 0-100 , 100%
 * 200-500, 90%
 * 500 i wiecej , 80%
 * itp.
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

    public function getAviableDiscountByProductId($product_id,$quantity,$use_own_quantity_discount = 0)
    {
        $q = $this->db->query("SELECT * FROM `".DB_PREFIX."product_quantity_discount` WHERE
                     `from` <= '".(int)$quantity."' AND product_id = '".(int)$product_id."'
                     ORDER BY `from` DESC  ");

        if(!$use_own_quantity_discount)
        {
            $cats = $this->db->query("SELECT DISTINCT * FROM `".DB_PREFIX."product_to_category` ptc
            LEFT JOIN category_path cp ON(ptc.category_id = cp.category_id)
            LEFT JOIN `".DB_PREFIX."category_quantity_discount` cqd ON (cp.category_id = cqd.category_id)
              WHERE ptc.product_id = '".(int)$product_id."'
              AND cqd.from > 0
              AND cqd.to > 0
              ORDER BY cp.level DESC");

            if(!empty($cats->row))
            {
                $ar = array();
                $quantity_discount_category_id = $cats->row['category_id'];

                foreach($cats->rows as $quantity_discount)
                {
                    if($quantity_discount_category_id != $quantity_discount['category_id'])
                    {
                        break;
                    }

                    if($quantity_discount['from'] <= $quantity )
                    {
                        $ar[$quantity_discount['from']] = $quantity_discount['percent'];
                    }

                }

                if(!empty($ar))
                {
                    krsort($ar);

                    return array_shift($ar);
                }
            }
            elseif($conf = $this->config->get('quantity_discount_values'))
            {
                $ar = array();
                foreach($conf as $quantity_discount)
                {
                    if($quantity_discount['from'] <= $quantity )
                    {
                        $ar[$quantity_discount['from']] = $quantity_discount['percent'];
                    }
                }

                if(!empty($ar))
                {
                    krsort($ar);

                    return array_shift($ar);
                }

            }



        }
        else
        {
            if($q->num_rows)
            {
                return $q->row['percent'];
            }
            elseif($conf = $this->config->get('quantity_discount_values'))
            {
                $ar = array();
                foreach($conf as $quantity_discount)
                {
                    if($quantity_discount['from'] <= $quantity )
                    {
                        $ar[$quantity_discount['from']] = $quantity_discount['percent'];
                    }
                }

                if(!empty($ar))
                {
                    krsort($ar);

                    return array_shift($ar);
                }

            }
        }



        return false;
    }

    public function deleteDiscounts($product_id)
    {
        $this->db->query("DELETE FROM `".DB_PREFIX."product_quantity_discount` WHERE
         product_id = '".(int)$product_id."'

          ");
    }





}