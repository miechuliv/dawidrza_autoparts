<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

        $this->load->model('catalog/manufacturer');

        $this->data['manufacturers'] = array();

        $manufacturers = $this->model_catalog_manufacturer->getManufacturers();

        foreach($manufacturers as $manufacturer)
        {
            $this->data['manufacturers'][$manufacturer['manufacturer_id']] =  $manufacturer['name'];

        }

		$this->data['heading_title'] = $this->config->get('config_title');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}


		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header',
            
		);
										
		$this->response->setOutput($this->render());
	}
}
?>