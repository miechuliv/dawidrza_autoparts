<?php 
class ModelCatalogAttribute extends Model {
	public function addAttribute($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "'
		, sort_order = '" . (int)$data['sort_order'] . "'
		, front = '" . (int)$data['front'] . "'


		");
		
		$attribute_id = $this->db->getLastId();
		
		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
	}

    public function getAttributeValues($attribute_id)
    {
        $q = $this->db->query("SELECT DISTINCT(text) FROM `".DB_PREFIX."product_attribute` WHERE attribute_id = '".(int)$attribute_id."' ");

        return $q->rows;
    }

    public function getAttributeValuesByCategory($attribute_id,$category_id)
    {
        $q = $this->db->query("SELECT DISTINCT(pa.text) FROM `".DB_PREFIX."product_attribute` pa
            LEFT JOIN product p ON(pa.product_id=p.product_id)
             LEFT JOIN product_to_category ptc ON(p.product_id=ptc.product_id) WHERE pa.attribute_id = '".(int)$attribute_id."'
              AND ptc.category_id = '".(int)$category_id."' ");

        return $q->rows;
    }

	public function editAttribute($attribute_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "'
		, sort_order = '" . (int)$data['sort_order'] . "'
		 , front = '" . (int)$data['front'] . "'
		 WHERE attribute_id = '" . (int)$attribute_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
	}
	
	public function deleteAttribute($attribute_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");
	}
		
	public function getAttribute($attribute_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE a.attribute_id = '" . (int)$attribute_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
		
	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}
								
		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY attribute_group, ad.name";	
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
		
	public function getAttributeDescriptions($attribute_id) {
		$attribute_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");
		
		foreach ($query->rows as $result) {
			$attribute_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $attribute_data;
	}
		
	public function getAttributesByAttributeGroupId($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}
								
		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ad.name";	
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
	
	public function getTotalAttributes() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute");
		
		return $query->row['total'];
	}	
	
	public function getTotalAttributesByAttributeGroupId($attribute_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		
		return $query->row['total'];
	}

    public function getAttributeValuesByAttribute($attribute_id = false)
    {
        if($attribute_id)
        {
            $query = $this->db->query("SELECT DISTINCT(text) FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '".(int)$attribute_id."' ");
        }
        else
        {
            $query = $this->db->query("SELECT DISTINCT(text) FROM " . DB_PREFIX . "product_attribute ");

        }


        $values = array();

        if($query->row)
        {
            foreach($query->rows as $row)
            {
                if($row['text'] AND $row['text']!='')
                {
                    $values[] = $row['text'];
                }

            }
        }

        return $values;
    }
}
?>