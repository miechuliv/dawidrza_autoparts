<?php
//-----------------------------------------
// Shipping Poczta Polska Ekonomiczna Opencart Module
// Version: 1.4.7 - 1.5.x
// Author: Altkomp PHU
// License: Commercial
// Email: biuro@altkomp.pl
// http://www.altkomp.pl/
//-----------------------------------------
class ModelShippingPocztaekonom extends Model {
	private $name = '';

	public function getQuote($address) {
		$this->name = basename(__FILE__, '.php');
        $this->load->language('shipping/' . $this->name);

        $this->load->language('shipping/pocztaekonom');

		$geozones = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");

		$quote_data = array();

		foreach ($geozones->rows as $result) {
   			if ($this->config->get('pocztaekonom_' . $result['geo_zone_id'] . '_status')) {

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

				if ($query->num_rows) {
       				$status = TRUE;
   				} else {
       				$status = FALSE;
   				}
			} else {
				$status = FALSE;
			}

			# Address filter Check. If "Geozone Description" has a ~ in it, then verify by the address keyword (postcode, city, address_1, etc)
			if ($status) {

				$keyword = $this->config->get('pocztaekonom_' . $result['geo_zone_id'] . '_filter');

				if ($keyword) {
					if (strpos($result['description'], '~') !== false) {

						$match = 0;
						$filters = explode(',', str_replace('~', '', $result['description']));
						foreach($filters as $filter) {
							if (stripos($address[$keyword], trim($filter)) === false) { //if the value doesn't match - status false
								$status = false;
							} elseif (stripos($address[$keyword], trim($filter)) > 0) { //if the value doesn't match at the first position - status false
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