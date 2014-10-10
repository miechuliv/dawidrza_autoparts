<?php
class ModelTotalCategoryDiscount extends Model {

    /*private function aasort (&$array, $key) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret;
    }*/

	public function getTotal(&$total_data, &$total, &$taxes) {

			$this->language->load('total/category_discount');
        $this->load->model('catalog/product');
			
			$discounts = $this->config->get('category_discount_discounts');

            $products = $this->cart->getProducts();

        $discount_total = 0;

        $sub_total = $this->cart->getSubTotal();



			
			if ($discounts) {

                //$this->aasort($discounts,'order');

                foreach($products as $product)
                {
                    $product_categories = $this->model_catalog_product->getCategories($product['product_id']);

                    $cats = array();



                    foreach($product_categories as $category)
                    {
                        $cats[] = $category['category_id'];
                    }

                    $s_total = 0;

                     foreach($discounts as $discount)
                     {


                            if(in_array($discount['category_id'],$cats))
                            {

                                // dodajemy zniżkę do tego produktu
                                $d = 0;

                                if ($discount['type'] == 'flat') {
                                    $d = $discount['value'] * ($product['total'] / $sub_total);
                                } elseif ($discount['type'] == 'percent') {
                                    $d = $product['total'] / 100 * $discount['value'];
                                }

                                if ($product['tax_class_id']) {
                                    $tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $d), $product['tax_class_id']);

                                    foreach ($tax_rates as $tax_rate) {
                                        if ($tax_rate['type'] == 'P') {
                                            $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                                        }
                                    }
                                }

                                if($d > $s_total)
                                {
                                    $s_total = $d;
                                }


                            }
                     }

                    $discount_total += $s_total;
                }


      			
				$total_data[] = array(
					'code'       => 'category_discount',
        			'title'      => $this->language->get('text_category_discount'),
	    			'text'       => $this->currency->format(-$discount_total),
        			'value'      => -$discount_total,
					'sort_order' => $this->config->get('category_discount_sort_order')
      			);

				$total -= $discount_total;
			} 

	}
	
	public function confirm($order_info, $order_total) {

	}
}
?>