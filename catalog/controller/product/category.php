<?php 
class ControllerProductCategory extends Controller {  
	public function index() {

		$this->language->load('product/category');


		
		$this->load->model('catalog/category');


		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}
				
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}

        $last_ajax_page = false;


        $deviceType = ($this->mobile_detect->isMobile() ? ($this->mobile_detect->isTablet() ? 'tablet' : 'phone') : 'computer');

        if($deviceType == 'computer')
        {
            if($page %5 == 0)
            {
                $last_ajax_page = true;
            }
        }
        else
        {
            if($page %3 == 0)
            {
                $last_ajax_page = true;
            }
        }

							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}

        // additional search
        // get search data
        $filtering = false;





        $this->data['all_cats'] = false;

        if(isset($this->request->post['clear']) AND $this->request->post['clear'])
        {

            unset($this->request->post['all-cats']);
            unset($this->request->post['manufacturer_id']);
        }

        if(isset($this->request->post['all-cats']) AND $this->request->post['all-cats'])
        {
            unset($this->request->get['path']);

            $this->data['all_cats'] = true;

        }





        $this->data['contact_link_text'] = $this->language->get('text_contact');
        $this->data['contact_link'] = $this->url->link('information/contact');








        // dodatkowe filtery
        // filtrowanie przez get
        if(isset($this->request->get['filter_attribute']))
        {
           $filter_attribute = $this->request->get['filter_attribute'];
        }
        else
        {
            $filter_attribute = false;
        }

        if(isset($this->request->get['filter_option']))
        {
            $filter_option = $this->request->get['filter_option'];
        }
        else
        {
            $filter_option = false;
        }

        if(isset($this->request->get['filter_price_min']))
        {
            $filter_price_min = $this->request->get['filter_price_min'];
        }
        else
        {
            $filter_price_min = false;
        }

        if(isset($this->request->get['filter_price_max']))
        {
            $filter_price_max = $this->request->get['filter_price_max'];
        }
        else
        {
            $filter_price_max = false;
        }

        $all = false;







		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);	
			
		if (isset($this->request->get['path']) AND $this->request->get['path']) {
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
									
			$path = '';
		
			$parts = explode('_', (string)$this->request->get['path']);
		
			$category_id = (int)array_pop($parts);
		
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}
									
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if ($category_info) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path . $url),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}
		} else {
			$category_id = 0;
            $all = true;
		}

        $this->data['category_search'] = $category_id;


				
		$category_info = $this->model_catalog_category->getCategory($category_id,$all);


        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        $this->data['search'] = $search;
	
		if ($category_info) {

            if($all)
            {
                $this->document->setTitle('Alle Kategorien');
                $this->document->setDescription('Alle Kategorien');
                $this->document->setKeywords('Alle Kategorien');
                $this->data['heading_title'] = 'Alle Kategorien';
            }
            else
            {
                $this->document->setTitle($category_info['name']);
                $this->document->setDescription($category_info['meta_description']);
                $this->document->setKeywords($category_info['meta_keyword']);
                $this->data['heading_title'] = $category_info['name'];
            }

			$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
			

			
			$this->data['text_refine'] = $this->language->get('text_refine');
			$this->data['text_empty'] = $this->language->get('text_empty');			
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['text_limit'] = $this->language->get('text_limit');
					
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			// Set the last category breadcrumb		
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

            if($all)
            {
                $this->data['breadcrumbs'][] = array(
                    'text'      => 'Alle Kategorien',
                    'href'      => $this->url->link('product/category'),
                    'separator' => $this->language->get('text_separator')
                );
            }
            else
            {
                $this->data['breadcrumbs'][] = array(
                    'text'      => $category_info['name'],
                    'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path']),
                    'separator' => $this->language->get('text_separator')
                );
            }

								
			if (!$all AND $category_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = '';
			}

            if($all)
            {
                $this->data['description'] = 'Alle Kategorien';
            }
            else
            {
                $this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
            }

			$this->data['compare'] = $this->url->link('product/compare');
			
			$url = '';
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}	
						
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            // dodatkowe
            if (isset($this->request->get['filter_price_min'])) {
                $url .= '&filter_price_min=' . $this->request->get['filter_price_min'];
            }
            if (isset($this->request->get['filter_price_max'])) {
                $url .= '&filter_price_max=' . $this->request->get['filter_price_max'];
            }
            if (isset($this->request->get['filter_attribute'])) {
                foreach($this->request->get['filter_attribute'] as $id => $val)
                {
                    $url .= '&filter_attribute['.$id.']=' . $val;
                }

            }
            if (isset($this->request->get['filter_option'])) {
                foreach($this->request->get['filter_option'] as $id => $val)
                {
                    $url .= '&filter_option['.$id.']=' . $val;
                }
            }



            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }


            $this->data['categories'] = array();

            if($all)
            {


                // wybiera Alle Kategorien
                $results = $this->model_catalog_category->getCategories($category_id,$all);

                $this->data['products'] = array();

                foreach($results as $result)
                {
                    $this->data['categories'][] = array(
                        'name'  => $result['name'],
                        'href'  => $this->url->link('product/category', 'path=' . $result['category_id'] . $url)
                    );
                }


                $product_results = array();


                $data = array(

                    'filter_name'         => $search,
                    'filter_description'         => $search,
                    'filter_model'         => trim($search),

                    'filter_filter'      => $filter,
                    'sort'               => $sort,
                    'order'              => $order,
                    'start'              => ($page - 1) * ($limit),
                    'limit'              => $limit,
                    'filter_attribute' => $filter_attribute,
                    'filter_option' => $filter_option,
                    'filter_price_min' => $this->currency->convert($filter_price_min,$this->currency->getCode(),$this->config->get('config_currency')),
                    'filter_price_max' => $this->currency->convert($filter_price_max,$this->currency->getCode(),$this->config->get('config_currency')),

                    'quantity' => 1,
                );

                unset($results);

                if($filtering){
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }
                else
                {
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }

                    if(isset($filtering))
                    {
                        $product_results = array_merge($product_results,$this->model_catalog_product->getProducts($data));
                    }
                    else
                    {
                        $product_results = array_merge($product_results,$this->model_catalog_product->getProducts($data));
                    }





            }
			else
            {
                $results = $this->model_catalog_category->getCategories($category_id);

                foreach ($results as $result) {
                    $data = array(
                        'filter_category_id'  => $result['category_id'],
                        'filter_sub_category' => true
                    );

                    $product_total = $this->model_catalog_product->getTotalProducts($data);

                    $this->data['categories'][] = array(
                        'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                        'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
                    );
                }

                $this->data['products'] = array();

                $data = array(
                    'filter_name'         => $search,
                    'filter_model'         => trim($search),
                    'filter_category_id' => $category_id,
                    'filter_filter'      => $filter,
                    'sort'               => $sort,
                    'order'              => $order,
                    'start'              => ($page - 1) * $limit,
                    'limit'              => $limit,
                    'filter_attribute' => $filter_attribute,
                    'filter_option' => $filter_option,
                    'filter_price_min' => $this->currency->convert($filter_price_min,$this->currency->getCode(),$this->config->get('config_currency')),
                    'filter_price_max' => $this->currency->convert($filter_price_max,$this->currency->getCode(),$this->config->get('config_currency')),
                    'type' => 'regenerated',
                    'quantity' => 1,
                );

                unset($results);


                if($filtering){
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }
                else
                {
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }

                if($filtering)
                {
                    $results = $this->model_catalog_product->getProducts($data);
                }
                else
                {
                    $results = $this->model_catalog_product->getProducts($data);
                }

            }



			
            if(isset($product_results))
            {
                $results = $product_results;
            }

			
			foreach ($results as $result) {
                if(!$result)
                {
                    continue;
                }
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));


                  //  $image = $this->model_tool_image->image_watermark(str_replace(HTTP_IMAGE,'',$image));
				} else {
					$image = false;
				}

                if(!$image)
                {
                    $r = $this->model_catalog_product->getProductImages($result['product_id']);

                    if(!empty($r))
                    {
                        $image = $this->model_tool_image->resize($r[0]['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    }
                }

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
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}


				
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}	
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

                // CodeHouse: get additional image
                $results = $this->model_catalog_product->getProductImages($result['product_id']);




                if ( isset($results[0]['image']) ) {
                    $additional_image = $this->model_tool_image->resize($results[0]['image'] , $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')) ;
                } else { $additional_image = false ; }
                // CodeHouse: END

                if($all)
                {
                   $href = $this->url->link('product/product',  '&product_id=' . $result['product_id'] . $url);
                }
                else
                {
                   $href =  $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url);
                }

                
								
				$this->data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
                    // CodeHouse: get additional image
                    'additional_image' => $additional_image,
                    // CodeHouse: END
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'price'       => $price,
                    'price_netto'       => $this->currency->format($result['price']),
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $href,
				);



			}

			
			$url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
				
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            // dodatkowe
            if (isset($this->request->get['filter_price_min'])) {
                $url .= '&filter_price_min=' . $this->request->get['filter_price_min'];
            }
            if (isset($this->request->get['filter_price_max'])) {
                $url .= '&filter_price_max=' . $this->request->get['filter_price_max'];
            }
            if (isset($this->request->get['filter_attribute'])) {
                foreach($this->request->get['filter_attribute'] as $id => $val)
                {
                    $url .= '&filter_attribute['.$id.']=' . $val;
                }

            }
            if (isset($this->request->get['filter_option'])) {
                foreach($this->request->get['filter_option'] as $id => $val)
                {
                    $url .= '&filter_option['.$id.']=' . $val;
                }
            }
										
			$this->data['sorts'] = array();

            if($all)
            {
                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_default'),
                    'value' => 'p.sort_order-ASC',
                    'href'  => $this->url->link('product/category',  'sort=p.sort_order&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_asc'),
                    'value' => 'pd.name-ASC',
                    'href'  => $this->url->link('product/category',  'sort=pd.name&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_desc'),
                    'value' => 'pd.name-DESC',
                    'href'  => $this->url->link('product/category', 'sort=pd.name&order=DESC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_asc'),
                    'value' => 'p.price-ASC',
                    'href'  => $this->url->link('product/category',  'sort=p.price&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_desc'),
                    'value' => 'p.price-DESC',
                    'href'  => $this->url->link('product/category', 'sort=p.price&order=DESC' . $url)
                );

                if ($this->config->get('config_review_status')) {
                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_desc'),
                        'value' => 'rating-DESC',
                        'href'  => $this->url->link('product/category',  'sort=rating&order=DESC' . $url)
                    );

                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_asc'),
                        'value' => 'rating-ASC',
                        'href'  => $this->url->link('product/category', 'sort=rating&order=ASC' . $url)
                    );
                }

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_asc'),
                    'value' => 'p.model-ASC',
                    'href'  => $this->url->link('product/category', 'sort=p.model&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_desc'),
                    'value' => 'p.model-DESC',
                    'href'  => $this->url->link('product/category', 'sort=p.model&order=DESC' . $url)
                );
            }
            else
            {
                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_default'),
                    'value' => 'p.sort_order-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_asc'),
                    'value' => 'pd.name-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_desc'),
                    'value' => 'pd.name-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_asc'),
                    'value' => 'p.price-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_desc'),
                    'value' => 'p.price-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
                );

                if ($this->config->get('config_review_status')) {
                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_desc'),
                        'value' => 'rating-DESC',
                        'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
                    );

                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_asc'),
                        'value' => 'rating-ASC',
                        'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
                    );
                }

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_asc'),
                    'value' => 'p.model-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_desc'),
                    'value' => 'p.model-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
                );
            }
			

			
			$url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
				
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
            // dodatkowe
            if (isset($this->request->get['filter_price_min'])) {
                $url .= '&filter_price_min=' . $this->request->get['filter_price_min'];
            }
            if (isset($this->request->get['filter_price_max'])) {
                $url .= '&filter_price_max=' . $this->request->get['filter_price_max'];
            }
            if (isset($this->request->get['filter_attribute'])) {
                foreach($this->request->get['filter_attribute'] as $id => $val)
                {
                    $url .= '&filter_attribute['.$id.']=' . $val;
                }

            }
            if (isset($this->request->get['filter_option'])) {
                foreach($this->request->get['filter_option'] as $id => $val)
                {
                    $url .= '&filter_option['.$id.']=' . $val;
                }
            }
			
			$this->data['limits'] = array();
	
			$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));
			
			sort($limits);

            if($all)
            {
              $href =  $this->url->link('product/category',  $url . '&limit=' . $limits);
            }
            else
            {
                $href =   $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $limits);
            }
	
			foreach($limits as $limits){
				$this->data['limits'][] = array(
					'text'  => $limits,
					'value' => $limits,
					'href'  => $href
				);
			}
			
			$url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
				
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            // dodatkowe
            if (isset($this->request->get['filter_price_min'])) {
                $url .= '&filter_price_min=' . $this->request->get['filter_price_min'];
            }
            if (isset($this->request->get['filter_price_max'])) {
                $url .= '&filter_price_max=' . $this->request->get['filter_price_max'];
            }
            if (isset($this->request->get['filter_attribute'])) {
                foreach($this->request->get['filter_attribute'] as $id => $val)
                {
                    $url .= '&filter_attribute['.$id.']=' . $val;
                }

            }
            if (isset($this->request->get['filter_option'])) {
                foreach($this->request->get['filter_option'] as $id => $val)
                {
                    $url .= '&filter_option['.$id.']=' . $val;
                }
            }

            if(!isset($this->request->get['ajax']))
            {
                $pagination = new Pagination();
                $pagination->total = $product_total;
                $pagination->page = $page;
                $pagination->limit = $limit;
                $pagination->text = $this->language->get('text_pagination');

                if($all)
                {
                    $pagination->url = $this->url->link('product/category', $url . '&page={page}');
                }
                else
                {
                    $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
                }



                $this->data['pagination'] = $pagination->render();

                $this->data['sort'] = $sort;
                $this->data['order'] = $order;
                $this->data['limit'] = $limit;

                $this->data['continue'] = $this->url->link('common/home');

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/product/category.tpl';
                } else {
                    $this->template = 'default/template/product/category.tpl';
                }

                $this->children = array(
                    'common/column_left',
                    'common/column_right',
                    'common/content_top',
                    'common/content_bottom',
                    'common/footer',
                    'common/header'
                );

                $this->response->setOutput($this->render());
            }
            else
            {

                $json = array(
                    'result' => 'success',
                    'html' => '',
                );

                $this->data['last_ajax_page'] = $last_ajax_page;

                if($last_ajax_page)
                {
                    $pagination = new Pagination();
                    $pagination->total = $product_total;
                    $pagination->page = $page;
                    $pagination->limit = $limit;
                    $pagination->text = $this->language->get('text_pagination');

                    if($all)
                    {
                        $pagination->url = $this->url->link('product/category', $url . '&page={page}');
                    }
                    else
                    {
                        $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
                    }


                    $this->data['pagination'] = $pagination->render();

                    $json['result'] = 'stop_loading';
                }

                if(empty($this->data['products']))
                {
                   // $this->response->setOutput('no_result');

                    $json['result'] = 'no_result';
                }
                else
                {
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category_ajax.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/product/category_ajax.tpl';
                    } else {
                        $this->template = 'default/template/product/category_ajax.tpl';
                    }

                    $this->children = array(

                    );

                    $json['html'] = $this->render();

                    //$this->response->setOutput($this->render());
                    $this->response->setOutput(json_encode($json));
                }

            }

					

    	} else {
			$url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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

            if (isset($this->request->get['filter_attribute'])) {
                foreach($this->request->get['filter_attribute'] as $id => $val)
                {
                    $url .= '&filter_attribute['.$id.']=' . $val;
                }

            }
            if (isset($this->request->get['filter_option'])) {
                foreach($this->request->get['filter_option'] as $id => $val)
                {
                    $url .= '&filter_option['.$id.']=' . $val;
                }
            }
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/category', $url),
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
				'common/header'
			);
					
			$this->response->setOutput($this->render());
		}
  	}
}
?>