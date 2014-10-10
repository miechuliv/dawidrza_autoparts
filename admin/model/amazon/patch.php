<?php
/**
 * Created by James Allsup
 */
class ModelAmazonPatch extends Model
{ 
    public function runPatch($manual = true){
        /*
         * Manual flag to true is set when the user runs the patch method manually
         * false is when the module is updated using the update system
         */
        $this->load->model('setting/setting');

        $settings = $this->model_setting_setting->getSetting('openbay_amazon');
        
        if($settings) {
            $amazonSkuColumn = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazon_product_link` WHERE `Field` = 'amazon_sku'")->rows;
            $productIdColumn = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazon_product_link` WHERE `Field` = 'product_id'")->rows;
            $allColumns = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazon_product_link`")->rows;

            
            //Check if we have version without openStock linking
            if(count($amazonSkuColumn) == 1 && count($productIdColumn) == 1 && count($allColumns) == 2) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazon_product_link` 
                    DROP PRIMARY KEY,
                    ADD `id` int(11) NOT NULL AUTO_INCREMENT,
                    AUTO_INCREMENT = 1,
                    ADD PRIMARY KEY (`id`),
                    ADD `var` char(100) NOT NULL DEFAULT ''");
            }
            
            //Check if we have amazon_product table without "var" column
            $amazonProductVarColumn = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazon_product` WHERE `Field` = 'var'")->rows;
            if(count($amazonProductVarColumn) != 1) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazon_product`
                    DROP PRIMARY KEY,
                    ADD `var` char(100) NOT NULL DEFAULT ''");
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazon_product`
                    ADD PRIMARY KEY (`product_id`, `var`)");
                
            }
            
            //Check if we have amazon_product table without "marketplaces" column
            $amazonProductMarketplacesColumn = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazon_product` WHERE `Field` = 'marketplaces'")->rows;
            if(count($amazonProductMarketplacesColumn) != 1) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazon_product`
                    ADD `marketplaces` text NOT NULL");
            }
            
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY COLUMN `upc` varchar(128);");
            
            //Check if we have amazon_order table without courier and tracking columns
            $amazonOrderCourierColumn = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "amazon_order` WHERE `Field` = 'courier_id'")->rows;
            if(count($amazonOrderCourierColumn) != 1) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "amazon_order`
                    ADD(`courier_id` varchar(255) NOT NULL,
                        `courier_other` tinyint(1) NOT NULL,
                        `tracking_no` varchar(255) NOT NULL)");
            }
            
            
        }
        
        /*
         * Always return true
         */
        return true;
    } 
}