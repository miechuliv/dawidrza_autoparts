<?php
class ModelTotalGrupyDruku extends Model {



	public function getTotal(&$total_data, &$total, &$taxes) {

			$this->language->load('total/grupy_druku');
            $this->load->model('catalog/product');
            $this->load->model('catalog/grupy_druku');


            $products = $this->cart->getProducts();



			
			if ($products) {

                //$this->aasort($discounts,'order');
                $result = 0;
                $l_kolorow = 0;


                foreach($products as $product)
                {

                    foreach ($product['option'] as  $value) {
                        $opt_q = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$value['product_option_id']. "' AND po.product_id = '" . (int)$product['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
                        $opt_val_q = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value['product_option_value_id'] . "' AND pov.product_option_id = '" . (int)$value['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                        if($opt_q->row['option_id'] == 2)
                        {
                            $l_kolorow = $opt_val_q->row['name'];
                        }
                    }

                        // najpierw pruba obliczenia kosztów druku od sztuki
                    $subResult = $this->model_catalog_grupy_druku->getProductKosztDruku($product['product_id'],$product['quantity'],$l_kolorow,1);

                    if($subResult)
                    {
                        $result += $subResult*$product['quantity'];
                    }
                    else
                    {
                        // jeżeli nie mozna obliczyć ceny od sztuki to prubujemy obliczyc ryczałt ( stała stawke )
                        $subResult = $this->model_catalog_grupy_druku->getProductKosztDruku($product['product_id'],$product['quantity'],$l_kolorow,0);

                        if($subResult)
                        {
                            $result += $subResult;
                        }

                    }






                }

                if($result > 0)
                {
                    $total_data[] = array(
                        'code'       => 'grupy_druku',
                        'title'      => $this->language->get('text_grupy_druku'),
                        'text'       => $this->currency->format($result),
                        'value'      => $result,
                        'sort_order' => $this->config->get('grupy_druku_sort_order')
                    );

                    $total += $result;
                }
      			

			} 

	}
	
	public function confirm($order_info, $order_total) {

	}
}
?>