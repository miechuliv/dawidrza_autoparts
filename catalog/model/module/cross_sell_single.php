<?php
class ModelModuleCrossSellSingle extends Model {
		
	public function getAlsoBought($product_id){
	
	$try=$this->db->query("SELECT  order_id FROM  `order_product` WHERE product_id='".(int)$product_id."' ");
	if(!($try->num_rows>0)){
		return false;
		
	}
	$customers_ids=$this->db->query("SELECT customer_id FROM `order` WHERE order_id IN (SELECT  order_id FROM  `order_product` WHERE product_id='".(int)$product_id."')");
	if(!($customers_ids->num_rows>0)){
		return false;
		
	}
	$tmp=array();
	foreach ($customers_ids->rows as  $value) {
		//echo $value['customer_id'].'<br/>';
		$tmp[]=$value['customer_id'];
	}
	$target=join(',', $tmp);
	if(strlen($target)<=0){
		return false;
	}
	$orders_ids=$this->db->query("SELECT order_id FROM `order` WHERE customer_id IN (".$target.")");
	if(!($orders_ids->num_rows > 0)){
		return false;
	}
	$tmp=array();
	//echo 'orders';
	foreach ($orders_ids->rows as  $value) {
		//echo $value['order_id'].'<br/>';
		$tmp[]=$value['order_id'];
	}
	unset($target);
	$target=join(',', $tmp);
	if(strlen($target)<=0){
		return false;
	}
	//echo 'products';
	$results=$this->db->query("SELECT product_id, COUNT(product_id) as counter FROM `order_product` WHERE order_id IN (".$target.") AND product_id!='".(int)$product_id."' GROUP BY product_id ORDER BY counter DESC LIMIT 5 ");
	if(!($results->num_rows > 0)){
		return false;
	}
	$products_ids=array();
	foreach ($results->rows as $key => $value) {
		//echo $value['product_id'].' => '.$value['counter'].'<br/>';
		$products_ids[]=$value['product_id'];
	}
	
	$this->load->model('catalog/product');
	$product_data=array();
	foreach ($products_ids as $key => $value) {
		$tmp=array();
		$tmp=$this->model_catalog_product->getProduct($value);
		if(isset($tmp['product_id'])){
			$product_data[]=$tmp;
		}
		
	}
	
	// miehcu hot fix
    foreach ($product_data as $key => $product) {
        if($key>3){
        	unset($product_data[$key]);
        }
    }
	
  
	return $product_data;
	
	}
}
?>