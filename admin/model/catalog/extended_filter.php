<?php 
class ModelCatalogExtendedFilter extends Model{



        public function add($data)
        {

            $this->db->query(" INSERT INTO `".DB_PREFIX."extended_filter` SET  `category_id` = '".(int)$data['category_id']."'  
         ,  `sort_order` = '".(int)$data['sort_order']."'
         ,  `type` = '".$this->db->escape($data['type'])."'

         ,  `attribute_or_option_id` = '".(int)$data['attribute_or_option_id']."'
         ,  `active` = '".(int)$data['active']."'
         " );

            $extended_filter_id =  $this->db->getLastId();

            $this->db->query("  DELETE FROM `".DB_PREFIX."extended_filter_description`  WHERE `extended_filter_id` = '".$extended_filter_id."' ");

            foreach($data['description'] as $language_id => $description){

                $this->db->query(" INSERT INTO `".DB_PREFIX."extended_filter_description` SET  `language_id` = '".(int)$language_id."'
         ,  `extended_filter_id` = '".(int)$extended_filter_id."'
         ,  `name` = '".$this->db->escape($description['name'])."'

         " );
            }
        }

        public function edit($id,$data)
        {
            $this->db->query(" UPDATE `".DB_PREFIX."extended_filter` SET  `category_id` = '".(int)$data['category_id']."'  
             ,  `sort_order` = '".(int)$data['sort_order']."'
             ,  `type` = '".$this->db->escape($data['type'])."'

             ,  `attribute_or_option_id` = '".(int)$data['attribute_or_option_id']."'
             ,  `active` = '".(int)$data['active']."'
             WHERE `extended_filter_id` = '".$id."' ");

            $extended_filter_id =  $id;

            $this->db->query("  DELETE FROM `".DB_PREFIX."extended_filter_description`  WHERE `extended_filter_id` = '".$extended_filter_id."' ");

            foreach($data['description'] as $language_id => $description){

                $this->db->query(" INSERT INTO `".DB_PREFIX."extended_filter_description` SET  `language_id` = '".(int)$language_id."'
         ,  `extended_filter_id` = '".(int)$extended_filter_id."'
         ,  `name` = '".$this->db->escape($description['name'])."'

         " );
            }

            return $id;
        }

        public function getExtendedFilter($id)
        {
            $result = $this->db->query(" SELECT * FROM `".DB_PREFIX."extended_filter`  WHERE `extended_filter_id` = '".$id."' ");
            return $result->row;
        }

        public function getExtendedFilters()
        {
            $result = $this->db->query(" SELECT * FROM `".DB_PREFIX."extended_filter` ");
            return $result->rows;
        }

        public function getExtendedFiltersByCategory($category_id)
        {
            $result = $this->db->query(" SELECT * FROM `".DB_PREFIX."extended_filter` WHERE `category_id` = '".(int)$category_id."' ");

            $data = array();

            foreach($result->rows as $row)
            {
                $row['description'] = $this->getExtendedFilterDescriptions($row['extended_filter_id']);
                $data[] = $row;
            }

            return $data;
        }

        public function delete($id)
        {

            $this->db->query("  DELETE FROM `".DB_PREFIX."extended_filter`  WHERE `extended_filter_id` = '".$id."' ");
        }

    public function deleteByCategoryId($id)
    {

        $this->db->query("  DELETE FROM `".DB_PREFIX."extended_filter`  WHERE `category_id` = '".$id."' ");
    }

    public function getExtendedFilterDescriptions($id)
    {
        $result = $this->db->query(" SELECT * FROM `".DB_PREFIX."extended_filter_description` WHERE `extended_filter_id` = '".$id."'  ");
        $data = array();

        foreach($result->rows as $row)
        {
                $data[$row['language_id']] = $row;
        }

        return $data;
    }


}