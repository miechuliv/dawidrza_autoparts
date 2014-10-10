<?php
class ControllerModuleLatest extends Controller {
	protected function index($setting) {
		$this->language->load('module/latest');
		
      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
				
		$this->load->model('catalog/product');

        $this->load->model('catalog/product_quantity_discount');
		
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
		
		$data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProducts($data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}

            $percent = $this->model_catalog_product_quantity_discount->getAviableDiscountByProductId($result['product_id'],100000);

            if($percent)
            {

                $result['price'] = $result['price']*(1 + $percent/100);

            }
						
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				//$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                $price = $this->currency->format($result['price']);
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
			
			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/latest.tpl';
		} else {
			$this->template = 'default/template/module/latest.tpl';
		}

		$this->render();
	}
}
?>