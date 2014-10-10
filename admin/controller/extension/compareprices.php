<?php
class ControllerExtensionCompareprices extends Controller {
	public function index() {
		$this->load->language('extension/compareprices');
		 
		$this->document->setTitle($this->language->get('heading_title')); 

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/compareprices', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
			
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_address_xml'] = $this->language->get('column_address_xml');
		$this->data['column_data_export'] = $this->language->get('column_data_export');
		$this->data['column_action'] = $this->language->get('column_action');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
		
			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}
		
		$this->data['compare'] = array();
				
		$files = glob(DIR_CATALOG . 'controller/integration_compare/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$name = basename($file, '.php');
	
				$action = array();
				
				if (!$this->config->get($name . '_status')) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => $this->url->link('extension/compareprices/install', 'token=' . $this->session->data['token'] . '&extension=' . $name, 'SSL')
					);
				} else {		
					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => $this->url->link('extension/compareprices/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $name, 'SSL')
					);
				}

				$this->data['compare'][] = array(
					'name'        => !file_exists(DIR_IMAGE . 'compare/' . $name . '.jpg') ? $name : '<img src="' . HTTP_CATALOG . 'image/compare/' . $name . '.jpg" alt="' . $name . '" title="' . $name . '" />',
					'status'      => $this->config->get($name . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'address_xml' => $this->config->get($name . '_status') ? str_replace('admin/', '', $this->url->link('integration_compare/' . $name, 'password=' . $this->config->get($name . '_private_key'), 'SSL')) : '',
					'data_export' => $this->config->get($name . '_data_export'),
					'action'      => $action
				);
			}
		}

		$this->template = 'extension/compareprices.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function install() {
		if (!$this->user->hasPermission('modify', 'extension/compareprices')) {
			$this->session->data['error'] = $this->language->get('error_permission');
			
			$this->redirect($this->url->link('extension/compareprices', 'token=' . $this->session->data['token'], 'SSL'));
		} else {				
			$this->load->model('setting/setting');

			$setings = array($this->request->get['extension'] . '_status' => '1', $this->request->get['extension'] . '_data_export' => '', $this->request->get['extension'] . '_private_key' => $this->randChar());
			$this->model_setting_setting->editSetting('compareprices_' . $this->request->get['extension'], $setings);

			$this->load->language('extension/compareprices');
			$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->request->get['extension']);
			$this->redirect($this->url->link('extension/compareprices', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/compareprices')) {
			$this->session->data['error'] = $this->language->get('error_permission'); 
			
			$this->redirect($this->url->link('extension/compareprices', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->load->model('setting/setting');

			$this->model_setting_setting->deleteSetting('compareprices_' . $this->request->get['extension']);
			$this->redirect($this->url->link('extension/compareprices', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	private function randChar() {
		$keyset  = "abcdefghijklmABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$randkey = "";
		$length = rand(6, 12);

		for ($i = 0; $i < $length; $i++) {
			$randkey .= substr($keyset, rand(0, strlen($keyset) - 1), 1);
		}

		return $randkey;
	}
}
?>