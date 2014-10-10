<?php
class ModelModuleCrossSell extends Model {
		
	public function getAlsoBought($product_id){
		
	if (is_array($product_id)) {
		
		$this->load->model('module/cross_sell_multiple');
		$result=$this->model_module_cross_sell_multiple->getAlsoBought($product_id);
		return $result;
	} else {
		$this->load->model('module/cross_sell_single');
		$result=$this->model_module_cross_sell_single->getAlsoBought($product_id);
		return $result;
	}
	
		
	
}
}
?>