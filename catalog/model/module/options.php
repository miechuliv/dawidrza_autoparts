<?php
class ModelModuleOptions extends Controller {
	
		
		public function getOptions() {
		$option_data = array();

		$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "`option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
		
		foreach ($option_query->rows as $option) {
			if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
				$option_value_data = array();
			
				$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE   ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ovd.option_id = '" . (int)$option['option_id'] . "' ORDER BY ov.sort_order");
				
				foreach ($option_value_query->rows as $option_value) {
					if(isset($option_value['color'])){
						$color=$option_value['color'];
					}else{
						$color=false;
					}
					$option_value_data[] = array(
						
						'option_value_id'         => $option_value['option_value_id'],
						'name'                    => $option_value['name'],
						'color'                   => $color
								
					);
					
				}
									
				$option_data[] = array(
					
					'option_id'         => $option['option_id'],
					'name'              => $option['name'],
					'type'              => $option['type'],
					'option_value'      => $option_value_data,
					
				);
			} else {
				$option_data[] = array(
					
					'option_id'         => $option['option_id'],
					'name'              => $option['name'],
					'type'              => $option['type'],
					//'option_value'      => $option['option_value'],
					
				);				
			}
      	}
		
		return $option_data;
	}
	
}
?>