<?php
class ModelShippingApaczka extends Model {
	function getQuote($address) {
		$this->load->language('shipping/apaczka');

		if ($this->config->get('apaczka_status')) {
		$method_data = array();
		$quote_data = array();

		$quote_data['apaczka'] = array(
			'code'         => 'apaczka.apaczka',
			'title'        => $this->language->get('text_title'),
			'cost'         => $this->config->get('apaczka_shipment_price'),
			'text'         => $this->currency->format($this->config->get('apaczka_shipment_price')),
			'tax_class_id' => $this->config->get('apaczka_tax_class_id')
		);

		$method_data = array(
			'code'       => 'apaczka',
			'title'      => $this->language->get('text_title'),
			'quote'      => $quote_data,
			'sort_order' => $this->config->get('apaczka_sort_order'),
			'error'      => FALSE
		);

		return $method_data;
		}
	}
}
?>