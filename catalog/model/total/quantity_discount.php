<?php
class ModelTotalQuantityDiscount extends Model {

    /**
     * jak to ma teraz działać skoro zniżka ilościowa zaminiła się w marże ilościową ?
     */
	public function getTotal(&$total_data, &$total, &$taxes) {

        $total_discount = 0;

        $this->load->language('total/quantity_discount');

            foreach($this->cart->getProducts() as $product)
            {
                    $q = $this->db->query("SELECT * FROM `".DB_PREFIX."product_quantity_discount` WHERE
                     `from` <= '".(int)$product['quantity']."' AND product_id = '".(int)$product['product_id']."'
                     ORDER BY `from` DESC  ");

                $discount = 0;

                  if($q->num_rows)
                  {
                      $discount = ($product['price']*$product['quantity'])*($q->row['percent']/100);

                      if ($product['tax_class_id']) {
                          $tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

                          foreach ($tax_rates as $tax_rate) {
                              if ($tax_rate['type'] == 'P') {
                                  $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                              }
                          }
                      }
                  }
                  elseif($conf = $this->config->get('quantity_discount_values'))
                  {
                      $ar = array();
                      foreach($conf as $quantity_discount)
                      {
                           if($quantity_discount['from'] <= $product['quantity'] )
                           {
                               $ar[$quantity_discount['from']] = $quantity_discount['percent'];
                           }
                      }

                      krsort($ar);

                      $percent = array_shift($ar);

                      $discount = ($product['price']*$product['quantity'])*($percent/100);

                      if ($product['tax_class_id']) {
                          $tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

                          foreach ($tax_rates as $tax_rate) {
                              if ($tax_rate['type'] == 'P') {
                                  $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
                              }
                          }
                      }
                  }

                $total_discount += $discount;
            }

			$total_data[] = array(
				'code'       => 'quantity_discount',
        		'title'      => $this->language->get('text_title'),
        		'text'       => $this->currency->format($total_discount),
        		'value'      => $total_discount,
				'sort_order' => $this->config->get('quantity_discount_sort_order')
			);


			
			$total -= $total_discount;

	}
}
?>