<?php
class ModelCatalogRetailer extends Model {
	public function addretailer($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "retailer SET retailer_name = '" . $this->db->escape($data['name']) . "' ");

		
		$this->cache->delete('retailer');
	}
	
	public function editretailer($retailer_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "retailer SET retailer_name = '" . $this->db->escape($data['name']) . "' WHERE retailer_id = '" . (int)$retailer_id . "'");

		
		$this->cache->delete('retailer');
	}
	
	public function deleteretailer($retailer_id) {
     
		$this->db->query("DELETE FROM " . DB_PREFIX . "retailer WHERE retailer_id = '" . (int)$retailer_id . "'");

			
		$this->cache->delete('retailer');
	}	
	
	public function getretailer($retailer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "retailer WHERE retailer_id = '" . (int)$retailer_id . "'");
		
		return $query->row;
	}
	
	public function getretailers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "retailer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE retailer_name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
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


	public function getTotalretailers() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "retailer");
		
		return $query->row['total'];
	}	
}
?>