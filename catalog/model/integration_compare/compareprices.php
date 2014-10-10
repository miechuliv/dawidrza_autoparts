<?php
class ModelIntegrationCompareCompareprices extends Model {
	public function saveLastDate($type) {
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET value = '" . date("Y-m-d H:i:s") . "' WHERE `group` = 'compareprices_" . $type . "' AND `key` = '" . $type . "_data_export'");
	}

	public function getAllCategories() {
		$query = $this->db->query("SELECT c.category_id, cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		
		return $query->rows;
	}

	public function getCategories($parent_id) {
			$category_data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		foreach ($query->rows as $result) {
			$category_data[] = array(
				'category_id' => $result['category_id'],
				'name'        => $this->getPath($result['category_id'], $this->config->get('config_language_id'))
			);

			$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
		}

		return $category_data;
	}

	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1' ORDER BY c.sort_order, cd.name ASC");

		$category_info = $query->row;

		if ($category_info['parent_id']) {
			return $this->getPath($category_info['parent_id'], $this->config->get('config_language_id')) . '/' . $category_info['name'];
		} else {
			return $category_info['name'];
		}
	}
}
?>