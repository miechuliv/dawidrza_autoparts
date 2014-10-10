<?php 
class ModelCatalogTopListDescription extends Model{



        public function add($data)
        {

            $this->db->query(" INSERT INTO `".DB_PREFIX."top_list_description` SET  `top_list_id` = '".(int)$data['top_list_id']."'  
 ,  `language_id` = '".(int)$data['language_id']."'  
 ,  `name` = '".$this->db->escape($data['name'])."'  
 " );

            return $this->db->getLastId();
        }

        public function edit($id,$data)
        {
            $this->db->query(" UPDATE `".DB_PREFIX."top_list_description` SET  `top_list_id` = '".(int)$data['top_list_id']."'  
 ,  `language_id` = '".(int)$data['language_id']."'  
 ,  `name` = '".$this->db->escape($data['name'])."'  
 WHERE `top_list_description_id` = '".$id."' ");
        }

        public function getTopListDescription($top_list_id,$language_id)
        {
            $result = $this->db->query(" SELECT * FROM `".DB_PREFIX."top_list_description`  WHERE `top_list_id` = '".$top_list_id."'
             AND language_id = '".(int)$language_id."' ");
            return $result->row;
        }

        public function getTopListDescriptions($top_list_id)
        {
            $result = $this->db->query(" SELECT * FROM `".DB_PREFIX."top_list_description` WHERE top_list_id = '".(int)$top_list_id."' ");
            return $result->rows;
        }

        public function delete($top_list_id)
        {

            $this->db->query("  DELETE FROM `".DB_PREFIX."top_list_description`  WHERE `top_list_id` = '".$top_list_id."' ");
        }


}