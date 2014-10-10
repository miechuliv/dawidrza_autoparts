<?php

class ControlleramazonusProduct extends Controller{
    
    public function index() {
        
        //Template stuff =>
        $this->load->language('amazonus/products'); 
        $this->load->language('catalog/product');
        $this->load->language('ebay/amazonus');

        $this->document->addStyle('view/stylesheet/openbay.css');
        
        $this->load->library('amazonus');
        $this->load->model('amazonus/amazonus');
        $this->load->model('catalog/product');
        $this->load->library('log');
        
        $logger = new Log('amazonus_product.log');

        $this->data = array_merge($this->data, $this->load->language('amazonus/products'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        
        $url = '';

        if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }	

        if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
        }
        
        $this->data['cancel_url'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $this->data['breadcrumbs'][] = array(
            'text' => 'Products',
            'href' => $this->data['cancel_url'],
            'separator' => ' :: '
        );

        $this->template = 'amazonus/product/form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->load->model('tool/image');
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        
        //<= END of template stuff
        
        if(isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
            $this->data['product_id'] = $product_id;
        }
        
        if(isset($this->request->get['edit_id'])) {
            $edit_id = $this->request->get['edit_id'];
            $this->data['edit_id'] = $edit_id;
        }
        $edit_var = isset($this->request->get['var']) ? $this->request->get['var'] : '';
        $this->data['edit_var'] = $edit_var;
        
        
        $errors = $this->model_amazonus_amazonus->getProductErrors(isset($edit_id) ? $edit_id : $product_id);
        $this->data['errors'] = array();
        foreach($errors as $error) {
            $error['message'] =  'Error for SKU: "' . $error['sku'] . '" - ' . $this->formatUrlsInText($error['message']);
            
            $this->data['errors'][] = $error;
        }
        
        /* 
         * Perform updates to database if form is posted
         */
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $dataArray = $this->request->post;
            
            if(isset($edit_id)) {
                $this->model_amazonus_amazonus->saveProduct($edit_id, $dataArray);
                $this->redirect($this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . $url . "&product_id=" . $edit_id . "&tab=saved", 'SSL'));
            } else {
                $this->model_amazonus_amazonus->saveProduct($product_id, $dataArray);
                $this->redirect($this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . $url . "&product_id=" . $product_id . "&tab=saved", 'SSL'));
            }
        }
        
        $product_info = $this->model_catalog_product->getProduct( isset($edit_id) ? $edit_id : $product_id);
        $this->data['listing_name'] = $product_info['name'] . " : " . $product_info['model'];
        $this->data['listing_sku'] = $product_info['sku'];
        $this->data['listing_url'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . (isset($edit_id) ? $edit_id : $product_id), 'SSL');
        
        if(isset($edit_id)) {
           $this->data['edit_product_category'] = $this->model_amazonus_amazonus->getProductCategory($edit_id, $edit_var); 
        } else {
            $this->data['edit_product_category'] = "";
        }
        
        /* 
         * Load saved listings
         */
        $this->data['saved_products'] = array();
        
        $saved_products = $this->model_amazonus_amazonus->getSavedProducts();
        
        $this->data['product_saved'] = false;
        
        foreach($saved_products as $saved_product) {
            
            $this->data['saved_products'][] = array(
                'product_id' => $saved_product['product_id'],
                'product_name' => $saved_product['product_name'],
                'product_model' => $saved_product['product_model'],
                'product_sku' => $saved_product['product_sku'],
                'amazonus_sku' => $saved_product['amazonus_sku'],
                'var' => $saved_product['var'],
                'edit_link' => $this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . '&edit_id=' . $saved_product['product_id'] . '&var=' . $saved_product['var'] . $url, 'SSL'),
            );
            
            if(isset($product_id) && $product_id == $saved_product['product_id'] && $edit_var == $saved_product['var']) {
                $this->data['product_saved'] = true;
            }
        }
        
        if(isset($edit_id)) {
            $pRow = $this->model_amazonus_amazonus->getProduct($edit_id, $edit_var);
            if(empty($pRow)) {
                $this->redirect($this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . $url . "&product_id=" . $edit_id . '&var=' . $edit_var . '&tab=' . $this->request->get['tab'], 'SSL'));
            }
        } else if(isset($product_id)) {
            $pRow = $this->model_amazonus_amazonus->getProduct($product_id, $edit_var);
            if(!empty($pRow)) {
                $this->redirect($this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . $url . "&edit_id=" . $product_id . '&var=' . $edit_var . '&tab=' . $this->request->get['tab'], 'SSL'));
            }
        }
        /*
         * Load available categories
         */       
        $am_categories = array();
        $this->data['inventory_loader'] = array();
        
        $amazonus_templates = $this->amazonus->getCategoryTemplates();
        
        foreach($amazonus_templates as $template) {
            $template = (array)$template;
            $data = array(
                    'friendly_name' => $template['friendly_name'],
                    'name' => $template['name'],
                    'template' => $template['xml']
                );
            if(isset($template['inventory_loader']) && $template['inventory_loader'] == true) {
                $this->data['inventory_loader'] = $data;
            } else {
                $am_categories[] = $data;
            }
        }
        $this->data['amazonus_categories'] = $am_categories;
        
        $this->data['selected_tab'] = 'quick';
        if($this->data['product_saved']) {
            $this->data['selected_tab'] = 'saved';
        } else if(!isset($this->data['inventory_loader']['name']) || (!empty($this->data['edit_product_category']) && $this->data['edit_product_category'] != $this->data['inventory_loader']['name'])) {
            $this->data['selected_tab'] = 'advanced';
        }
        
        if(isset($this->request->get['tab'])) {
            $this->data['selected_tab'] = $this->request->get['tab'];
        }
        
        
        /*
         * Provide urls to ajax and javascripts
         */
         if(isset($edit_id)) {
            $this->data['template_parser_url'] = $this->url->link('amazonus/product/parseTemplate&edit_id=' . $edit_id, 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $this->data['template_parser_url'] = $this->url->link('amazonus/product/parseTemplate&product_id=' . $product_id, 'token=' . $this->session->data['token'], 'SSL');
        }
        $this->data['remover_url'] = $this->url->link('amazonus/product/deleteSaved', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['uploader_url'] = $this->url->link('amazonus/product/uploadSaved', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['main_url'] = $this->url->link('amazonus/product', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['token'] = $this->session->data['token'];
        
        $this->data['loadLinks'] = $this->url->link('amazonus/product/getLinkedSkus', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['removeLink'] = $this->url->link('amazonus/product/removeItemLink', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['addLink'] = $this->url->link('amazonus/product/addItemLink', 'token=' . $this->session->data['token'], 'SSL');
        
        if($this->amazonus->addonLoad('openstock') == true) {
            $this->load->model('openstock/openstock');
            $this->data['options'] = $this->model_openstock_openstock->getProductOptionStocks(isset($edit_id) ? $edit_id : $product_id);
        } else {
            $this->data['options'] = array();
        }
        
        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('openbay_amazonus');
        
        
        $this->response->setOutput($this->render());
    }
    
    private function is_serialized( $data ) {
        // if it isn't a string, it isn't serialized
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (!preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }
        return false;
    }
    
    public function uploadSaved() {
        ob_start();
        $this->load->library('amazonus');
        $this->load->model('amazonus/amazonus');
        $logger = new Log('amazonus_product.log');
        
        $logger->write('Uploading process started.');
        
        $savedProducts = $this->model_amazonus_amazonus->getSavedProductsData();
        
        if(empty($savedProducts)) {
            $logger->write('No saved listings found. Uploading canceled.');
            $result['status'] = 'No saved listings. Nothing to upload. Aborting.';
            $result = json_encode($result);
            ob_clean();
            $this->response->setOutput($result);
            return;
        }
        
        foreach($savedProducts as $savedProduct) {
            $productDataDecoded = (array)json_decode($savedProduct['data']);
            
            $catalog = defined(HTTPS_CATALOG) ? HTTPS_CATALOG : HTTP_CATALOG;
            $response_data = array("response_url" => $catalog . 'index.php?route=amazonus/product/inbound');
            $category_data = array('category' => (string)$savedProduct['category']);
            $fields_data = array('fields' => (array)$productDataDecoded['fields']);
            
            $mpArray = array();
            $marketplaces_data = array('marketplaces' => $mpArray);
            
            $productData = array_merge($category_data, $fields_data, $response_data, $marketplaces_data);
            $insertion_response = $this->amazonus->insertProduct($productData);
            
            $logger->write("Uploading product with data:" . print_r($productData, true) . "
                Got response:" . print_r($insertion_response, true));
            
            if(!isset($insertion_response['status']) || $insertion_response['status'] == 'error') {
                if(isset($insertion_response['info'])) {
                    $result['info'] = $insertion_response['info'];
                } else {
                    $result['info'] = '';
                }
                $result['status'] = 'error';
        //        $logger->write('Product upload failed. Reseting insertion with id: ' . $insertion_id);
        //        $this->model_amazonus_amazonus->resetUploaded($insertion_id);
                break;
            }
            $logger->write('Product upload success');
            $this->model_amazonus_amazonus->setProductUploaded($savedProduct['product_id'], $insertion_response['insertion_id'], $savedProduct['var']);
        }
        
        if(!isset($result['status'])) {
            $result['status'] = 'ok';
            $logger->write('Uploading process completed successfully.');
        } else {
            $logger->write('Uploading process failed with status message: ' . $result['status']);
        }
        $result = json_encode($result);
        ob_clean();
        $this->response->setOutput($result);
    }
    
    public function parseTemplate() {
        ob_start();
        
        $this->load->library('log');
        $logger = new Log('amazonus_product.log');
        
        $result = array();
        
        if(isset($this->request->get['xml'])) {
            $templateName = $this->request->get['xml'];
            
            $this->load->library('amazonus_category_template');
            $this->load->library('amazonus');
            
            
            $templateParser = new amazonus_category_template();
            $data = array('template' => $templateName);
            $response = $this->amazonus->callWithResponse("productv2/GetTemplateXml", $data);
            
            if(!$templateParser->load($response, false)) {
                $logger->write("admin/amazonus/product/parseTemplate failed to load template parses. name=" . $templateName);
                return;
            }
            $category = $templateParser->getCategoryName();
            $fields = $templateParser->getAllFields();
            
            $requestedVar = isset($this->request->get['var']) ? $this->request->get['var'] : '';
            
            if(isset($this->request->get['edit_id'])) {
                $fields = $this->fillSavedValues($this->request->get['edit_id'], $fields, $requestedVar);
            }
            elseif(isset($this->request->get['product_id'])) {
                $fields = $this->fillDefaultValues($this->request->get['product_id'], $fields, $requestedVar);         
            }
            
            $result = array(
                "category" => $category,
                "fields" => $fields);
        }
        $result = json_encode($result);
        
        ob_clean();
        $this->response->setOutput($result);
    }
    
    private function fillDefaultValues($product_id, $fields_array, $var = '') {
        $this->load->model('catalog/product');
        $this->load->model('setting/setting');
        $this->load->model('amazonus/amazonus');
        $openbay_settings = $this->model_setting_setting->getSetting('openbay_amazonus');
        
        $product_info = $this->model_catalog_product->getProduct($product_id);
        
        $product_info['description'] = strip_tags(html_entity_decode($product_info['description']), "<br>");
        
        $product_info['image'] = HTTPS_CATALOG . 'image/' . $product_info['image'];
        
        $tax_added = isset($openbay_settings['openbay_amazonus_listing_tax_added']) ? $openbay_settings['openbay_amazonus_listing_tax_added'] : 0;
        $product_info['price'] = number_format($product_info['price'] + $tax_added / 100 * $product_info['price'], 2, '.', '');
        
        /*Key must be lowecase */
        $defaults = array(
            'sku' => $product_info['sku'],
            'title' => $product_info['name'],
            'productname' => $product_info['name'],
            'product-name' => $product_info['name'],
            'quantity' => $this->model_amazonus_amazonus->getProductQuantity($product_id, $var),
            'item-price' => $product_info['price'],
            'price' => $product_info['price'],
            'itemprice' => $product_info['price'],
            'standardprice' => $product_info['price'],
            'description' => $product_info['description'],
            'mainimage' => $product_info['image'],
        );
        
        if(!empty($product_info['upc'])) {
            $defaults['type'] = 'UPC';
            $defaults['value'] = $product_info['upc'];
        } else if(!empty($product_info['ean'])) {
            $defaults['type'] = 'EAN';
            $defaults['value'] = $product_info['ean'];
        }
        
        $this->load->library('amazonus');
        if($var !== '' && $this->amazonus->addonLoad('openstock')) {
            $this->load->model('tool/image');
            $this->load->model('openstock/openstock');
            $optionStocks = $this->model_openstock_openstock->getProductOptionStocks($product_id);
            
            $option = null;
            foreach ($optionStocks as $optionIterator) {
                if($optionIterator['var'] === $var) {
                    $option = $optionIterator;
                    break;
                }
            }
            
            if($option != null) {
                $defaults['sku'] = $option['sku'];
                $defaults['quantity'] = $option['stock'];
                $defaults['standardprice'] = number_format($option['price'] + $tax_added / 100 * $option['price'], 2, '.', '');
                if(!empty($option['image'])) {
                    $defaults['mainimage'] = HTTPS_CATALOG . 'image/' . $option['image'];
                }

            }
        }
        
        $filledArray = array();
        
        foreach($fields_array as $field) {
            
            $value_array = array('value' => '');
            
            if(isset($defaults[strtolower($field['name'])])) {
                $value_array = array('value' => $defaults[strtolower($field['name'])]);
            }
            
            $filledItem = array_merge($field, $value_array);
            
            $filledArray[] = $filledItem;
        }
        return $filledArray;
    }
    
    private function fillSavedValues($product_id, $fields_array, $var = '') {
        
        $this->load->model('amazonus/amazonus');
        $savedListing = $this->model_amazonus_amazonus->getProduct($product_id, $var);
        
        $decoded_data = (array)json_decode($savedListing['data']);
        $saved_fields = (array)$decoded_data['fields'];
        
        //Show current quantity instead of last uploaded
        $saved_fields['Quantity'] = $this->model_amazonus_amazonus->getProductQuantity($product_id, $var);
        
        $filledArray = array();
        
        foreach($fields_array as $field) {
            $value_array = array('value' => '');
            
            if(isset($saved_fields[$field['name']])) {
                $value_array = array('value' => $saved_fields[$field['name']]);
            }
            
            $filledItem = array_merge($field, $value_array);
            
            $filledArray[] = $filledItem;
        }

        return $filledArray;
    }
    
    public function deleteSaved() {
        if(!isset($this->request->get['product_id']) || !isset($this->request->get['var'])) {
            return;
        }
        
        $this->load->model('amazonus/amazonus');
        $this->model_amazonus_amazonus->deleteSaved($this->request->get['product_id'], $this->request->get['var']);
    }
    
    //Only for developer via direct link
    public function resetPending() {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "amazonus_product`
            SET `status` = 'saved'
            WHERE `status` = 'uploaded'");
    }
    
    //TODO if javascript validation is not enough
    private function validateForm() {
        return true;
    }
    
    private function formatUrlsInText($text){
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        preg_match_all($reg_exUrl, $text, $matches);
        $usedPatterns = array();
        foreach($matches[0] as $pattern){
            if(!array_key_exists($pattern, $usedPatterns)){
                $usedPatterns[$pattern]=true;
                $text = str_replace($pattern, "<a target='_blank' href=" .$pattern .">" . $pattern . "</a>", $text);   
            }
        }
        return $text;
    }
    
    /*
     * Item links (for individual products)
     */
    public function getLinkedSkus() {
        if(!isset($this->request->get['product_id'])) {
            $this->response->setOutput(json_encode("error"));
            return;
        }
        
        $this->load->model('amazonus/amazonus');
        
        $itemLinks = $this->model_amazonus_amazonus->getProductLinks($this->request->get['product_id']);
        
        $this->load->library('amazonus');
        if($this->amazonus->addonLoad('openstock') == true) {
            $this->load->model('tool/image');
            $this->load->model('openstock/openstock');
            $linksWithVar = array();
            foreach($itemLinks as $row) {
                $stockOpts = $this->model_openstock_openstock->getProductOptionStocks($row['product_id']);
                foreach($stockOpts as $opt) {
                    if($opt['var'] == $row['var']) {
                        $row['combi'] = $opt['combi'];
                        break;
                    }
                }
                $linksWithVar[] = $row;
            }
            $itemLinks = $linksWithVar;
        } 
        
        
        $result = json_encode($itemLinks);
        $this->response->setOutput($result);   
    }
    
    
    public function addItemLink() {
        if(isset($this->request->get['product_id']) && isset($this->request->get['amazonus_sku'])) {
            $amazonus_sku = $this->request->get['amazonus_sku'];
            $product_id = $this->request->get['product_id'];
            $var = isset($this->request->get['var']) ? $this->request->get['var'] : '';
            
        } else {
            $result = json_encode('error');
            $this->response->setOutput($result);
            return;
        }
        $this->load->model('amazonus/amazonus');
        $this->load->library('amazonus');
        $this->model_amazonus_amazonus->linkProduct($amazonus_sku, $product_id, $var);
        $logger = new Log('amazonus_stocks.log');
        $logger->write('addItemLink() called for product id: ' . $product_id . ', amazonus sku: ' . $amazonus_sku . ', var: ' . $var);
        
        if($var != '' && $this->amazonus->addonLoad('openstock') == true) {
            $logger->write('Using openStock');
            $this->load->model('tool/image');
            $this->load->model('openstock/openstock');
            $optionStocks = $this->model_openstock_openstock->getProductOptionStocks($product_id);
            $quantityData = array();
            foreach($optionStocks as $optionStock) {
                if(isset($optionStock['var']) && $optionStock['var'] == $var) {
                    $quantityData[$amazonus_sku] = $optionStock['stock'];
                    break;
                }
            }
            if(!empty($quantityData)) {
                $logger->write('Updating quantities with data: ' . print_r($quantityData, true));
                $this->amazonus->updateQuantities($quantityData);
            } else {
                $logger->write('No quantity data will be posted.');
            } 
        } else {
            $this->amazonus->putStockUpdateBulk(array($product_id));
        }
        
        $result = json_encode('ok');
        $this->response->setOutput($result);   
        $logger->write('addItemLink() exiting');
    }
    
    public function removeItemLink() {
        if(isset($this->request->get['amazonus_sku'])) {
            $amazonus_sku = $this->request->get['amazonus_sku'];            
        } else {
            $result = json_encode('error');
            $this->response->setOutput($result);
            return;
        }
        $this->load->model('amazonus/amazonus');
        
        $this->model_amazonus_amazonus->removeProductLink($amazonus_sku);
        
        $result = json_encode('ok');
        $this->response->setOutput($result);   
    }
    
    public function getItemLinks() {
        $this->load->model('amazonus/amazonus');
        $this->load->model('catalog/product');
        
        $itemLinks = $this->model_amazonus_amazonus->getProductLinks();
        $result = json_encode($itemLinks);
        $this->response->setOutput($result);   
    }
    
    public function getUnlinkedItems() {
        $this->load->model('amazonus/amazonus');
        $this->load->model('catalog/product');
        
        $unlinkedProducts = $this->model_amazonus_amazonus->getUnlinkedProducts();
        $result = json_encode($unlinkedProducts);
        $this->response->setOutput($result);  
    }
}