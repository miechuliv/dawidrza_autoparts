<?php

class ModelAllegroCategory extends Model {

	public function getProductsByCategory( $category_id ) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");
								  
		return $query->rows;
	}

	public function getProduct( $product_id ) {

		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
		return $query->row;
	}
	
	public function getProductAttributes($product_id) {
		$product_attribute_data = array();
		
		$product_attribute_query = $this->db->query("SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY pa.attribute_id");
		
		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();
			
			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
			
			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}
			
			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'name'                          => $product_attribute['name'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}
		
		return $product_attribute_data;
	}

	public function getProductOptions( $product_id ) {

		$product_option_data = array();
		
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') {
				$product_option_value_data = array();	
				
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],						
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']					
					);
				}
				
				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
				);				
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}	
		
		return $product_option_data;
	}

	public function getProductImages( $product_id ) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' order by sort_order asc");
		
		return $query->rows;
	}

	public function getProductSpecial( $product_id ) {

		$customer_group_id = $this->config->get( 'config_customer_group_id' ) ;
		$query = $this->db->query( "SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . ( int )$product_id . "' AND customer_group_id = '" . ( int )$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1" ) ;

		if ( $query->num_rows ) {

			return $query->row['price'] ;
		}
		else {

			return false ;
		}
	}

	public function getProductSpecials( $product_id ) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
		
		return $query->rows;
	}

	public function getTotalProductSpecials() {

		$customer_group_id = $this->config->get( 'config_customer_group_id' ) ;
		$query = $this->db->query( "SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . ( int )$this->config->get( 'config_store_id' ) . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.product_id NOT IN (SELECT pd2.product_id FROM " . DB_PREFIX . "product_discount pd2 WHERE p.product_id = pd2.product_id AND pd2.customer_group_id = '" . ( int )$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())))" ) ;

		if ( isset( $query->row['total'] ) ) {

			return $query->row['total'] ;
		}
		else {

			return 0 ;
		}
	}

	public function getProductRelated( $product_id ) {

		$product_data = array() ;
		$product_related_query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . ( int )$product_id . "'" ) ;

		foreach ( $product_related_query->rows as $result ) {

			$product_query = $this->db->query( "SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.product_id GROUP BY r.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) WHERE p.product_id = '" . ( int )$result['related_id'] . "' AND pd.language_id = '" . ( int )$this->config->get( 'config_language_id' ) . "' AND p2s.store_id = '" . ( int )$this->config->get( 'config_store_id' ) . "' AND ss.language_id = '" . ( int )$this->config->get( 'config_language_id' ) . "' AND p.date_available <= NOW() AND p.status = '1'" ) ;

			if ( $product_query->num_rows ) {

				$product_data[$result['related_id']] = $product_query->row ;
			}
		}

		return $product_data ;
	}

	public function getCategories( $product_id ) {

		$product_category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getTemplates() {

		$query = $this->db->query( "select * from allegro_szablony" ) ;

		return $query->rows ;
	}

	public function showTemplate( $TemplateName ) {

		$query = $this->db->query( "select * from allegro_szablony where name = '$TemplateName'" ) ;

		return $query->row ;
	}
    
    public function GetCategoriesByCatParent($CatParent) {
        
        $query = $this->db->query("select * from allegro_categories where cat_parent = $CatParent order by cat_position asc") ;
        
        return $query->rows ;
    }
    
    public function GetCategoryOptions($CatId) {
        
        $query = $this->db->query("select * from allegro_category_options 
            where `sell-form-cat` = $CatId or cat_id = $CatId") ;
        
        return $query->rows ;
    }
    
    public function AddCategoryOptions($Insert, $CatId) {
        
        foreach( $Insert as $I ) {
            $Ins[] = $this->db->escape($I) ;
        }
        
        $Ins = "$CatId,'".implode("','", $Ins)."'" ;
        
        $this->db->query("insert into allegro_category_options 
        	(cat_id, `sell-form-id`, `sell-form-title`, `sell-form-cat`, `sell-form-type`, `sell-form-res-type`, 
            `sell-form-def-value`, `sell-form-opt`, `sell-form-pos`, `sell-form-length`, `sell-min-value`, 
            `sell-max-value`, `sell-form-desc`, `sell-form-opts-values`, `sell-form-field-desc`, 
            `sell-form-param-id`, `sell-form-param-values`, `sell-form-parent-id`, `sell-form-parent-value`, 
            `sell-form-unit`, `sell-form-options`)
        	values ($Ins)") ;
    }
    
    public function GetChildCategoryOptions($OptionId, $ParentValue, $CategoryId) {
        
        $query = $this->db->query("select `sell-form-param-id` from allegro_category_options 
            where `sell-form-id` = $OptionId") ;
        
        $check = $query->rows ;
        
        if ( !empty($check) ) {
                
                $checcc = array() ;
                
                foreach( $check as $c ) {
                    if ( !in_array($c['sell-form-param-id'], $checcc) ) {
                        $checcc[] = $c['sell-form-param-id'] ;
                    }
                }
                
                $checcc = $checcc[0] ;

                $query2 = $this->db->query("select * from allegro_category_options 
                    where `sell-form-parent-id` = $checcc 
                    and `sell-form-parent-value` = '$ParentValue' 
                    and cat_id = $CategoryId") ;

                $Return = array(
                    'rows' => $query2->rows,
                    'parent' => $checcc
                ) ;
        } else {
            $Return = array() ;
        }

        return $Return ;
    }
}

?>