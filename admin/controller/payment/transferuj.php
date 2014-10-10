<?php

class ControllerPaymentTransferuj extends Controller
{
	private $error = array(); 

	public function index()
	{
		$this->load->language('payment/transferuj');
		//$this->document->title = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate())
		{
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('transferuj', $this->request->post);				
			$this->session->data['success'] = $this->language->get('text_success');
		    $this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_transferuj_status']	= $this->language->get('entry_transferuj_status');
		$this->data['entry_transferuj_status_yes'] = $this->language->get('entry_transferuj_status_yes');
		$this->data['entry_transferuj_status_no'] = $this->language->get('entry_transferuj_status_no');		
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_transferuj_ip'] = $this->language->get('entry_transferuj_ip');

		$this->data['entry_settings_seller'] = $this->language->get('entry_settings_seller');				
		$this->data['entry_transferuj_seller_id'] = $this->language->get('entry_transferuj_seller_id');
		$this->data['entry_transferuj_conf_code'] = $this->language->get('entry_transferuj_conf_code');
		$this->data['entry_transferuj_conf_code_hint'] = $this->language->get('entry_transferuj_conf_code_hint');
		
		$this->data['entry_settings_orders'] = $this->language->get('entry_settings_orders');
		$this->data['entry_transferuj_currency'] = $this->language->get('entry_transferuj_currency');
		$this->data['entry_transferuj_order_status_error'] = $this->language->get('entry_transferuj_order_status_error');
		$this->data['entry_transferuj_order_status_completed'] = $this->language->get('entry_transferuj_order_status_completed');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = (isset($this->error['warning']) ? $this->error['warning'] : '');
		$this->data['error_merchant'] = (isset($this->error['merchant']) ? $this->error['merchant'] : '');
		$this->data['error_password'] = (isset($this->error['password']) ? $this->error['password'] : '');

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
	    );

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/transferuj&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

	    $this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/transferuj&token=' . $this->session->data['token'];
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		

		$this->data['transferuj_status'] = (isset($this->request->post['transferuj_status']) ? $this->request->post['transferuj_status'] : $this->config->get('transferuj_status'));
		$this->data['transferuj_sort_order'] = (isset($this->request->post['transferuj_sort_order']) ? $this->request->post['transferuj_sort_order'] : $this->config->get('transferuj_sort_order'));
		$this->data['transferuj_ip'] = (isset($this->request->post['transferuj_ip']) ? $this->request->post['transferuj_ip'] : $this->config->get('transferuj_ip'));
		$this->data['transferuj_seller_id'] = (isset($this->request->post['transferuj_seller_id']) ? $this->request->post['transferuj_seller_id'] : $this->config->get('transferuj_seller_id'));
		$this->data['transferuj_conf_code'] = (isset($this->request->post['transferuj_conf_code']) ? $this->request->post['transferuj_conf_code'] : $this->config->get('transferuj_conf_code'));
		
		$this->load->model('localisation/currency');
		
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code'];  } 
		$currency_info = $this->model_localisation_currency->getCurrency('0');     
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code'];  } 
		$currency_info = $this->model_localisation_currency->getCurrency('1');
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code'];  } 
		$currency_info = $this->model_localisation_currency->getCurrency('2');
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code'];  } 
		$currency_info = $this->model_localisation_currency->getCurrency('3');
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code'];  } 
		$currency_info = $this->model_localisation_currency->getCurrency('4');
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code']; } 
		$currency_info = $this->model_localisation_currency->getCurrency('5');
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code']; }
		$currency_info = $this->model_localisation_currency->getCurrency('6');
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code']; }
		$currency_info = $this->model_localisation_currency->getCurrency('7');
		if (!empty($currency_info)) { $this->data['curr'][] = $currency_info['code']; }
		
		$this->data['transferuj_currency'] = (isset($this->request->post['transferuj_currency']) ? $this->request->post['transferuj_currency'] : $this->config->get('transferuj_currency'));
		
		$this->load->model('localisation/order_status');		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->data['transferuj_order_status_error'] = (isset($this->request->post['transferuj_order_status_error']) ? $this->request->post['transferuj_order_status_error'] : $this->config->get('transferuj_order_status_error'));
		$this->data['transferuj_order_status_completed'] = (isset($this->request->post['transferuj_order_status_completed']) ? $this->request->post['transferuj_order_status_completed'] : $this->config->get('transferuj_order_status_completed'));

		$this->template = 'payment/transferuj.tpl';
		$this->children = array(
			'common/header',	
			'common/footer');
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'payment/transferuj')) $this->error['warning'] = $this->language->get('error_permission');		
		if (!$this->request->post['transferuj_seller_id']) $this->error['merchant'] = $this->language->get('error_merchant');
		return (!$this->error ? true : false);
	}
}

?>