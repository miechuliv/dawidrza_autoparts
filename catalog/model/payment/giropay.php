<?php

/**
 * giropay.de payment gateway for Opencart by Extensa Web Development
 *
 * Copyright © 2012-2013 Extensa Web Development Ltd. All Rights Reserved.
 * This file may not be redistributed in whole or significant part.
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 * @author 		Extensa Web Development Ltd. (www.extensadev.com)
 * @copyright	Copyright (c) 2012-2013, Extensa Web Development Ltd.
 * @package 	giropay.de payment gateway
 * @link		http://www.opencart.com/index.php?route=extension/extension/info&extension_id=8683
 */

class ModelPaymentGiropay extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/giropay');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('giropay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('giropay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('giropay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'giropay',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('giropay_sort_order')
			);
		}

		return $method_data;
	}
}
?>