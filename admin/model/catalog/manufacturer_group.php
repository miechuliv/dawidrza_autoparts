<?php
class ModelCatalogManufacturerGroup extends Model {
    public function addManufacturerGroup($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_group SET name = '".$this->db->escape($data['name'])."'  ");

        $manufacturer_group_id = $this->db->getLastId();

        $this->cache->delete('manufacturer_group');

        return $manufacturer_group_id;
    }

    public function editManufacturerGroup($manufacturer_group_id, $data) {

        $this->db->query("UPDATE " . DB_PREFIX . "manufacturer_group SET name = '" . $this->db->escape($data['name']) . "' WHERE manufacturer_group_id = '" . (int)$manufacturer_group_id . "'");

        $this->cache->delete('manufacturer_group');
    }

    public function deleteManufacturerGroup($manufacturer_group_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_group WHERE manufacturer_group_id = '" . (int)$manufacturer_group_id . "'");


        $this->cache->delete('manufacturer_group');
    }

    public function getManufacturerGroup($manufacturer_group_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "manufacturer_group WHERE manufacturer_group_id = '" . (int)$manufacturer_group_id . "'");

        return $query->row;
    }

    public function getManufacturerGroups($data = array()) {

        $sql = "SELECT * FROM " . DB_PREFIX . "manufacturer_group";

        if (isset($data['filter_name']) AND !empty($data['filter_name'])) {
            $sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sort_data = array(
            'name',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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



    public function getTotalManufacturerGroups() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer_group");

        return $query->row['total'];
    }
}
?>