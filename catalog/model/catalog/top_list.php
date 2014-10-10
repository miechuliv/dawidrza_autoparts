<?php 
class ModelCatalogTopList extends Model{



        public function add($data)
        {

            $this->db->query(" INSERT INTO `".DB_PREFIX."top_list` SET  `sort_order` = '".(int)$data['sort_order']."'  
 ,  `active` = '".(int)$data['active']."'  
 " );
            $id =  $this->db->getLastId();
            $this->load->model('catalog/top_list_description');
            $this->load->model('catalog/top_list_product');

            $this->model_catalog_top_list_description->delete($id);


            foreach($data['description'] as $language_id => $name)
            {
                $this->model_catalog_top_list_description->add(array(
                    'top_list_id' => $id,
                    'language_id' => $language_id,
                    'name' => $name['name']
                ));
            }




            $this->model_catalog_top_list_product->delete($id);

            foreach($data['products'] as $product)
            {

                $this->model_catalog_top_list_product->add(array(
                    'top_list_id' => $id,
                    'product_id' => $product['product_id'],
                    'product_sort_order' => $product['product_sort_order'],
                ));
            }


            return $id;
        }

        public function edit($id,$data)
        {
            $this->db->query(" UPDATE `".DB_PREFIX."top_list` SET  `sort_order` = '".(int)$data['sort_order']."'  
 ,  `active` = '".(int)$data['active']."'  
 WHERE `top_list_id` = '".$id."' ");


            $this->load->model('catalog/top_list_description');
            $this->load->model('catalog/top_list_product');

            $this->model_catalog_top_list_description->delete($id);

            foreach($data['description'] as $language_id => $name)
            {
                $this->model_catalog_top_list_description->add(array(
                    'top_list_id' => $id,
                    'language_id' => $language_id,
                    'name' => $name['name']
                ));
            }

            $this->model_catalog_top_list_product->delete($id);

            foreach($data['products'] as $product)
            {
                $this->model_catalog_top_list_product->add(array(
                    'top_list_id' => $id,
                    'product_id' => $product['product_id'],
                    'product_sort_order' => $product['product_sort_order'],
                ));
            }


        }

        public function getTopList($id)
        {
            $result = $this->db->query(" SELECT * FROM `".DB_PREFIX."top_list`  WHERE `top_list_id` = '".$id."' ");
            return $result->row;
        }

        public function getTopLists($filters)
        {
            $sql = " SELECT * FROM `".DB_PREFIX."top_list` WHERE `active` = '1' ORDER BY sort_order ASC ";

            if(isset($filters['limit']))
            {
                $sql .= " LIMIT ".(int)$filters['limit']." ";
            }

            $result = $this->db->query($sql);
            return $result->rows;
        }

        public function delete($id)
        {

            $this->db->query("  DELETE FROM `".DB_PREFIX."top_list`  WHERE `top_list_id` = '".$id."' ");
        }


}