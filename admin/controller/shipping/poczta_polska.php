<?php
// copl
class ControllerShippingPocztaPolska extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('shipping/poczta_polska');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('poczta_polska', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
						
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/poczta_polska', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/poczta_polska', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 

// maksymalne wymiary i waga przesy³ki listowej a tak¿e limit iloœci przedmiotów w kopercie
		$this->data['entry_max_size_envelope'] = $this->language->get('entry_max_size_envelope');
		if (isset($this->request->post['max_size_envelope_x'])) {
			$this->data['max_size_envelope_x'] = $this->request->post['max_size_envelope_x'];
		} elseif ($this->config->get('max_size_envelope_x')) {
			$this->data['max_size_envelope_x'] = $this->config->get('max_size_envelope_x');
		} else {
			$this->data['max_size_envelope_x'] = '15';	
		}
		if (isset($this->request->post['max_size_envelope_y'])) {
			$this->data['max_size_envelope_y'] = $this->request->post['max_size_envelope_y'];
		} elseif ($this->config->get('max_size_envelope_y')) {
			$this->data['max_size_envelope_y'] = $this->config->get('max_size_envelope_y');
		} else {
			$this->data['max_size_envelope_y'] = '7';	
		}
		if (isset($this->request->post['max_size_envelope_z'])) {
			$this->data['max_size_envelope_z'] = $this->request->post['max_size_envelope_z'];
		} elseif ($this->config->get('max_size_envelope_z')) {
			$this->data['max_size_envelope_z'] = $this->config->get('max_size_envelope_z');
		} else {
			$this->data['max_size_envelope_z'] = '20';	
		}
		if (isset($this->request->post['max_weight_envelope'])) {
			$this->data['max_weight_envelope'] = $this->request->post['max_weight_envelope'];
		} elseif ($this->config->get('max_weight_envelope')) {
			$this->data['max_weight_envelope'] = $this->config->get('max_weight_envelope');
		} else {
			$this->data['max_weight_envelope'] = '2';	
		}
		if (isset($this->request->post['max_items_envelope'])) {
			$this->data['max_items_envelope'] = $this->request->post['max_items_envelope'];
		} elseif ($this->config->get('max_items_envelope')) {
			$this->data['max_items_envelope'] = $this->config->get('max_items_envelope');
		} else {
			$this->data['max_items_envelope'] = '';	
		}
		
		// list polecony ekonomiczny
		$this->data['entry_poczta_polska_polecony_ekonom'] = $this->language->get('entry_poczta_polska_polecony_ekonom');
		if (isset($this->request->post['poczta_polska_polecony_ekonom'])) {
			$this->data['poczta_polska_polecony_ekonom'] = $this->request->post['poczta_polska_polecony_ekonom'];
		} else {
			$this->data['poczta_polska_polecony_ekonom'] = $this->config->get('poczta_polska_polecony_ekonom');
		}
		if (isset($this->request->post['poczta_polska_polecony_ekonom_rate'])) {
			$this->data['poczta_polska_polecony_ekonom_rate'] = $this->request->post['poczta_polska_polecony_ekonom_rate'];
		} elseif ($this->config->get('poczta_polska_polecony_ekonom_rate')) {
			$this->data['poczta_polska_polecony_ekonom_rate'] = $this->config->get('poczta_polska_polecony_ekonom_rate');
		} else {
			$this->data['poczta_polska_polecony_ekonom_rate'] = '.05:3.75,.1:4.20,.35:4.50,.5:5.10,1:7.50,2:9.50';	
		}
        //
		// list polecony priorytet
		$this->data['entry_poczta_polska_polecony_prio'] = $this->language->get('entry_poczta_polska_polecony_prio');
		if (isset($this->request->post['poczta_polska_polecony_prio'])) {
			$this->data['poczta_polska_polecony_prio'] = $this->request->post['poczta_polska_polecony_prio'];
		} else {
			$this->data['poczta_polska_polecony_prio'] = $this->config->get('poczta_polska_polecony_prio');
		}
		if (isset($this->request->post['poczta_polska_polecony_prio_rate'])) {
			$this->data['poczta_polska_polecony_prio_rate'] = $this->request->post['poczta_polska_polecony_prio_rate'];
		} elseif ($this->config->get('poczta_polska_polecony_ekonom_rate')) {
			$this->data['poczta_polska_polecony_prio_rate'] = $this->config->get('poczta_polska_polecony_prio_rate');
		} else {
			$this->data['poczta_polska_polecony_prio_rate'] = '.05:4.15,.1:5.20,.35:5.70,.5:6.20,1:9,2:12.10';	
		}
        //
		// paczka ekonomiczna
		$this->data['entry_poczta_polska_ekonom'] = $this->language->get('entry_poczta_polska_ekonom');
		if (isset($this->request->post['poczta_polska_ekonom'])) {
			$this->data['poczta_polska_ekonom'] = $this->request->post['poczta_polska_ekonom'];
		} else {
			$this->data['poczta_polska_ekonom'] = $this->config->get('poczta_polska_ekonom');
		}
		if (isset($this->request->post['poczta_polska_ekonom_rate'])) {
			$this->data['poczta_polska_ekonom_rate'] = $this->request->post['poczta_polska_ekonom_rate'];
		} elseif ($this->config->get('poczta_polska_ekonom_rate')) {
			$this->data['poczta_polska_ekonom_rate'] = $this->config->get('poczta_polska_ekonom_rate');
		} else {
			$this->data['poczta_polska_ekonom_rate'] = '1:9.50,2:11.00,5:13.00,10:18.00,15:21.00,20:30.00,30:36.00';	
		}
        //
		// paczka priorytetowa
	    $this->data['entry_poczta_polska_prio'] = $this->language->get('entry_poczta_polska_prio');
		if (isset($this->request->post['poczta_polska_prio'])) {
			$this->data['poczta_polska_prio'] = $this->request->post['poczta_polska_prio'];
		} else {
			$this->data['poczta_polska_prio'] = $this->config->get('poczta_polska_prio');
		}
		if (isset($this->request->post['poczta_polska_prio_rate'])) {
			$this->data['poczta_polska_prio_rate'] = $this->request->post['poczta_polska_prio_rate'];
		} elseif ($this->config->get('poczta_polska_prio_rate')) {
			$this->data['poczta_polska_prio_rate'] = $this->config->get('poczta_polska_prio_rate');
		} else {
			$this->data['poczta_polska_prio_rate'] = '1:11.00,2:13.00,5:14.50,10:20.50,15:25.00,20:35.00,30:43.00';	
		}
        //
		// paczka pobraniowa ekonomiczna
		$this->data['entry_poczta_polska_pobranie_ekonom'] = $this->language->get('entry_poczta_polska_pobranie_ekonom');	
		if (isset($this->request->post['poczta_polska_pobranie_ekonom'])) {
			$this->data['poczta_polska_pobranie_ekonom'] = $this->request->post['poczta_polska_pobranie_ekonom'];
		} else {
			$this->data['poczta_polska_pobranie_ekonom'] = $this->config->get('poczta_polska_pobranie_ekonom');
		}
		if (isset($this->request->post['poczta_polska_pobranie_ekonom_rate'])) {
			$this->data['poczta_polska_pobranie_ekonom_rate'] = $this->request->post['poczta_polska_pobranie_ekonom_rate'];
		} elseif ($this->config->get('poczta_polska_pobranie_ekonom_rate')) {
			$this->data['poczta_polska_pobranie_ekonom_rate'] = $this->config->get('poczta_polska_pobranie_ekonom_rate');
		} else {
			$this->data['poczta_polska_pobranie_ekonom_rate'] = '.5:10.50,1:12.50,2:14.00,5:16.00,10:22.50,15:27.00,20:36.00,30:42.00';	
		}
        //
		// paczka pobraniowa priorytet
		$this->data['entry_poczta_polska_pobranie_prio'] = $this->language->get('entry_poczta_polska_pobranie_prio');
		if (isset($this->request->post['poczta_polska_pobranie_prio'])) {
			$this->data['poczta_polska_pobranie_prio'] = $this->request->post['poczta_polska_pobranie_prio'];
		} else {
			$this->data['poczta_polska_pobranie_prio'] = $this->config->get('poczta_polska_pobranie_prio');
		}
		if (isset($this->request->post['poczta_polska_pobranie_prio_rate'])) {
			$this->data['poczta_polska_pobranie_prio_rate'] = $this->request->post['poczta_polska_pobranie_prio_rate'];
		} elseif ($this->config->get('poczta_polska_pobranie_prio_rate')) {
			$this->data['poczta_polska_pobranie_prio_rate'] = $this->config->get('poczta_polska_pobranie_prio_rate');
		} else {
			$this->data['poczta_polska_pobranie_prio_rate'] = '.5:13.50,1:15.50,2:17.00,5:19.00,10:26.00,15:30.00,20:40.00,30:50.00';	
		}
        //
		// koszty pakowania
		$this->data['entry_poczta_polska_packing_cost'] = $this->language->get('entry_poczta_polska_packing_cost');
		if (isset($this->request->post['poczta_polska_packing_cost'])) {
			$this->data['poczta_polska_packing_cost'] = $this->request->post['poczta_polska_packing_cost'];
		} else {
			$this->data['poczta_polska_packing_cost'] = $this->config->get('poczta_polska_packing_cost');
		}				
        //
		// od jakiej kwoty doliczamy op³ate za wartoœæ
		$this->data['entry_poczta_polska_add_value_fee'] = $this->language->get('entry_poczta_polska_add_value_fee');
		if (isset($this->request->post['poczta_polska_add_value_fee'])) {
			$this->data['poczta_polska_add_value_fee'] = $this->request->post['poczta_polska_add_value_fee'];
		} else {
			$this->data['poczta_polska_add_value_fee'] = $this->config->get('poczta_polska_add_value_fee');
		}						
        //
		// czy wyœwietlaæ ciê¿ar przesy³ki
		$this->data['entry_poczta_polska_display_weight'] = $this->language->get('entry_poczta_polska_display_weight');	
		if (isset($this->request->post['poczta_polska_display_weight'])) {
			$this->data['poczta_polska_display_weight'] = $this->request->post['poczta_polska_display_weight'];
		} else {
			$this->data['poczta_polska_display_weight'] = $this->config->get('poczta_polska_display_weight');
		}
		
		if (isset($this->request->post['poczta_polska_tax_class_id'])) {
			$this->data['poczta_polska_tax_class_id'] = $this->request->post['poczta_polska_tax_class_id'];
		} else {
			$this->data['poczta_polska_tax_class_id'] = $this->config->get('poczta_polska_tax_class_id');
		}

		if (isset($this->request->post['poczta_polska_geo_zone_id'])) {
			$this->data['poczta_polska_geo_zone_id'] = $this->request->post['poczta_polska_geo_zone_id'];
		} else {
			$this->data['poczta_polska_geo_zone_id'] = $this->config->get('poczta_polska_geo_zone_id');
		}
		
		if (isset($this->request->post['poczta_polska_status'])) {
			$this->data['poczta_polska_status'] = $this->request->post['poczta_polska_status'];
		} else {
			$this->data['poczta_polska_status'] = $this->config->get('poczta_polska_status');
		}
		
		if (isset($this->request->post['poczta_polska_sort_order'])) {
			$this->data['poczta_polska_sort_order'] = $this->request->post['poczta_polska_sort_order'];
		} else {
			$this->data['poczta_polska_sort_order'] = $this->config->get('poczta_polska_sort_order');
		}
		
		$this->load->model('localisation/tax_class');
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();	

		$this->template = 'shipping/poczta_polska.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/poczta_polska')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>