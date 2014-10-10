<?php
class ControllerModuleBrowseHistory extends Controller {
	protected function index($setting) {
		// load language
	  
		
		$this->language->load('module/browseHistory');
       
      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_more'] = $this->language->get('text_more');
		
		$this->load->model('module/browserHistory');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->data['grid']=3;
		$this->data['listing_cols']=$setting['limit'];
		
		
        // get history first
        $product_ids=array();
		// get ip
		$ip=$_SERVER['REMOTE_ADDR'];
		if(!$ip){
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		// get product ips
        $product_ids = $this->model_module_browserHistory->getBrowseHistoryByIp($ip,$setting['limit']);
		$results = array();
		
	
		
		$this->model_module_browserHistory->saveBrowseHistoryByIp($ip,$this -> request -> get['product_id'],$setting['limit']);
		// save current history
		
		if($product_ids){
		
			// get products by ip's
			foreach ($product_ids as $product_id) {
				$results[] = $this->model_catalog_product->getProduct($product_id);
				
			
			}
			
			
			$this->data['products'] = array();

		
		// get products data
		foreach ($results as $result) {
			
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}
			
			// miechu mode
			$results2 = $this->model_catalog_product->getProductImages($result['product_id']);
                if ( isset($results2[0]['image']) ) {
                   $additional_image = $this->model_tool_image->resize($results2[0]['image'] , $setting['image_width'], $setting['image_height']) ;
                } else { $additional_image = false ; }
			//


            // sciagamy najniższa możliwa cene wg. promocji ilosciowej
            $this->load->model('catalog/product_quantity_discount');



            $percent = $this->model_catalog_product_quantity_discount->getAviableDiscountByProductId($result['product_id'],100000,$result['use_own_quantity_discount']);


            if($percent)
            {


                // cena jest obliczana nieco inaczje niż pierwotnie
                // teraz quantity_discount oznacza marże a nie zniżke
                //$result['price'] = $result['price']*(1 - $percent/100);
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
				'additional_image' => $additional_image
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/browseHistory.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/browseHistory.tpl';
		} else {
			$this->template = 'default/template/module/browseHistory.tpl';
		}

		$this->render();
			
			
		}else{
			return false;
		}
        
		
	}
}
?>