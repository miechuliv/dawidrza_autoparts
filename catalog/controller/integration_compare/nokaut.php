<?php
class ControllerIntegrationCompareNokaut extends Controller {
	public function index() {
		if ($this->config->get('nokaut_status')) {
			if ($this->request->get['password'] == $this->config->get('nokaut_private_key')) {
				$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
				$output .= '<!DOCTYPE nokaut SYSTEM "http://www.nokaut.pl/integracja/nokaut.dtd">';
				$output .= '<nokaut><offers>';

				$this->load->model('catalog/category');

				$this->load->model('catalog/product');

				$this->load->model('tool/image');

				$products = $this->model_catalog_product->getProducts();

				foreach ($products as $product) {
					$product = $this->clear($product);
					$avail = ($product['quantity'] > 0) ? '0' : '1';

					if ((float)$product['special'])
                        $price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), 'PLN', false, false);
                    else
                        $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), 'PLN', false, false);

					$output .= '<offer>';
					$output .= '<id>' . $product['product_id'] . '</id>';
					$output .= '<name><![CDATA[' . $product['name'] . ']]></name>';
					$output .= '<description><![CDATA[' . $product['description'] . ']]></description>';
					$output .= '<url>' . str_replace('&', '&amp;', $this->url->link('product/product', 'product_id=' . $product['product_id'])) . '</url>';

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
										$string .= ' / ' . $category_info['name'];
									}
								}
							}
						}
					}

					if ($product['image'])
						$output .= '<image>' . $this->model_tool_image->resize($product['image'], 500, 500) . '</image>';

					$output .= '<price>' . $price . '</price>';
					$output .= '<category><![CDATA[' . $string . ']]></category>';
					$output .= '<producer><![CDATA[' . $product['manufacturer'] . ']]></producer>';
					$output .= '<availability>' . $avail . '</availability>';
					$output .= '</offer>';
				}

				$output .= '</offers></nokaut>';

				$this->load->model('integration_compare/compareprices');
				$this->model_integration_compare_compareprices->saveLastDate('nokaut');

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

	protected function clear($data) {
		foreach ($data as $key => $value) {
			$data[$key] = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
			$data[$key] = strip_tags($data[$key]);
			$data[$key] = preg_replace(array('/(\s){1,100}/us', '/&nbsp;/', '/\r\n/', '/\n\r/', '/\n/'), array(' ', ' ', '', '', ''), $data[$key]);
			$data[$key] = trim($data[$key]);
		}

		return $data;
	}
}
?>