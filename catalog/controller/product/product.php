<?php
class ControllerProductProduct extends Controller {
	private $error = array(); 
	
	public function index() { 
		$this->language->load('product/product');
	
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),			
			'separator' => false
		);

		
		$this->load->model('catalog/category');	
		
		if (isset($this->request->get['path'])) {
			$path = '';
			
			$parts = explode('_', (string)$this->request->get['path']);
			
			$category_id = (int)array_pop($parts);
				
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
				
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if ($category_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
			
			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);
				
			if ($category_info) {			
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	
	
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}	
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
										
				$this->data['breadcrumbs'][] = array(
					'text'      => $category_info['name'],
					'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path']),
					'separator' => $this->language->get('text_separator')
				);
			}
		}
		
		$this->load->model('catalog/manufacturer');	
		
		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array( 
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);	
	
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
							
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {	
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),					
					'separator' => $this->language->get('text_separator')
				);
			}
		}
		
		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';
			
			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}
						
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}
						
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}
			
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}	

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
												
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('product/search', $url),
				'separator' => $this->language->get('text_separator')
			); 	
		}
		
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			$url = '';


            // kategoria
            $categories = $this->model_catalog_product->getCategories($product_info['product_id']);


            $category = array_shift($categories);

            $this->data['category_name'] = false;

            if(isset($category['category_id']))
            {

                $cat = $this->model_catalog_category->getCategory($category['category_id']);
                $this->data['category_name'] = $cat['name'];
            }
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
						
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
						
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}
						
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}	
						
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}
			
			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}	
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
																		
			$this->data['breadcrumbs'][] = array(
				'text'      => $product_info['name'],
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
				'separator' => $this->language->get('text_separator')
			);			
			
			$this->document->setTitle($product_info['name']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
			
			$this->data['heading_title'] = $product_info['name'];
			
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');	
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_weight'] = $this->language->get('text_weight');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');

            


			
			$this->data['entry_name'] = $this->language->get('entry_name');
            $this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');			
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$this->data['tab_related'] = $this->language->get('tab_related');
			
			$this->data['product_id'] = $this->request->get['product_id'];

            $this->updateGlobalData('product_id',$this->request->get['product_id'],false);

			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['model'] = $product_info['model'];
			$this->data['weight'] = $product_info['weight'];
			$this->data['length'] = $product_info['length'];
			$this->data['width'] = $product_info['width'];
			$this->data['height'] = $product_info['height'];
			$this->data['reward'] = $product_info['reward'];
			$this->data['points'] = $product_info['points'];
			
			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}

            $this->data['product_quantity'] = $product_info['quantity'];

            $delivery_time = new \DateTime($product_info['delivery_time']);
            $current_date = new \DateTime();

            if($delivery_time >= $current_date)
            {
                $this->data['delivery_time'] = $delivery_time->format('Y-m-d');
            }
            else
            {
                $this->data['delivery_time'] = false;
            }


			
			$this->load->model('tool/image');




			
			$this->data['images'] = array();
			
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);




            $p_image = false;

            if($product_info['image'] AND file_exists(DIR_IMAGE.$product_info['image']) AND is_file(DIR_IMAGE.$product_info['image']))
            {
                $p_image = $product_info['image'];

            }
            else
            {
                if(isset($results[0]['image']))
                {
                    $p_image = $results[0]['image'];
                }

            }





            if ($p_image AND file_exists(DIR_IMAGE.$p_image) AND is_file(DIR_IMAGE.$p_image)) {
                $this->data['popup'] = $this->model_tool_image->resize($p_image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                $this->data['popup'] = str_replace(HTTP_IMAGE,'',$this->data['popup']);
                $this->data['popup'] = $this->model_tool_image->image_watermark($this->data['popup']);

            } else {
                $this->data['popup'] = '';
            }


            if ($p_image AND file_exists(DIR_IMAGE.$p_image) AND is_file(DIR_IMAGE.$p_image)) {

                $this->data['thumb'] = $this->model_tool_image->resize($p_image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            } else {
                $this->data['thumb'] = '';
            }
			
			foreach ($results as $result) {


                if(!file_exists(DIR_IMAGE.$result['image']) OR !is_file(DIR_IMAGE.$result['image']))
                {
                    continue;
                }
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),

                    'middle' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))

				);
			}

            foreach($this->data['images'] as $key => $image){

                if(isset($image['popup']))
                {
                    $image['popup'] = str_replace(HTTP_IMAGE,'',$image['popup']);
                    $this->data['images'][$key]['popup'] = $this->model_tool_image->image_watermark($image['popup']);
                }
            }


            // sciagamy najniższa możliwa cene wg. promocji ilosciowej
            $this->load->model('catalog/product_quantity_discount');



            $percent = $this->model_catalog_product_quantity_discount->getAviableDiscountByProductId($product_info['product_id'],100000,$product_info['use_own_quantity_discount']);

            $price = $product_info['price'];

            if($percent)
            {


                $price = $product_info['price']*(1 + $percent/100);


            }

            $product_info['price'];

						
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($price, $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}


						
			if ((float)$product_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($price, $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}
			
			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $price);
			} else {
				$this->data['tax'] = false;
			}




			
			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
			
			$this->data['discounts'] = array(); 
			
			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}
			
			$this->data['options'] = array();

            $sugerowana_technika_zdobienia = false;

            $stz =  $this->db->query("SELECT * FROM `".DB_PREFIX."product_attribute` WHERE product_id = '".(int)$this->request->get['product_id']."' AND attribute_id = 3 ");

            if($stz->num_rows)
            {
                $sugerowana_technika_zdobienia = $stz->row['text'];
            }

            if($sugerowana_technika_zdobienia == 'laser')
            {
                $this->data['show_color_count_option'] = false;
            }
            else
            {
                $this->data['show_color_count_option'] = true;
            }

            $option_images = array();
			
			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
					$option_value_data = array();



					
					foreach ($option['option_value'] as $option_value) {

						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}

                            if($option_value['image'])
                            {

                                $quickFixArray = array('a','b','c','d','e','a1','a2','b1','b2','c1','c2','d1','d2');

                                if(file_exists(DIR_IMAGE.$option_value['image']))
                                {
                                    $option_images[$option_value['product_option_value_id']] = array(
                                        'alt' => $option_value['name'],
                                        'title' => $option_value['name'],
                                        'popup' => $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                                        'thumb' => $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),

                                        'middle' => $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))

                                    );
                                }
                                else
                                {
                                    foreach($quickFixArray as $fix)
                                    {
                                        $t = explode('.',$option_value['image']);
                                        $ext = array_pop($t);
                                        $fixed_name = implode('.',$t);
                                        $fixed_name .= '_'.$fix.'.'.$ext;
                                        if(file_exists(DIR_IMAGE.$fixed_name))
                                        {
                                            $option_images[$option_value['product_option_value_id']] = array(
                                                'alt' => $option_value['name'],
                                                'title' => $option_value['name'],
                                                'popup' => $this->model_tool_image->resize($fixed_name, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                                                'thumb' => $this->model_tool_image->resize($fixed_name, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),

                                                'middle' => $this->model_tool_image->resize($fixed_name, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))

                                            );

                                            break;
                                        }
                                    }
                                }



                            }
							
							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix'],
                                'quantity' => $option_value['quantity'],
                                'model' => $option_value['model'],
							);
						}
					}

                    /* przy druku podana jest sugerowana technika zdobienia */
                    if($option['option_id']==3 AND $sugerowana_technika_zdobienia)
                    {
                        $name = $option['name'].' <small>('.$sugerowana_technika_zdobienia.')</small>';
                    }
                    elseif($option['option_id']==2 AND $sugerowana_technika_zdobienia)
                    {
                        $name = $option['name'].' <small>('.$sugerowana_technika_zdobienia.')</small>';
                    }
                    else
                    {
                        $name = $option['name'];
                    }
					
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $name,
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);					
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {

                    // tych dwóch opcji nie pokazujemy, będą na innej podstronie
                    if($option['option_id'] == 5 OR $option['option_id'] == 6)
                    {
                        continue;
                    }

                    $this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);						
				}
			}

            if(empty($option_images))
            {
                $this->data['images'][] = array(
                    'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                    'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),

                    'middle' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))

                );
            }

            $this->data['option_images'] = $option_images;

            $this->load->model('catalog/product_quantity_discount');

            $this->data['product_quantity_discount'] = $this->model_catalog_product_quantity_discount->getDiscountByProductId($this->request->get['product_id']);


            if(empty($this->data['product_quantity_discount']))
            {
                $this->data['product_quantity_discount'] = $this->config->get('quantity_discount_values');
            }


            // sprawdzamy czy jest w jakieś top-liście
            $this->load->model('catalog/top_list');
            $this->load->model('catalog/top_list_product');
            $this->load->model('catalog/top_list_description');

            $top_lists = $this->model_catalog_top_list_product->getTopListByProduct($product_id);

            $this->data['top_lists'] = array();

            foreach($top_lists as $top_list)
            {
                $description = $this->model_catalog_top_list_description->getTopListDescription($top_list['top_list_id'],$this->config->get('config_language_id'));
                $this->data['top_lists'][] = sprintf($this->language->get('text_in_top_list'),$top_list['product_sort_order'],$description['name']);


            }

            // cennik druku
            $this->load->model('catalog/grupy_druku');
            $this->data['l_kolorow'] = 0;

            if(!empty($this->data['product_quantity_discount']))
            {
                foreach($this->data['product_quantity_discount'] as $key => $discount)
                {
                    //$this->data['product_quantity_discount'][$key]['percent'] = $this->currency->format(($product_info['price']*(1 - $discount['percent']/100))) .' / '. $this->currency->format($this->tax->calculate(($product_info['price']*(1 - $discount['percent']/100)), $product_info['tax_class_id'], $this->config->get('config_tax')));
                    $this->data['product_quantity_discount'][$key]['percent'] = $this->currency->format(($product_info['price']*(1 + $discount['percent']/100))) .' / '. $this->currency->format($this->tax->calculate(($product_info['price']*(1 + $discount['percent']/100)), $product_info['tax_class_id'], $this->config->get('config_tax')));
                    // koszt z drukiem dla danego przedziełu , co z kolorami?
                    $l_kolorow = $this->db->query("SELECT * FROM `".DB_PREFIX."product_attribute` WHERE product_id = '".(int)$this->request->get['product_id']."' AND attribute_id = 4 ");


                    if($l_kolorow->num_rows)
                    {
                        $l_kolorow = (int)$l_kolorow->row['text'];
                    }
                    else
                    {
                        $l_kolorow = 0;
                    }

                    $this->data['l_kolorow'] = $l_kolorow;
                    // koszty druku dla róznej ilości kolorów
                    $this->data['product_quantity_discount'][$key]['druk'] = array();

                    // do ceny po znizce za ilośc dodaje się jeszcze koszt druku który też jest uzależiony od ilości i ilości kolorów
                    for($i = 1; $i <= $l_kolorow;$i++)
                    {
                        $druk = 0;
                        $druk = $this->model_catalog_grupy_druku->getProductKosztDruku($this->request->get['product_id'],$discount['from'],$i,1);

                        if(!$druk)
                        {
                            // koszt druku ryczałtowy
                            $druk = $this->model_catalog_grupy_druku->getProductKosztDruku($this->request->get['product_id'],$discount['from'],$i,0);

                            if($druk)
                            {
                                $this->data['product_quantity_discount'][$key]['druk'][$i] = $this->currency->format(($product_info['price']*(1 + $discount['percent']/100))) .' + '. $this->currency->format($druk) .' / '. $this->currency->format($this->tax->calculate(($product_info['price']*(1 + $discount['percent']/100)), $product_info['tax_class_id'], $this->config->get('config_tax'))) .' + '. $this->currency->format($druk);
                            }
                            else
                            {
                                $this->data['product_quantity_discount'][$key]['druk'][$i] = $this->currency->format(($product_info['price']*(1 + $discount['percent']/100) + $druk)) .' / '. $this->currency->format($this->tax->calculate(($product_info['price']*(1 + $discount['percent']/100) + $druk), $product_info['tax_class_id'], $this->config->get('config_tax')));
                            }

                        }
                        else
                        {
                            // koszt druku od sztuki
                            // zmiana zniżki na marże
                            //$this->data['product_quantity_discount'][$key]['druk'][$i] = $this->currency->format(($product_info['price']*(1 - $discount['percent']/100) + $druk)) .' / '. $this->currency->format($this->tax->calculate(($product_info['price']*(1 - $discount['percent']/100) + $druk), $product_info['tax_class_id'], $this->config->get('config_tax')));
                            $this->data['product_quantity_discount'][$key]['druk'][$i] = $this->currency->format(($product_info['price']*(1 + $discount['percent']/100) + $druk)) .' / '. $this->currency->format($this->tax->calculate(($product_info['price']*(1 + $discount['percent']/100) + $druk), $product_info['tax_class_id'], $this->config->get('config_tax')));

                        }

                    }

                }
            }







							
			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}
			
			$this->data['review_status'] = $this->config->get('config_review_status');
			$this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];



			$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);


			$this->data['products'] = array();
			
			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
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
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}	
			
			$this->data['tags'] = array();
			
			if ($product_info['tag']) {		
				$tags = explode(',', $product_info['tag']);
				
				foreach ($tags as $tag) {
					$this->data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}
			
			$this->model_catalog_product->updateViewed($this->request->get['product_id']);



            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/product.tpl';
			} else {
				$this->template = 'default/template/product/product.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header',
                'module/alsobought',
			);
						
			$this->response->setOutput($this->render());
		} else {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
						
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}	
						
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}			

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}	
					
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}
							
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}
					
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}
			
			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}	
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
														
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $product_id),
        		'separator' => $this->language->get('text_separator')
      		);			
		
      		$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');



			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header',
                'module/alsobought',
			);
						
			$this->response->setOutput($this->render());
    	}
  	}
	
	public function review() {
    	$this->language->load('product/product');
		
		$this->load->model('catalog/review');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['reviews'] = array();
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
			
		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
      		
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
        		'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
			
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/review.tpl';
		} else {
			$this->template = 'default/template/product/review.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function write() {
		$this->language->load('product/product');
		
		$this->load->model('catalog/review');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}
			
			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}
	
			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}
	
			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			//	$json['error'] = $this->language->get('error_captcha');
			}
				
			if (!isset($json['error'])) {
				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
/*	
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	*/
	public function upload() {
		$this->language->load('product/product');
		
		$json = array();
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
			
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}	  	

			// Allowed file extension types
			$allowed = array();
			
			$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}	
			
			// Allowed file mime types		
		    $allowed = array();
			
			$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
							
			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}
						
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		
		if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			$file = basename($filename) . '.' . md5(mt_rand());
			
			// Hide the uploaded file name so people can not link to it directly.
			$json['file'] = $this->encryption->encrypt($file);
			
			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
						
			$json['success'] = $this->language->get('text_upload');
		}	
		
		$this->response->setOutput(json_encode($json));		
	}

    public function getQuantityDiscount()
    {

        $this->load->model('catalog/product_quantity_discount');
        $this->load->model('catalog/product');
        $this->load->model('catalog/grupy_druku');

        $product = $this->model_catalog_product->getProduct($this->request->post['product_id']);

        $percent = $this->model_catalog_product_quantity_discount->getAviableDiscountByProductId($this->request->post['product_id'],$this->request->post['quantity'],$product['use_own_quantity_discount']);



        if($percent)
        {


            $price = $product['price']*(1 + $percent/100);


        }
        else
        {
            $price = $product['price'];


        }



        // dodatkowo doliczamy opłate za dnadruk i liczbę kolorów
        $opt_val = $this->db->query("SELECT * FROM `".DB_PREFIX."product_option_value` WHERE product_option_value_id = '".(int)trim($this->request->post['druk'])."'
         AND product_id = '".(int)$this->request->post['product_id']."' ");

        //var_dump($opt_val);

        $opt_val_id = false;
        if($opt_val->num_rows)
        {
                $opt_val_id = $opt_val->row['option_value_id'];
        }

        if($opt_val_id == 1 AND isset($this->request->post['colors']))
        {

            $druk = $this->model_catalog_grupy_druku->getProductKosztDruku($this->request->post['product_id'],$this->request->post['quantity'],$this->request->post['colors']);

            $price_tax = $this->currency->format($this->tax->calculate(($price+$druk), $product['tax_class_id'], $this->config->get('config_tax')));
            $price = $this->currency->format(($price+$druk));
        }
        else
        {
            $price_tax = $this->currency->format($this->tax->calculate(($price), $product['tax_class_id'], $this->config->get('config_tax')));
            $price = $this->currency->format(($price));
        }



        $this->response->setOutput($price.':'.$price_tax);
    }
}
?>