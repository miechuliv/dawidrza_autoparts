<?php
class ControllerExtensionEbay extends Controller {
    public function index() {
        $this->load->model('openbay/openbay');
        $this->load->model('setting/extension');
        $this->data = array_merge($this->data, $this->load->language('extension/openbay'));

        $this->document->setTitle($this->language->get('lang_heading_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('lang_heading_title'),
            'href' => $this->url->link('extension/ebay', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['mcrypt']           = $this->model_openbay_openbay->checkMcrypt();
        $this->data['mbstring']         = $this->model_openbay_openbay->checkMbstings();
        $this->data['ftpenabled']       = $this->model_openbay_openbay->checkFtpenabled();
        $this->data['manage_link']  = $this->url->link('extension/ebay/manage', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['success']      = '';
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $this->data['error']        = '';
        if (isset($this->session->data['error'])) {
            $this->data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        $extensions = $this->model_setting_extension->getInstalled('ebay');

        foreach ($extensions as $key => $value) {
            if (!file_exists(DIR_APPLICATION . 'controller/ebay/' . $value . '.php')) {
                $this->model_setting_extension->uninstall('ebay', $value);
                unset($extensions[$key]);
            }
        }

        $this->data['extensions'] = array();

        $files = glob(DIR_APPLICATION . 'controller/ebay/*.php');

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                $this->load->language('ebay/' . $extension);

                $action = array();

                if (!in_array($extension, $extensions)) {
                    $action[] = array(
                        'text' => $this->language->get('text_install'),
                        'href' => $this->url->link('extension/ebay/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
                    );
                } else {
                    $action[] = array(
                        'text' => $this->language->get('text_edit'),
                        'href' => $this->url->link('ebay/' . $extension, 'token=' . $this->session->data['token'], 'SSL')
                    );

                    $action[] = array(
                        'text' => $this->language->get('text_uninstall'),
                        'href' => $this->url->link('extension/ebay/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
                    );
                }

                $this->data['extensions'][] = array(
                    'name' => $this->language->get('heading_title'),
                    'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'action' => $action
                );
            }
        }

        $this->template = 'extension/openbay.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('openbaymanager');

        if (isset($settings['openbay_version'])) {
            $this->data['openbay_version'] = $settings['openbay_version'];
        } else {
            $this->load->model('openbay/version');
            $this->data['openbay_version']  = $this->model_openbay_version->getVersion();
            $settings['openbay_version']    = $this->model_openbay_version->getVersion();
            $this->model_setting_setting->editSetting('openbaymanager', $settings);
        }

        $this->response->setOutput($this->render());
    }

    public function install() {
        if (!$this->user->hasPermission('modify', 'extension/ebay')) {
            $this->session->data['error'] = $this->language->get('error_permission');
            $this->redirect($this->url->link('extension/ebay', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $this->load->model('user/user_group');

            $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'ebay/' . $this->request->get['extension']);
            $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'ebay/' . $this->request->get['extension']);

            require_once(DIR_APPLICATION . 'controller/ebay/' . $this->request->get['extension'] . '.php');

            $class = 'ControllerEbay' . str_replace('_', '', $this->request->get['extension']);
            $class = new $class($this->registry);

            if (method_exists($class, 'install')) {
                $class->install();
            }

            $this->redirect($this->url->link('extension/ebay', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function uninstall() {
        if (!$this->user->hasPermission('modify', 'extension/ebay')) {
            $this->session->data['error'] = $this->language->get('error_permission');

            $this->redirect($this->url->link('extension/ebay', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            require_once(DIR_APPLICATION . 'controller/ebay/' . $this->request->get['extension'] . '.php');

            $this->load->model('setting/extension');
            $this->load->model('setting/setting');

            $this->model_setting_extension->uninstall('ebay', $this->request->get['extension']);
            $this->model_setting_setting->deleteSetting($this->request->get['extension']);

            $class = 'ControllerEbay' . str_replace('_', '', $this->request->get['extension']);
            $class = new $class($this->registry);

            if (method_exists($class, 'uninstall')) {
                $class->uninstall();
            }

            $this->redirect($this->url->link('extension/ebay', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function manage() {
        $this->load->model('setting/setting');

        $this->data = array_merge($this->data, $this->load->language('extension/openbay'));

        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->model_setting_setting->editSetting('openbaymanager', $this->request->post);

            $this->session->data['success'] = $this->language->get('lang_text_success');

            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token']);
        }

        /**
         * OpenBay updates
         */
        if (isset($this->request->post['openbay_version'])) {
            $this->data['openbay_version'] = $this->request->post['openbay_version'];
        } else {
            $settings = $this->model_setting_setting->getSetting('openbaymanager');

            if (isset($settings['openbay_version'])) {
                $this->data['openbay_version'] = $settings['openbay_version'];
            } else {
                $this->load->model('openbay/version');
                $settings['openbay_version'] = $this->model_openbay_version->getVersion();
                $this->data['openbay_version'] = $this->model_openbay_version->getVersion();
                $this->model_setting_setting->editSetting('openbaymanager', $settings);
            }
        }

        if (isset($this->request->post['openbay_ftp_username'])) {
            $this->data['openbay_ftp_username'] = $this->request->post['openbay_ftp_username'];
        } else {
            $this->data['openbay_ftp_username'] = $this->config->get('openbay_ftp_username');
        }

        if (isset($this->request->post['openbay_ftp_pw'])) {
            $this->data['openbay_ftp_pw'] = $this->request->post['openbay_ftp_pw'];
        } else {
            $this->data['openbay_ftp_pw'] = $this->config->get('openbay_ftp_pw');
        }

        if (isset($this->request->post['openbay_ftp_rootpath'])) {
            $this->data['openbay_ftp_rootpath'] = $this->request->post['openbay_ftp_rootpath'];
        } else {
            $this->data['openbay_ftp_rootpath'] = $this->config->get('openbay_ftp_rootpath');
        }

        if (isset($this->request->post['openbay_ftp_pasv'])) {
            $this->data['openbay_ftp_pasv'] = $this->request->post['openbay_ftp_pasv'];
        } else {
            $this->data['openbay_ftp_pasv'] = $this->config->get('openbay_ftp_pasv');
        }

        if (isset($this->request->post['openbay_ftp_beta'])) {
            $this->data['openbay_ftp_beta'] = $this->request->post['openbay_ftp_beta'];
        } else {
            $this->data['openbay_ftp_beta'] = $this->config->get('openbay_ftp_beta');
        }

        if (isset($this->request->post['openbay_ftp_server'])) {
            $this->data['openbay_ftp_server'] = $this->request->post['openbay_ftp_server'];
        } else {
            $this->data['openbay_ftp_server'] = $this->config->get('openbay_ftp_server');

            if (empty($this->data['openbay_ftp_server'])) {
                $this->data['openbay_ftp_server'] = $_SERVER["SERVER_ADDR"];
            }
        }

        if (isset($this->request->post['openbay_admin_directory'])) {
            $this->data['openbay_admin_directory'] = $this->request->post['openbay_admin_directory'];
        } else {
            if (!$this->config->get('openbay_admin_directory')) {
                $this->data['openbay_admin_directory'] = 'admin';
            } else {
                $this->data['openbay_admin_directory'] = $this->config->get('openbay_admin_directory');
            }
        }

        if (isset($this->request->post['openbay_disable_homemessage'])) {
            $this->data['openbay_disable_homemessage'] = $this->request->post['openbay_disable_homemessage'];
        } else {
            $this->data['openbay_disable_homemessage'] = $this->config->get('openbay_disable_homemessage');
        }

        if (isset($this->request->post['openbay_language'])) {
            $this->data['openbay_language'] = $this->request->post['openbay_language'];
        } else {
            $this->data['openbay_language'] = $this->config->get('openbay_language');
        }

        $this->data['languages'] = array(
            'en_GB' => 'English',
            'de_DE' => 'German',
            'es_ES' => 'Spanish',
            'fr_FR' => 'French',
            'it_IT' => 'Italian',
            'nl_NL' => 'Dutch',
            'zh_HK' => 'Simplified Chinese'
        );

        /**
         * Updating language
         */
        $this->data['txt_obp_version'] = $this->config->get('openbay_version');

        $this->document->setTitle($this->language->get('lang_text_manager'));

        $this->data['action'] = HTTPS_SERVER . 'index.php?route=extension/ebay/manage&token=' . $this->session->data['token'];
        $this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'];

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
            'text' => 'OpenBay Pro',
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=extension/ebay/manage&token=' . $this->session->data['token'],
            'text' => 'Manage',
            'separator' => ' :: '
        );


        $this->template = 'extension/openbay_manage.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    public function ftpTestConnection() {
        $this->load->model('openbay/openbay');

        $data = $this->model_openbay_openbay->ftpTestConnection();

        $this->response->setOutput(json_encode($data));
    }

    public function ftpUpdateModule() {
        $this->load->model('openbay/openbay');

        $data = $this->model_openbay_openbay->ftpUpdateModule();

        return $this->response->setOutput(json_encode($data));
    }

    public function getNotifications() {
        sleep(1); // give the data some "feel" that its not in our system
        $this->load->model('openbay/openbay');
        $this->response->setOutput(json_encode($this->model_openbay_openbay->getNotifications()));
    }

    public function getVersion() {
        sleep(1); // give the data some "feel" that its not in our system
        $this->load->model('openbay/openbay');
        $this->response->setOutput(json_encode($this->model_openbay_openbay->getVersion()));
    }

    public function runPatch() {
        $this->load->model('ebay/patch');
        $this->load->model('amazon/patch');
        $this->load->model('amazonus/patch');
        $this->model_ebay_patch->runPatch();
        $this->model_amazon_patch->runPatch();
        $this->model_amazonus_patch->runPatch();
        sleep(1);
        return $this->response->setOutput(json_encode(array('msg' => 'ok')));
    }

    public function faqGet(){
        $this->load->model('openbay/openbay');
        $this->load->language('extension/openbay');

        $data = $this->model_openbay_openbay->faqGet($this->request->get['qry_route']);
        $data['faqbtn'] = $this->language->get('faqbtn');
        $this->response->setOutput(json_encode($data));
    }

    public function faqDismiss(){
        $this->load->model('openbay/openbay');
        $this->response->setOutput(json_encode($this->model_openbay_openbay->faqDismiss($this->request->get['qry_route'])));
    }

    public function faqClear(){
        $this->load->model('openbay/openbay');
        $this->model_openbay_openbay->faqClear();
        sleep(1);
        $this->response->setOutput(json_encode(array('error' => false)));
    }

    public function ajaxOrderInfo(){
        $this->data = array_merge($this->data, $this->load->language('extension/openbay'));

        if ($this->config->get('openbay_status') == 1) {
            if($this->ebay->isEbayOrder($this->request->get['order_id']) !== false){
                //if status is shipped
                if($this->config->get('EBAY_DEF_SHIPPED_ID') == $this->request->get['status_id']){
                    $this->data['carriers']     = $this->ebay->getCarriers();
                    $this->data['order_info']   = $this->ebay->getOrder($this->request->get['order_id']);
                    $this->template             = 'ebay/ajax_shippinginfo.tpl';
                    $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
                }
            }
        }

        if ($this->config->get('play_status') == 1) {
            if($this->play->isPlayOrder($this->request->get['order_id']) !== false){
                $this->data['order_info']   = $this->play->getPlayOrder($this->request->get['order_id']);

                //if status is shipped
                if($this->config->get('obp_play_shipped_id') == $this->request->get['status_id']){
                    $this->data['carriers']     = $this->play->getCarriers();
                    if(!empty($this->data['order_info'])){
                        $this->template = 'play/ajax_shippinginfo.tpl';
                        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
                    }
                }
                //if status is refunded
                if($this->config->get('obp_play_refunded_id') == $this->request->get['status_id']){
                    $this->data['refund_reason'] = $this->play->getRefundReason();
                    if(!empty($this->data['order_info'])){
                        $this->template = 'play/ajax_refundinfo.tpl';
                        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
                    }
                }
            }
        }

        if($this->config->get('amazon_status') == 1) {

            $this->data['order_info'] = $this->amazon->getOrder($this->request->get['order_id']);

            //if is amazon order
            if($this->data['order_info']){
                //if status is shipped
                if($this->request->get['status_id'] == $this->config->get('openbay_amazon_order_status_shipped')){
                    $this->data['couriers'] = $this->amazon->getCarriers();
                    $this->template = 'amazon/ajax_shippinginfo.tpl';
                    $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
                }
            }
        }
        
        if($this->config->get('amazonus_status') == 1) {

            $this->data['order_info'] = $this->amazonus->getOrder($this->request->get['order_id']);

            //if is amazonus order
            if($this->data['order_info']){
                //if status is shipped
                if($this->request->get['status_id'] == $this->config->get('openbay_amazonus_order_status_shipped')){
                    $this->data['couriers'] = $this->amazonus->getCarriers();
                    $this->template = 'amazonus/ajax_shippinginfo.tpl';
                    $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
                }
            }
        }

    }

    public function ajaxAddOrderInfo(){
        //ebay
        if ($this->config->get('openbay_status') == 1) {
            if($this->ebay->isEbayOrder($this->request->get['order_id']) !== false){
                if($this->config->get('EBAY_DEF_SHIPPED_ID') == $this->request->get['status_id']){
                    $this->ebay->orderStatusListen($this->request->get['order_id'], $this->request->get['status_id'], array('tracking_no' => $this->request->post['tracking_no'], 'carrier_id' => $this->request->post['carrier_id']));
                }else{
                    $this->ebay->orderStatusListen($this->request->get['order_id'], $this->request->get['status_id']);
                }
            }
        }

        //play.com
        if ($this->config->get('play_status') == 1) {
            if($this->play->isPlayOrder($this->request->get['order_id']) !== false){
                $this->data['order_info'] = $this->play->getPlayOrder($this->request->get['order_id']);

                //if status is shipped
                if($this->config->get('obp_play_shipped_id') == $this->request->get['status_id']){
                    if(!empty($this->request->post['play_courier']) && !empty($this->request->post['play_tracking_no'])){
                        $this->load->model('play/play');
                        $this->model_play_play->updatePlayOrderTracking($this->request->get['order_id'], $this->request->post['play_courier'], $this->request->post['play_tracking_no']);
                        $this->play->orderStatusModified($this->request->get['order_id'], $this->request->get['old_status_id']);
                    }
                }
                //if status is refunded
                if($this->config->get('obp_play_refunded_id') == $this->request->get['status_id']){
                    if(!empty($this->request->post['play_refund_message']) && !empty($this->request->post['play_refund_reason'])){
                        $this->load->model('play/play');
                        $this->model_play_play->updatePlayOrderRefund($this->request->get['order_id'], $this->request->post['play_refund_message'], $this->request->post['play_refund_reason']);
                        $this->play->orderStatusModified($this->request->get['order_id'], $this->request->get['old_status_id']);
                    }
                }
            }
        }

        //Amazon EU
        if ($this->config->get('amazon_status') == 1) {
            $amazonOrder = $this->amazon->getOrder($this->request->get['order_id']);
            if($amazonOrder){
                if($this->config->get('openbay_amazon_order_status_shipped') == $this->request->get['status_id']){
                    if(!empty($this->request->post['courier_other'])) {
                        $this->amazon->updateOrder($this->request->get['order_id'], 'shipped', $this->request->post['courier_other'], false, $this->request->post['tracking_no']);
                    } else {
                        $this->amazon->updateOrder($this->request->get['order_id'], 'shipped', $this->request->post['courier_id'], true, $this->request->post['tracking_no']);
                    }
                }
                if($this->config->get('openbay_amazon_order_status_canceled') == $this->request->get['status_id']){
                    $this->amazon->updateOrder($this->request->get['order_id'], 'canceled');
                }
            }
        }
        
        //Amazon US
        if ($this->config->get('amazonus_status') == 1) {
            $amazonusOrder = $this->amazonus->getOrder($this->request->get['order_id']);
            if($amazonusOrder){
                if($this->config->get('openbay_amazonus_order_status_shipped') == $this->request->get['status_id']){
                    if(!empty($this->request->post['courier_other'])) {
                        $this->amazonus->updateOrder($this->request->get['order_id'], 'shipped', $this->request->post['courier_other'], false, $this->request->post['tracking_no']);
                    } else {
                        $this->amazonus->updateOrder($this->request->get['order_id'], 'shipped', $this->request->post['courier_id'], true, $this->request->post['tracking_no']);
                    }
                }
                if($this->config->get('openbay_amazonus_order_status_canceled') == $this->request->get['status_id']){
                    $this->amazonus->updateOrder($this->request->get['order_id'], 'canceled');
                }
            }
        }

    }

    public function orderList(){
        $this->load->language('sale/order');
        $this->load->model('openbay/order');

        $this->data = array_merge($this->data, $this->load->language('extension/openbay_order'));

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
            'text' => 'OpenBay Pro',
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=extension/ebay/manage&token=' . $this->session->data['token'],
            'text' => $this->data['lang_title_order_update'],
            'separator' => ' :: '
        );

        $this->data['orders'] = array();

        $data = array(
            'filter_order_id'        => $filter_order_id,
            'filter_customer'	     => $filter_customer,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_date_added'      => $filter_date_added,
            'sort'                   => $sort,
            'order'                  => $order,
            'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit'                  => $this->config->get('config_admin_limit')
        );

        $order_total = $this->model_openbay_order->getTotalOrders($data);
        $results = $this->model_openbay_order->getOrders($data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_view'),
                'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
            );

            if (strtotime($result['date_added']) > strtotime('-' . (int)$this->config->get('config_order_edit') . ' day')) {
                $action[] = array(
                    'text' => $this->language->get('text_edit'),
                    'href' => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
                );
            }

            $this->data['orders'][] = array(
                'order_id'      => $result['order_id'],
                'customer'      => $result['customer'],
                'status'        => $result['status'],
                'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
                'action'        => $action,
                'channel'       => $this->model_openbay_order->findOrderChannel($result['order_id'])
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_missing'] = $this->language->get('text_missing');
        $this->data['column_order_id'] = $this->language->get('column_order_id');
        $this->data['column_customer'] = $this->language->get('column_customer');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_date_added'] = $this->language->get('column_date_added');
        $this->data['column_action'] = $this->language->get('column_action');
        $this->data['button_filter'] = $this->language->get('button_filter');

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->session->data['error'])) {
            $this->data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sort_order'] = $this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $this->data['sort_customer'] = $this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
        $this->data['sort_status'] = $this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $this->data['sort_date_added'] = $this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
        $this->data['link_update'] = $this->url->link('extension/ebay/orderListUpdate', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['filter_order_id'] = $filter_order_id;
        $this->data['filter_customer'] = $filter_customer;
        $this->data['filter_order_status_id'] = $filter_order_status_id;
        $this->data['filter_date_added'] = $filter_date_added;

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'extension/openbay_orderlist.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function orderListUpdate(){

        $this->data = array_merge($this->data, $this->load->language('extension/openbay_order'));

        if(!isset($this->request->post['selected']) || empty($this->request->post['selected'])){
            $this->session->data['error'] = $this->data['lang_no_orders'];
            $this->redirect($this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'], 'SSL'));
        }else{
            $this->load->model('openbay/order');
            $this->load->language('sale/order');

            $this->data['column_order_id'] = $this->language->get('column_order_id');
            $this->data['column_customer'] = $this->language->get('column_customer');
            $this->data['column_status'] = $this->language->get('column_status');
            $this->data['column_date_added'] = $this->language->get('column_date_added');
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['link_cancel'] = $this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['link_complete'] = $this->url->link('extension/ebay/orderListComplete', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['market_options'] = array();

            if ($this->config->get('openbay_status') == 1) {
                $this->data['market_options']['ebay']['carriers'] = $this->ebay->getCarriers();
            }

            if ($this->config->get('amazon_status') == 1) {
                $this->data['market_options']['amazon']['carriers'] = $this->amazon->getCarriers();
            }
            
            if ($this->config->get('amazonus_status') == 1) {
                $this->data['market_options']['amazonus']['carriers'] = $this->amazonus->getCarriers();
            }

            if ($this->config->get('play_status') == 1) {
                $this->data['market_options']['play']['carriers'] = $this->play->getCarriers();
            }


            $this->load->model('localisation/order_status');
            $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
            $this->data['status_mapped'] = array();

            foreach($this->data['order_statuses'] as $status){
                $this->data['status_mapped'][$status['order_status_id']] = $status['name'];
            }

            $orders = array();

            foreach($this->request->post['selected'] as $order_id){
                $order = $this->model_openbay_order->getOrder($order_id);

                if($order['order_status_id'] != $this->request->post['change_order_status_id']){
                    $order['channel'] = $this->model_openbay_order->findOrderChannel($order_id);
                    $orders[] = $order;
                }
            }

            if(empty($orders)){
                $this->session->data['error'] = $this->data['lang_no_orders'];
                $this->redirect($this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'], 'SSL'));
            }else{
                $this->data['orders'] = $orders;
            }

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'href' => HTTPS_SERVER . 'index.php?route=extension/ebay&token=' . $this->session->data['token'],
                'text' => 'OpenBay Pro',
                'separator' => ' :: '
            );

            $this->data['breadcrumbs'][] = array(
                'href' => HTTPS_SERVER . 'index.php?route=extension/ebay/manage&token=' . $this->session->data['token'],
                'text' => $this->data['lang_title_order_update'],
                'separator' => ' :: '
            );

            $this->template = 'extension/openbay_orderlist_confirm.tpl';
            $this->children = array(
                'common/header',
                'common/footer'
            );

            $this->response->setOutput($this->render());
        }
    }

    public function orderListComplete(){

        $this->load->model('sale/order');
        $this->load->model('play/play');
        $this->load->model('localisation/order_status');

        $this->data = array_merge($this->data, $this->load->language('extension/openbay_order'));

        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
        $status_mapped = array();

        foreach($order_statuses as $status){
            $status_mapped[$status['order_status_id']] = $status['name'];
        }

        $i = 0;
        foreach($this->request->post['order_id'] as $order_id){
            //ebay
            if ($this->config->get('openbay_status') == 1 && $this->request->post['channel'][$order_id] == 'eBay') {
                if($this->config->get('EBAY_DEF_SHIPPED_ID') == $this->request->post['order_status_id']){
                    $this->ebay->orderStatusListen($order_id, $this->request->post['order_status_id'], array('tracking_no' => $this->request->post['tracking'][$order_id], 'carrier_id' => $this->request->post['carrier'][$order_id]));
                }else{
                    $this->ebay->orderStatusListen($this->request->get['order_id'], $this->request->get['status_id']);
                }
            }

            //Amazon EU
            if ($this->config->get('amazon_status') == 1 && $this->request->post['channel'][$order_id] == 'Amazon') {
                if($this->config->get('openbay_amazon_order_status_shipped') == $this->request->post['order_status_id']){
                    if(isset($this->request->post['carrier_other'][$order_id]) && !empty($this->request->post['carrier_other'][$order_id])) {
                        $this->amazon->updateOrder($order_id, 'shipped', $this->request->post['carrier_other'][$order_id], false, $this->request->post['tracking'][$order_id]);
                    } else {
                        $this->amazon->updateOrder($order_id, 'shipped', $this->request->post['carrier'][$order_id], true, $this->request->post['tracking'][$order_id]);
                    }
                }
                if($this->config->get('openbay_amazon_order_status_canceled') == $this->request->post['order_status_id']){
                    $this->amazon->updateOrder($order_id, 'canceled');
                }
            }

            //play.com
            if ($this->config->get('play_status') == 1 && $this->request->post['channel'][$order_id] == 'Play') {
                //if status is shipped
                if($this->config->get('obp_play_shipped_id') == $this->request->post['order_status_id']){
                    if(!empty($this->request->post['play_courier']) && !empty($this->request->post['play_tracking_no'])){
                        $this->model_play_play->updatePlayOrderTracking($order_id, $this->request->post['carrier'][$order_id], $this->request->post['tracking'][$order_id]);
                        $this->play->orderStatusModified($order_id, $this->request->post['old_status'][$order_id]);
                    }
                }
                //if status is refunded
                if($this->config->get('obp_play_refunded_id') == $this->request->post['order_status_id']){
                    if(!empty($this->request->post['refund_message'][$order_id]) && !empty($this->request->post['refund_reason'][$order_id])){
                        $this->model_play_play->updatePlayOrderRefund($order_id, $this->request->post['refund_message'][$order_id], $this->request->post['refund_reason'][$order_id]);
                        $this->play->orderStatusModified($order_id, $this->request->post['old_status'][$order_id]);
                    }
                }
            }

            $data = array(
                'notify' => $this->request->post['notify'][$order_id],
                'order_status_id' => $this->request->post['order_status_id'],
                'comment' => $this->request->post['comments'][$order_id],
            );

            $this->model_sale_order->addOrderHistory($order_id, $data);
            $i++;
        }

        $this->session->data['success'] = $i.' '. $this->data['lang_confirmed'] .' '.$status_mapped[$this->request->post['order_status_id']];

        $this->redirect($this->url->link('extension/ebay/orderList', 'token=' . $this->session->data['token'], 'SSL'));
    }
}