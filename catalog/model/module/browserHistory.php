<?php  
class ModelModuleBrowserHistory extends Model {
	
	
	public function saveBrowseHistoryByIp($customer_ip,$product_id,$limit){
		
		// get all history associated with given customer_ip
		if(!$customer_ip OR !$product_id){
			return false;
		}
		
		$query=$this->db->query("SELECT * FROM browseHistory WHERE customer_ip='".$customer_ip."' Order by browseHistory_id ASC");
		
		if($query->num_rows > 0){
			
			 
			$ids=array();
			foreach ($query->rows as $key => $value) {
			     	$ids[]=(int)$value['browseHistory_id'];
			}

            $terminator = 0;
			
			if(count($ids)>(int)$limit){
				$terminator=$ids[count($ids)-(int)$limit-1];
			}
			
		// leave only 4 recent, delete older rows	
		 $query = $this->db->query("DELETE FROM browseHistory WHERE browseHistory_id  <= '".$terminator."' ");
		}
		
		// save current row
		$this->db->query("INSERT INTO browseHistory SET customer_ip='".$customer_ip."', product_id='".$product_id."' ");
		
		return true;
	}
	
	public function getBrowseHistoryByIp($customer_ip,$limit){
		
		
		$query=$this->db->query("SELECT * FROM browseHistory WHERE customer_ip='".$customer_ip."' Order by browseHistory_id DESC LIMIT  ".$limit." ");
		
		if($query->num_rows > 0){
			
			$data=array();
			foreach ($query->rows as $key => $value) {
			     	$data[]=$value['product_id'];
			}
			
			return $data;
			
		}else{
			
			return false;
			 
		}
		
		
		
	}
	
	
}
?>