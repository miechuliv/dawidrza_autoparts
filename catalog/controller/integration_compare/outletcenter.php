<?php
class ControllerIntegrationCompareOutletcenter extends Controller {
	public function index() {
		if ($this->config->get('outletcenter_status')) {
			if ($this->request->get['password'] == $this->config->get('outletcenter_private_key')) {
				$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
				$output .= '<offers>';

				$this->load->model('catalog/category');

				$this->load->model('catalog/product');

				$this->load->model('tool/image');

				$products = $this->model_catalog_product->getProducts();

				foreach ($products as $product) {
                    $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), 'PLN', false, false);

					$output .= '<offer>';
					$output .= '<id>' . $product['product_id'] . '</id>';
					$output .= '<name><![CDATA[' . html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8') . ']]></name>';

					$categories = $this->model_catalog_product->getCategories($product['product_id']);

					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);
						
						if ($path) {
							$string = '';
							
							foreach (explode('_', $path) as $path_id) {
								$category_info = $this->model_catalog_category->getCategory($path_id);
								
								if ($category_info) {
									if (!$string) {
										$string = $category_info['name'];
									} else {
										$string .= '/' . $category_info['name'];
									}
								}
							}
						}
					}

					$output .= '<categoryId><![CDATA[' . html_entity_decode($string, ENT_QUOTES, 'UTF-8') . ']]></categoryId>';
					$output .= '<description><![CDATA[' . strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')) . ']]></description>';

					if ($product['image'])
						$output .= '<image><![CDATA[' . $this->model_tool_image->resize($product['image'], 500, 500) . ']]></image>';

					$output .= '<price>' . $price . '</price>';

					if ((float)$product['special']) {
						$special_price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), 'PLN', false, false);
						$output .= '<promo>' . $special_price . '</promo>';
						$output .= '<promocja></promocja>';
					}

					$output .= '<quantity>' . $product['quantity'] . '</quantity>';
					$output .= '</offer>';
				}

				$output .= '</offers>';

				$this->load->model('integration_compare/compareprices');
				$this->model_integration_compare_compareprices->saveLastDate('outletcenter');

				$this->response->addHeader('Content-Type: application/rss+xml');
				$this->response->setOutput($output);
			}
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);
	
		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}	
		
			$path = $this->getPath($category_info['parent_id'], $new_path);
					
			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}
}
?>