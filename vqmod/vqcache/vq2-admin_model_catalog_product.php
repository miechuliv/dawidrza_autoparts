<?php
class ModelCatalogProduct extends Model {

    public function massSave($ids,$data)
    {


        $update = false;

            $sql = "UPDATE ".DB_PREFIX."product SET ";

            if($data['mass_price'])
            {
                if($update)
                {
                    $sql .= " ,price = '".(float)$data['mass_price']."'  ";
                }
                else
                {
                    $sql .= " price = '".(float)$data['mass_price']."'  ";
                }

                $update = true;
            }


        if($data['mass_price_percentage'])
        {



            $f = function($string)
            {
                $matches = array();

                preg_match_all('!\d+!', $string, $matches);



                $r = (int)(implode('',$matches[0]));

                return $r/100;
            };



            if($update)
            {
                if(strpos($data['mass_price_percentage'],'+')!==false)
                {

                    $sql .= " ,price = price + price*'".(float)$f($data['mass_price_percentage'])."'  ";
                }
                elseif(strpos($data['mass_price_percentage'],'-')!==false)
                {

                    $sql .= " ,price = price - price*'".(float)$f($data['mass_price_percentage'])."'  ";
                }

            }
            else
            {
                if(strpos($data['mass_price_percentage'],'+')!==false)
                {

                    $sql .= " price = price + price*'".(float)$f($data['mass_price_percentage'])."'  ";
                }
                elseif(strpos($data['mass_price_percentage'],'-')!==false)
                {

                    $sql .= " price = price - price*'".(float)$f($data['mass_price_percentage'])."'  ";
                }
            }

            $update = true;
        }

            if($data['mass_delivery_time'])
            {
                if($update)
                {
                    $sql .= " ,delivery_time = '".(int)$data['mass_delivery_time']."'  ";
                }
                else
                {
                    $sql .= " delivery_time = '".(int)$data['mass_delivery_time']."'  ";
                }

                $update = true;
            }

        if($data['mass_delivery_price'])
        {
            if($update)
            {
                $sql .= " ,delivery_price = '".(int)$data['mass_delivery_price']."'  ";
            }
            else
            {
                $sql .= " delivery_price = '".(int)$data['mass_delivery_price']."'  ";
            }

            $update = true;
        }

        if($data['mass_quantity'])
        {
            if($update)
            {
                $sql .= " ,quantity = '".(int)$data['mass_quantity']."'  ";
            }
            else
            {
                $sql .= " quantity = '".(int)$data['mass_quantity']."'  ";
            }

            $update = true;
        }

        if($data['mass_feed'])
        {
            if($update)
            {
                $sql .= " ,feed = '".(int)$data['mass_feed']."'  ";
            }
            else
            {
                $sql .= " feed = '".(int)$data['mass_feed']."'  ";
            }

            $update = true;
        }

        if($data['mass_category'])
        {

            foreach($ids as $id)
            {
                $this->db->query("INSERT IGNORE INTO product_to_category SET category_id = '".(int)$data['mass_category']."', product_id = '".(int)$id."' ");
            }

        }

        if($data['mass_category_remove'])
        {
            foreach($ids as $id)
            {
                $this->db->query("DELETE FROM product_to_category WHERE category_id = '".(int)$data['mass_category_remove']."' AND product_id = '".(int)$id."' ");
            }

        }

        if($data['mass_google_merchant']!==null)
        {

            if($update)
            {
                $sql .= " ,google_merchant = '".(int)$data['mass_google_merchant']."'  ";
            }
            else
            {
                $sql .= " google_merchant = '".(int)$data['mass_google_merchant']."'  ";
            }

            $update = true;

        }



        $ids = implode(', ',$ids);


        if($update)
        {
            $sql .= " WHERE product_id IN (".$ids.") ";


             $this->db->query($sql);
        }
    }

    public function getProductKeywords($id)
    {
            $result = $this->db->query("SELECT * FROM url_alias WHERE query = 'product_id=".$id."' ");

        $data = array();

            if($result->rows)
            {
                 foreach($result->rows as $row)
                 {
                           $data[$row['language']] = $row['keyword'];

                 }

                return $data;
            }

            return false;
    }


	public function addProduct($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "',
		 sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "',
		 ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "',
		 isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "',
		 location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "',
		  minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "',
		  stock_status_id = '" . (int)$data['stock_status_id'] . "',
		  date_available = '" . $this->db->escape($data['date_available']) . "',
		  manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
		  shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "',
		  points = '" . (int)(isset($data['points'])?$data['points']:0) . "', weight = '" . (float)$data['weight'] . "',
		  weight_class_id = '" . (int)$data['weight_class_id'] . "',
		  length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "',
		  height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "',
		  status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "',
		  sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(),
		  delivery_time = '".(int)$data['delivery_time']."',
		  delivery_price = '".(float)$data['delivery_price']."',
		  feed = '".(int)$data['feed']."',
		  google_merchant = '".(int)$data['google_merchant']."',
		  use_own_quantity_discount = '".(int)$data['use_own_quantity_discount']."',



          category_name = '".$this->db->escape($data['category_name'])."',
		  subcategory_name = '".$this->db->escape($data['subcategory_name'])."',
		  original_model = '".$this->db->escape($data['original_model'])."',
		  original_price = '".(float)$data['original_price']."',
		  buy_price = '".(float)$data['buy_price']."'



		  ");



		
		$product_id = $this->db->getLastId();

        if($data['google_merchant'])
        {
            $this->load->model('google/merchant');
            // google merchant
            $items = array();

            $items[] = array(
                'product_id' => $product_id,
                'operation'  => 'insert'
            );

            $this->model_google_merchant->sendBatch($items);
        }
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}
		
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
					
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
	
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
				
					$product_option_id = $this->db->getLastId();
				
					if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET
							product_option_id = '" . (int)$product_option_id . "',
							product_id = '" . (int)$product_id . "',
							 option_id = '" . (int)$product_option['option_id'] . "',
							  option_value_id = '" . (int)$product_option_value['option_value_id'] . "',
							  quantity = '" . (int)$product_option_value['quantity'] . "',
							  subtract = '" . (int)$product_option_value['subtract'] . "',
							  price = '" . (float)$product_option_value['price'] . "',
							   price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "',
							   points = '" . (int)$product_option_value['points'] . "',
							    points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "',
							    weight = '" . (float)$product_option_value['weight'] . "',
							    weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'
							    , image = '".$this->db->escape($product_option_value['image'])."'
							    , model = '".$this->db->escape($product_option_value['model'])."'
							    , original_model = '".$this->db->escape($product_option_value['original_model'])."' ");
						} 
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}

        if (isset($data['product_retailer'])) {
            foreach ($data['product_retailer'] as $retailer) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_retailer SET product_id = '" . (int)$product_id . "', retailer_id = '" . (int)$retailer . "' ");
            }
        }

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		if ($data['keyword']) {
            if(is_array($data['keyword']))
            {
                foreach($data['keyword'] as $key => $keyword){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "', `language`='".$key."' ");
                }

            }
            else
            {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', `language` = '".$this->config->get('config_language')."' ");
            }

		}

        // product quanity discount //
        $this->load->model('catalog/product_quantity_discount');
        if (isset($data['product_quantity_discount'])) {
            foreach ($data['product_quantity_discount'] as $discount) {
                $this->model_catalog_product_quantity_discount->addDiscount($product_id,$discount);
            }
        }



						
		$this->cache->delete('product');

        return $product_id;
	}
	
	public function editProduct($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "',
		sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "',
		 ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "',
		 isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "',
		  location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "',
		   minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "',
		   stock_status_id = '" . (int)$data['stock_status_id'] . "',
		   date_available = '" . $this->db->escape($data['date_available']) . "',
		   manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
		   shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "',
		   points = '" . (int)(isset($data['points'])?$data['points']:0) . "', weight = '" . (float)$data['weight'] . "',
		   weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "',
		    width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "',
		    length_class_id = '" . (int)$data['length_class_id'] . "',
		    status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "',
		    sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(),
		     delivery_time = '".(int)$data['delivery_time']."',
		  delivery_price = '".(float)$data['delivery_price']."',
		  feed = '".(int)$data['feed']."',
		  google_merchant = '".(int)$data['google_merchant']."',
		  use_own_quantity_discount = '".(int)$data['use_own_quantity_discount']."',

		    category_name = '".$this->db->escape($data['category_name'])."',
		  subcategory_name = '".$this->db->escape($data['subcategory_name'])."',
		  original_model = '".$this->db->escape($data['original_model'])."',
		  original_price = '".(float)$data['original_price']."',
		  buy_price = '".(float)$data['buy_price']."'


		    WHERE product_id = '" . (int)$product_id . "'");

        if($data['google_merchant'])
        {
            $this->load->model('google/merchant');
             // google merchant
            $items = array();

                $items[] = array(
                    'product_id' => $product_id,
                    'operation'  => 'insert'
                );

            $this->model_google_merchant->sendBatch($items);
        }


		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
					
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
				
					$product_option_id = $this->db->getLastId();
				
					if (isset($product_option['product_option_value'])  && count($product_option['product_option_value']) > 0 ) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'
							, image = '".$this->db->escape($product_option_value['image'])."'
							 , model = '".$this->db->escape($product_option_value['model'])."'
							 , original_model = '".$this->db->escape($product_option_value['original_model'])."'");
						}
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}					
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
 
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}		
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
			}
		}

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_retailer WHERE product_id = '" . (int)$product_id . "'");

        if (isset($data['product_retailer'])) {
            foreach ($data['product_retailer'] as $retailer) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_retailer SET product_id = '" . (int)$product_id . "', retailer_id = '" . (int)$retailer . "' ");
            }
        }

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

        // miechu no keyword delete
		 $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");


        if ($data['keyword']) {
            if(is_array($data['keyword']))
            {
                foreach($data['keyword'] as $key => $keyword){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "', `language`='".$key."' ");
                }

            }
            else
            {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', `language` = '".$this->config->get('config_language')."' ");
            }

        }

        // product quanity discount //
        $this->load->model('catalog/product_quantity_discount');
        $this->model_catalog_product_quantity_discount->deleteDiscounts($product_id);
        if (isset($data['product_quantity_discount'])) {
            foreach ($data['product_quantity_discount'] as $discount) {
                $this->model_catalog_product_quantity_discount->addDiscount($product_id,$discount);
            }
        }
						
		$this->cache->delete('product');

        return $product_id;
	}


	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			
			$data = $query->row;
			
			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = $this->getProductKeywords($product_id);
			$data['status'] = '0';
						
			$data = array_merge($data, array('product_attribute' => $this->getProductAttributes($product_id)));
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));			
			$data = array_merge($data, array('product_discount' => $this->getProductDiscounts($product_id)));
			$data = array_merge($data, array('product_filter' => $this->getProductFilters($product_id)));
			$data = array_merge($data, array('product_image' => $this->getProductImages($product_id)));		
			$data = array_merge($data, array('product_option' => $this->getProductOptions($product_id)));
			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_reward' => $this->getProductRewards($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			$data = array_merge($data, array('product_download' => $this->getProductDownloads($product_id)));
			$data = array_merge($data, array('product_layout' => $this->getProductLayouts($product_id)));
			$data = array_merge($data, array('product_store' => $this->getProductStores($product_id)));

			
			$id =  $this->addProduct($data);



            return $id;
		}
	}


	
	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		$this->cache->delete('product');
	}
	
	public function getProduct($product_id,$lang = false) {

        if(!$lang)
        {
            $lang = (int)$this->config->get('config_language_id');
        }

		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . " LIMIT 1') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . $lang . "'");
				
		return $query->row;
	}
	
	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

        if (!empty($data['filter_category_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
        }

        // join product attributes
        if(isset($data['filter_attribute']))
        {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id = pa.product_id)";
        }


        // miechu options join
        if (isset($data['filter_retailer']) AND $data['filter_retailer']) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_retailer p2r ON (p.product_id = p2r.product_id)";
        }

        if (isset($data['filter_option'])) {
            $sql .= "LEFT JOIN `" . DB_PREFIX . "product_option_value` pov ON (p.product_id=pov.product_id) LEFT JOIN `" . DB_PREFIX . "product_option` po ON (pov.product_option_id=po.product_option_id) LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.option_value_id=ov.option_value_id) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ov.option_value_id=ovd.option_value_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (ovd.option_id=od.option_id) ";
        }


        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        // filtr po kategorii
        if (!empty($data['filter_category_id'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
        }

        // miechu options
        if (isset($data['filter_option']) AND isset($data['filter_option_value'])) {

               $sql.=" AND po.option_id='" . (int)$data['filter_option'] . "' AND pov.option_value_id='" . (int)$data['filter_option_value'] . "' AND pov.quantity>=0 ";

        }

        if (isset($data['filter_retailer']) AND $data['filter_retailer']) {
            $sql .= " AND p2r.product_id = '" . (int)$data['filter_retailer'] . "'";
        }

        if(isset($data['filter_attribute']) AND isset($data['filter_attribute_value']))
        {
            $sql .= " AND pa.text = '" . $data['filter_attribute_value'] . "'";

        }



		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		
		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

        if (isset($data['type']) && !is_null($data['type'])) {
            $sql .= " AND p.type = '" . $data['type'] . "'";
        }
		
		$sql .= " GROUP BY p.product_id";


					
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	
	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");
								  
		return $query->rows;
	} 
	
	public function getProductDescriptions($product_id) {
		$product_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'tag'              => $result['tag']
			);
		}
		
		return $product_description_data;
	}
		
	public function getProductCategories($product_id) {
		$product_category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	
	public function getProductFilters($product_id) {
		$product_filter_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}
				
		return $product_filter_data;
	}
	
	public function getProductAttributes($product_id) {
		$product_attribute_data = array();
		
		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");
		
		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();
			
			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
			
			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}
			
			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}
		
		return $product_attribute_data;
	}
	
	public function getProductOptions($product_id) {
		$product_option_data = array();


		
		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();	
				
			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");



			foreach ($product_option_value_query->rows as $product_option_value) {



				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],						
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix'],
                    'image'                     => $product_option_value['image'],
                    'model'           => $product_option_value['model'],
                    'original_model'                     => $product_option_value['original_model'],
				);
			}
				
			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],			
				'product_option_value' => $product_option_value_data,
				'option_value'         => $product_option['option_value'],
				'required'             => $product_option['required']				
			);
		}
		
		return $product_option_data;
	}
			
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
	
	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");
		
		return $query->rows;
	}
	
	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
		
		return $query->rows;
	}
	
	public function getProductRewards($product_id) {
		$product_reward_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}
		
		return $product_reward_data;
	}
		
	public function getProductDownloads($product_id) {
		$product_download_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}
		
		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}
		
		return $product_store_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $product_layout_data;
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}
		
		return $product_related_data;
	}

    public function getProductRetailers($product_id) {
        $product_retailer_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_retailer WHERE product_id = '" . (int)$product_id . "'");

        foreach ($query->rows as $result) {
            $product_retailer_data[] = $result['retailer_id'];
        }

        return $product_retailer_data;
    }
	
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}

        // join product attributes
        if(isset($data['filter_attribute']))
        {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id = pa.product_id)";
        }


        // miechu options join
        if (isset($data['filter_retailer']) AND $data['filter_retailer']) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_retailer p2r ON (p.product_id = p2r.product_id)";
        }

        if (isset($data['filter_option'])) {
            $sql .= "LEFT JOIN `" . DB_PREFIX . "product_option_value` pov ON (p.product_id=pov.product_id) LEFT JOIN `" . DB_PREFIX . "product_option` po ON (pov.product_option_id=po.product_option_id) LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.option_value_id=ov.option_value_id) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ov.option_value_id=ovd.option_value_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (ovd.option_id=od.option_id) ";
        }


        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        // filtr po kategorii
        if (!empty($data['filter_category_id'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
        }

        // miechu options
        if (isset($data['filter_option']) AND isset($data['filter_option_value'])) {

            $sql.=" AND po.option_id='" . (int)$data['filter_option'] . "' AND pov.option_value_id='" . (int)$data['filter_option_value'] . "' AND pov.quantity>=0 ";

        }

        if (isset($data['filter_retailer']) AND $data['filter_retailer']) {
            $sql .= " AND p2r.product_id = '" . (int)$data['filter_retailer'] . "'";
        }

        if(isset($data['filter_attribute']) AND isset($data['filter_attribute_value']))
        {
            $sql .= " AND pa.text = '" . $data['filter_attribute_value'] . "'";

        }



		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		
		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}



		
		$query = $this->db->query($sql);

            return $query->row['total'];

	}

    // retailer
    public function getProductsRetailer($product_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_retailer WHERE product_id = '" . (int)$product_id . "'");

        return $query->rows;
    }
	
	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}
		
	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}	
	
public function getAllProducts($data = array()) {
		$sql = "SELECT p.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
			$sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}	
	
	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}


    private function checkArrayEmpty($data){

        foreach($data as $value)
        {
            if($value!='null')
            {
                return true;

            }
        }

        return false;
    }

    private function checkMultiArrayNotEmpty($data)
    {
        foreach($data as $key => $value)
        {
            if(is_array($value) AND !empty($value))
            {
                return true;
            }
        }

        return false;
    }
}
?>
