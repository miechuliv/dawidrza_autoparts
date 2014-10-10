<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 13.11.13
 * Time: 11:26
 * To change this template use File | Settings | File Templates.
 */

class ModelTotalKaucja extends Model{

    public function getTotal(&$total_data, &$total, &$taxes) {


        $products = $this->cart->getProducts();

        $kaucja = 0;

        foreach($products as $product)
        {
             $kaucja += $product['kaucja_cost'];
        }

        $total_data[] = array(
            'code'       => 'kaucja',
            'title'      => 'Altteilpfand',
            'text'       => $this->currency->format($kaucja),
            'value'      => $kaucja,
            'sort_order' => $this->config->get('kaucja_sort_order')
        );

        $total += $kaucja;
    }
}