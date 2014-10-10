<?php
class ModelShippingkex extends Model {
	function getQuote($address) {
		$this->load->language('shipping/kex');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('kex_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('kex_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();
	
		if ($status) {
			$cost = 0;
			$weight = $this->cart->getWeight();
			
			$rates = explode(',', $this->config->get('kex_rate'));
			
			foreach ($rates as $rate) {
  				$data = explode(':', $rate);
  					
				if ($data[0] >= $weight) {
					if (isset($data[1])) {
    					$cost = $data[1];
					}
					
   					break;
  				}
			}
			
			$quote_data = array();
			
			if ((float)$cost) {
				$quote_data['kex'] = array(
        			'code'         => 'kex.kex',
        			'title'        => $this->language->get('text_title') . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
        			'cost'         => $cost,
        			'tax_class_id' => $this->config->get('kex_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('kex_tax_class_id'), $this->config->get('config_tax')))
      			);
				
      			$method_data = array(
        			'code'       => 'kex',
        			'title'      => $this->language->get('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => $this->config->get('kex_sort_order'),
        			'error'      => false
      			);
			}
		}
	
		return $method_data;
	}
}
?>ition - status false
								$status = false;
							} else {
								$status = true;
								break;
							}
						}
					} elseif (strpos($result['description'], '!') !== false) { // If "Geozone Description" has a ! in it, then exclude by keyword
						$match = 0;
						$filters = explode(',', str_replace('!', '', $result['description']));
						foreach($filters as $filter) {
							if (stripos($address[$keyword], trim($filter)) === false) { //if the value doesn't match - status true
								$status = true;
							} elseif (stripos($address[$keyword], trim($filter)) > 0) { //if the value doesn't match at the first position - status false
								$status = true;
							} else {
								$status = false;
								break;
							}
						}
					}
				}
			}//

			if ($status) {

				$inRange = false;
				$cost = 0;

				// set the unit of calculation to $value (weight, subtotal, or itemcount)
				if ($this->config->get($this->name . '_' . $result['geo_zone_id'] . '_method') == 'itemcount'){
					$value = 0;
					foreach ($this->cart->getProducts() as $product) {
						if ($product['shipping']) {
							//$value++;
							$value += $product['quantity'];
						}
					}
					//$value = $this->cart->countProducts(); // this doesn't take into account products with shipping set to NO
				} elseif ($this->config->get($this->name . '_' . $result['geo_zone_id'] . '_method') == 'subtotal'){
					$value = $this->cart->getSubtotal();
				} else { // default to weight-based
					$value = $this->cart->getWeight();
				}

				// set the operator based on whether its incremental or decremental
				if ($this->config->get($this->name . '_' . $result['geo_zone_id'] . '_calc') == 'dec') {
					$op = '>=';
					$sort_method = 'dec';
				} else { //default to inc
					$op = '<=';
					$sort_method = 'inc';
				}

				$rates = explode(',', $this->config->get($this->name . '_' . $result['geo_zone_id'] . '_cost'));
				if ($sort_method == 'dec') { rsort($rates, SORT_NUMERIC); }
				foreach ($rates as $rate) {
					$array = explode(':', $rate);
					if (!empty($array) && isset($array[0]) && isset($array[1]) && $array[0] != '' && @(string)$array[1] != '') {
						if (eval("return($value $op $array[0]);")) {
							$cost = @$array[1];
							if (strpos($cost, '%')) {
								$cost = trim($cost, '%');
								$cost = $this->cart->getSubtotal() * ($cost/100);
							}
							$inRange = true;
							break;
						}
					}
				}

				if ($inRange == true) {
					$quote_data[$this->name . '_' . $result['geo_zone_id']] = array(
						'id'    		=> $this->name . '.' . $this->name . '_' . $result['geo_zone_id'], //v14x
						'code'    		=> $this->name . '.' . $this->name . '_' . $result['geo_zone_id'], //v15x
						//'code'    		=> 'kurier',
						'title' 		=> $this->language->get('text_title'),
						
						'cost'  		=> $cost,
						'tax_class_id' 	=> $this->config->get($this->name . '_tax_class_id'),
						'text'  		=> $this->currency->format($this->tax->calculate($cost, $this->config->get($this->name . '_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
		}


		$method_data = array();

		if ($quote_data) {
      		$method_data = array(
        		'id'         => $this->name, //v14x
                'code'       => $this->name, //v15x
        		'title'      => $this->language->get('text_title'),
        		
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get($this->name . '_sort_order'),
        		'error'      => FALSE
      		);
		}

		return $method_data;
  	}
}
?>