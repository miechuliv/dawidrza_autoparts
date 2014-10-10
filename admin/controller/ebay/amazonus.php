<?php
class ControllerEbayamazonus extends Controller {
    
    public function allUpdate() {
        $this->load->model('amazonus/amazonus');
        $this->load->model('catalog/product');
        
        $itemLinks = $this->model_amazonus_amazonus->getProductLinks();
        
        $ids = array();
        foreach($itemLinks as $link) {
            $ids[] = $link['product_id'];
        }
        echo "Products to be updated:";
        print_r($ids);
        $this->amazonus->putStockUpdateBulk($ids, true);
        echo "Completed. More info amazonus_stocks.log";
    }
    
    public function stockUpdates() {
        $this->data = array_merge($this->data, $this->load->language('amazonus/stock_updates'));
        
        $this->document->setTitle($this->language->get('lang_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');
        
        
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/overview&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_overview'),
            'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/stockUpdates&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_stock_updates'),
            'separator' => ' :: '
        );
        
        $this->template = 'amazonus/stock_updates.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        
        $this->data['link_overview'] = $this->url->link('ebay/amazonus/overview', 'token=' . $this->session->data['token'], 'SSL');
        
        $requestArgs = array();
        
        if (isset($this->request->get['filter_date_start'])) {
            $requestArgs['date_start'] = date("Y-m-d", strtotime($this->request->get['filter_date_start']));
        } else {
            $requestArgs['date_start'] = date("Y-m-d");
        }
        
        if (isset($this->request->get['filter_date_end'])) {
            $requestArgs['date_end'] = date("Y-m-d", strtotime($this->request->get['filter_date_end']));
        } else {
            $requestArgs['date_end'] = date("Y-m-d");
        }
        
        $this->data['date_start'] = $requestArgs['date_start'];
        $this->data['date_end'] = $requestArgs['date_end'];

        $xml = $this->amazonus->getStockUpdatesStatus($requestArgs);
        $simpleXmlObj = simplexml_load_string($xml);
         $this->data['tableData'] = array();
        if($simpleXmlObj !== false) {
            
            $tableData = array();
            
            foreach($simpleXmlObj->update as $updateNode) {
                $row = array('date_requested' => (string) $updateNode->date_requested,
                    'date_updated' => (string) $updateNode->date_updated,
                    'status' => (string) $updateNode->status,
                    );
                $data = array();
                foreach($updateNode->data->product as $productNode) {
                    $data[] = array('sku' => (string) $productNode->sku,
                        'stock' => (int) $productNode->stock
                        );
                }
                $row['data'] = $data;
                $tableData[(int)$updateNode->ref] = $row;
            }
            
            $this->data['tableData'] = $tableData;
            
        } else {
            $this->data['error'] = 'Could not connect to OpenBay PRO API.';
        }
        
        $this->data['token'] = $this->session->data['token']; 
        
        $this->response->setOutput($this->render());
        
        
    }
    
    public function index() {
        $this->redirect($this->url->link('ebay/amazonus/overview', 'token=' . $this->session->data['token'], 'SSL'));
        return;
    }
    
    public function overview() {
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('amazonus/amazonus');
        $this->load->model('sale/customer_group');
        
        $this->data = array_merge($this->data, $this->load->language('amazonus/overview'));
        
        $this->document->setTitle($this->language->get('lang_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_overview'),
            'separator' => ' :: '
        );
        
        $this->template = 'amazonus/overview.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        $this->data['validation']               = $this->amazonus->validate();
        $this->data['links_settings']           = $this->url->link('ebay/amazonus/settings', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_subscription']       = $this->url->link('ebay/amazonus/subscription', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_itemlink']           = $this->url->link('ebay/amazonus/itemLinks', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_stockUpdates']           = $this->url->link('ebay/amazonus/stockUpdates', 'token=' . $this->session->data['token'], 'SSL');

        
        
        $this->response->setOutput($this->render());
    }
    
    public function subscription(){   
        $this->data = array_merge($this->data, $this->load->language('amazonus/subscription'));
        
        $this->document->setTitle($this->language->get('lang_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');
        
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/overview&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_overview'),
            'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/subscription&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_my_account'),
            'separator' => ' :: '
        );
        
        $this->data['link_overview'] = $this->url->link('ebay/amazonus/overview', 'token=' . $this->session->data['token'], 'SSL');
        
        $responseXml = simplexml_load_string($this->amazonus->callWithResponse('plans/getPlans'));
        
        $plans = array();
        
        if ($responseXml) {
            foreach ($responseXml->Plan as $plan) {
                $plans[] = array(
                    'title' => (string) $plan->Title,
                    'description' => (string) $plan->Description,
                    'order_frequency' => (string) $plan->OrderFrequency,
                    'product_listings' => (string) $plan->ProductListings,
                    'price' => (string) $plan->Price,
                );
            }
        }
        
        $this->data['plans'] = $plans;
        
        $responseXml = simplexml_load_string($this->amazonus->callWithResponse('plans/getUsersPlans'));
        
        $plan = false;
        
        if ($responseXml) {
            $plan = array(
                'user_status' => (string) $responseXml->UserStatus,
                'title' => (string) $responseXml->Title,
                'description' => (string) $responseXml->Description,
                'price' => (string) $responseXml->Price,
                'order_frequency' => (string) $responseXml->OrderFrequency,
                'product_listings' => (string) $responseXml->ProductListings,
                'listings_remain' => (string) $responseXml->ListingsRemain,
                'listings_reserved' => (string) $responseXml->ListingsReserved,
            );
        }
        
        $this->data['user_plan'] = $plan;
        $this->data['server'] = $this->amazonus->getServer();
        $this->data['token'] = $this->config->get('openbay_amazonus_token');
        
        $this->template = 'amazonus/subscription.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }
    
    public function settings() {
        $this->data = array_merge($this->data, $this->load->language('amazonus/settings'));
        $this->document->setTitle($this->language->get('lang_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');
        
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('amazonus/amazonus');
        $this->load->model('sale/customer_group');
        
        $settings = $this->model_setting_setting->getSetting('openbay_amazonus');
        

        //if 1.5.1.0 it return unserialized arrays
        
        /* Amazon US does not have multiple markeplace ids
        if (isset($settings['openbay_amazonus_default_listing_marketplace_ids'])) {
            $settings['openbay_amazonus_default_listing_marketplace_ids'] = $this->is_serialized($settings['openbay_amazonus_default_listing_marketplace_ids']) ? (array)unserialize($settings['openbay_amazonus_default_listing_marketplace_ids']) : $settings['openbay_amazonus_default_listing_marketplace_ids'];
        }
         */
        
        if (isset($settings['openbay_amazonus_orders_marketplace_ids'])) {
            $settings['openbay_amazonus_orders_marketplace_ids'] = $this->is_serialized($settings['openbay_amazonus_orders_marketplace_ids']) ? (array)unserialize($settings['openbay_amazonus_orders_marketplace_ids']) : $settings['openbay_amazonus_orders_marketplace_ids'];
        }
        
        
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {     
            
            if (!isset($this->request->post['openbay_amazonus_orders_marketplace_ids'])) {
                $this->request->post['openbay_amazonus_orders_marketplace_ids'] = array();
            }
            /*
            if (!isset($this->request->post['openbay_amazonus_default_listing_marketplace_ids'])) {
                $this->request->post['openbay_amazonus_default_listing_marketplace_ids'] = array();
            }
            */
            $settings = array_merge($settings, $this->request->post);
            $this->model_setting_setting->editSetting('openbay_amazonus', $settings);
                     
            $this->model_amazonus_amazonus->scheduleOrders($settings);
            $this->session->data['success'] = $this->language->get('lang_setttings_updated');
            $this->redirect($this->url->link('ebay/amazonus/overview', 'token=' . $this->session->data['token'], 'SSL'));
            return;
        }
        
        $this->data['link_overview'] = $this->url->link('ebay/amazonus/overview', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/overview&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_overview'),
            'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/settings&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_settings'),
            'separator' => ' :: '
        );
        
        
        $this->data['marketplace_ids']                  = (isset($settings['openbay_amazonus_orders_marketplace_ids']) ? (array)$settings['openbay_amazonus_orders_marketplace_ids'] : array('') );
        
        /*
        $this->data['default_listing_marketplace_ids']  = ( isset($settings['openbay_amazonus_default_listing_marketplace_ids']) ? (array)$settings['openbay_amazonus_default_listing_marketplace_ids'] : array() );
        $this->data['marketplaces'] = array(
            array('name' => $this->language->get('lang_de'), 'id' => 'A1PA6795UKMFR9', 'code' => 'de'),
            array('name' => $this->language->get('lang_fr'), 'id' => 'A13V1IB3VIYZZH', 'code' => 'fr'),
            array('name' => $this->language->get('lang_it'), 'id' => 'APJ6JRA9NG5V4', 'code' => 'it'),
            array('name' => $this->language->get('lang_es'), 'id' => 'A1RKKUPIHCS9HS', 'code' => 'es'),
            array('name' => $this->language->get('lang_uk'), 'id' => 'A1F83G8C2ARO7P', 'code' => 'uk'),
        );
        */
        
        $this->data['is_enabled'] = isset($settings['amazonus_status']) ? $settings['amazonus_status'] : '';
        $this->data['openbay_amazonus_token'] = isset($settings['openbay_amazonus_token']) ? $settings['openbay_amazonus_token'] : '';
        $this->data['openbay_amazonus_enc_string1'] = isset($settings['openbay_amazonus_enc_string1']) ? $settings['openbay_amazonus_enc_string1'] : '';
        $this->data['openbay_amazonus_enc_string2'] = isset($settings['openbay_amazonus_enc_string2']) ? $settings['openbay_amazonus_enc_string2'] : '';
        $this->data['openbay_amazonus_listing_tax_added'] = isset($settings['openbay_amazonus_listing_tax_added']) ? $settings['openbay_amazonus_listing_tax_added'] : '0.00';
        $this->data['openbay_amazonus_order_tax'] = isset($settings['openbay_amazonus_order_tax']) ? $settings['openbay_amazonus_order_tax'] : '0.00';
        
        $unshippedStatusId = isset($settings['openbay_amazonus_order_status_unshipped']) ? $settings['openbay_amazonus_order_status_unshipped'] : '';
        $partiallyShippedStatusId = isset($settings['openbay_amazonus_order_status_partially_shipped']) ? $settings['openbay_amazonus_order_status_partially_shipped'] : '';
        $shippedStatusId = isset($settings['openbay_amazonus_order_status_shipped']) ? $settings['openbay_amazonus_order_status_shipped'] : '';
        $canceledStatusId = isset($settings['openbay_amazonus_order_status_canceled']) ? $settings['openbay_amazonus_order_status_canceled'] : '';
        
        $amazonusOrderStatuses = array(
            'unshipped' => array('name' => $this->language->get('lang_unshipped'), 'order_status_id' => $unshippedStatusId),
            'partially_shipped' => array('name' => $this->language->get('lang_partially_shipped'), 'order_status_id' => $partiallyShippedStatusId),
            'shipped' => array('name' => $this->language->get('lang_shipped'), 'order_status_id' => $shippedStatusId),
            'canceled' => array('name' => $this->language->get('lang_canceled'), 'order_status_id' => $canceledStatusId),
        );
        
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        $this->data['openbay_amazonus_order_customer_group'] = isset($settings['openbay_amazonus_order_customer_group']) ? $settings['openbay_amazonus_order_customer_group'] : '';
        
        $this->data['amazonus_order_statuses'] = $amazonusOrderStatuses;
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->data['subscription_url'] = $this->url->link('ebay/amazonus/subscription', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['itemLinks_url'] = $this->url->link('amazonus/product/linkItems', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['openbay_amazonus_notify_admin'] = isset($settings['openbay_amazonus_notify_admin']) ? $settings['openbay_amazonus_notify_admin'] : '';
       
        
        $this->template = 'amazonus/settings.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        
        $pingInfo = simplexml_load_string($this->amazonus->callWithResponse('ping/info'));
        
        $api_status = false;
        $api_auth = false;
        if($pingInfo) {
            $api_status = ((string)$pingInfo->Api_status == 'ok') ? true : false;
            $api_auth = ((string)$pingInfo->Auth == 'true') ? true : false;
        }
        
        $this->data['API_status'] = $api_status;
        $this->data['API_auth'] = $api_auth;
        
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
    
    public function itemLinks() {
        $this->data = array_merge($this->data, $this->load->language('amazonus/item_links'));
        $this->document->setTitle($this->language->get('lang_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');
        
        $this->data['link_overview'] = $this->url->link('ebay/amazonus/overview', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/overview&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_overview'),
            'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=ebay/amazonus/itemLinks&token=' . $this->session->data['token'],
            'text'      => $this->language->get('lang_item_links'),
            'separator' => ' :: '
        );
        
        $this->data['token'] = $this->session->data['token']; 
        
        $this->data['addLink'] = $this->url->link('amazonus/product/addItemLink', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['removeLink'] = $this->url->link('amazonus/product/removeItemLink', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['loadLinks'] = $this->url->link('amazonus/product/getItemLinks', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['loadUnlinked'] = $this->url->link('amazonus/product/getUnlinkedItems', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->template = 'amazonus/item_links.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }

    protected function validate() {

        if (!$this->user->hasPermission('modify', 'ebay/amazonus')) {
            $this->error = $this->language->get('error_permission');
        }

        if (empty($this->error)) {
            return true;
        }

        return false;
    }
    
    public function install(){
        $this->load->model('amazonus/amazonus');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');

        $this->model_amazonus_amazonus->install();
        $this->model_setting_extension->install('ebay', $this->request->get['extension']);
    }
    
    public function uninstall(){
        $this->load->model('amazonus/amazonus');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');

        $this->model_amazonus_amazonus->uninstall();
        $this->model_setting_extension->uninstall('ebay', $this->request->get['extension']);
        $this->model_setting_setting->deleteSetting($this->request->get['extension']);
    }
}
