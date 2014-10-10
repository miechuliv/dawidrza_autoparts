<?php
class ControllerIntegrationCompareSkapiec extends Controller {
	public function index() {
		if ($this->config->get('skapiec_status')) {
			if ($this->request->get['password'] == $this->config->get('skapiec_private_key')) {
				$output  = '<?xml version="1.0" encoding="UTF-8" ?>';

				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
					$root = $this->config->get('config_ssl');
				else
					$root = $this->config->get('config_url');

				$output .= '<xmldata><version>12.0</version><header><name><![CDATA[' . $this->config->get('config_name') . ']]></name><www><![CDATA[' . $root . ']]></www><time>' . date("Y-m-d") . '</time></header>';

				$output .= $this->buildCategories();
				$output .= '<data>';

				$this->load->model('catalog/category');

				$this->load->model('catalog/product');

				$this->load->model('tool/image');

				$products = $this->model_catalog_product->getProducts();

				foreach ($products as $product) {
					$product = $this->clear($product);

                    if ((float)$product['special'])
                        $price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), 'PLN', false, false);
                    else
                        $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), 'PLN', false, false);

					$output .= '<item>';
					$output .= '<compid><![CDATA[' . $product['product_id'] . ']]></compid>';
					$output .= '<vendor><![CDATA[' . $product['manufacturer'] . ']]></vendor>';
					$output .= '<name><![CDATA[' . $product['name'] . ']]></name>';
					$output .= '<price>' . $price . '</price>';

					$categories = $this->model_catalog_product->getCategories($product['product_id']);
					$category = end($categories);

					$output .= '<catid><![CDATA[' . $category['category_id'] . ']]></catid>';

					if ($product['image'])
						$output .= '<foto><![CDATA[' . $this->model_tool_image->resize($product['image'], 500, 500) . ']]></foto>';

					$output .= '<desclong><![CDATA[' . $product['description'] . ']]></desclong>';
					$output .= '<url><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></url>';
					$output .= '</item>';
				}

				$output .= '</data></xmldata>';

				$this->load->model('integration_compare/compareprices');
				$this->model_integration_compare_compareprices->saveLastDate('skapiec');

				$this->response->addHeader('Content-Type: application/rss+xml');
				$this->response->setOutput($output);
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

	protected function buildCategories() {
		$this->load->model('integration_compare/compareprices');

		$categories = $this->model_integration_compare_compareprices->getCategories(0);
        $_tmp_category = array();

		foreach ($categories as $category) {
			$_tmp_category[] = array(
                    'value'=> $category['category_id'],
                    'text' => $category['name'],
			);
		}


		$all = '<category>';

		foreach ($_tmp_category as $category) {
			$category = $this->clear($category);

			$all .= '<catitem><catid>' . $category['value'] . '</catid><catname><![CDATA[' . $category['text'] . ']]></catname></catitem>';
		}

		$all .= '</category>';

		return $all;
	}
}
?>