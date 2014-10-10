<?php
/**
 * Controller for module German
 *
 * PHP VERSION 5.3
 *
 * @category File
 * @package  Admin
 * @author   Andreas Tangemann   <a.tangemann@web.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://www.opencart.com
 */
/**
 * ControllerModuleGerman
 *
 * Controller for module German
 *
 * @category Controller
 * @package  Admin
 * @author   Andreas Tangemann   <a.tangemann@web.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://www.opencart.com
 */
class ControllerModuleGerman extends Controller
{
	private $_error = array(); 
	
    /**
     * Creates the index page to control this module
     *
     * @author Andreas Tangemann <a.tangemann@web.de>
     *
     * @return void
     *
     */
	public function index() 
	{
		$this->load->language('module/german');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->_error['warning'])) {
			$this->data['error_warning'] = $this->_error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link(
                'common/home', 'token=' . $this->session->data['token'], 'SSL'
            ),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link(
				'extension/module', 'token=' . $this->session->data['token'], 'SSL'
			),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link(
				'module/german', 'token=' . $this->session->data['token'], 'SSL'
			),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link(
			'module/german', 
			'token=' . $this->session->data['token'], 
			'SSL'
		);
		
		$this->data['cancel'] = $this->url->link(
			'extension/module', 'token=' . $this->session->data['token'], 'SSL'
		);

		if (isset($this->request->post['german_module'])) {
			$modules = explode(',', $this->request->post['german_module']);
		} elseif ($this->config->get('german_module') != '') { 
			$modules = explode(',', $this->config->get('german_module'));
		} else {
			$modules = array();
		}		
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->data['modules'] = $modules;
		
		if (isset($this->request->post['german_module'])) {
			$this->data['german_module'] = $this->request->post['german_module'];
		} else {
			$this->data['german_module'] = $this->config->get('german_module');
		}
		
		$this->template = 'module/german.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
    /**
     * Installs this module
     * 1. Create language
     * 2. Insert translations to database
     *
     * @author Andreas Tangemann <a.tangemann@web.de>
     * @access public
     *
     * @return void
     */
	public function install() 
	{
		// Sprache
		$this->load->model('localisation/language');
		$lang = new ModelLocalisationLanguage($this->registry);
		$languages = $lang->getLanguages();
		if (!isset($languages["de"])) {
			// Sprache de existiert noch nicht
			$data["name"]		= 'Deutsch';
			$data["code"]		= 'de';
			$data["locale"]		= 'de_DE.UTF-8,de_DE,de-de,german';
			$data["directory"]	= 'de_DE';
			$data["filename"]	= 'de_DE';
			$data["image"]		= 'de.png';
			$data["sort_order"]	= 1;
			$data["status"]		= 1;
			$lang->addLanguage($data);
			$languages = $lang->getLanguages();
			if (isset($languages["de"])) {
				$language_id = $languages["de"]["language_id"];
				$this->db->query("UPDATE " . DB_PREFIX . "information_description SET title = 'Datenschutzerklärung', description= '&lt;p&gt;Datenschutzerklärung&lt;/p&gt;' WHERE information_id=3 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "information_description SET title = 'Impressum', description='&lt;p&gt;Impressum&lt;/p&gt;' WHERE information_id=4 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "information_description SET title = 'Allgemeine Geschäftsbedingungen', description='&lt;p&gt;Allgemeine Geschäftsbedingungen&lt;/p&gt;' WHERE information_id=5 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "information_description SET title = 'Lieferbedingungen', description='&lt;p&gt;Lieferbedingungen&lt;/p&gt;' WHERE information_id=6 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "length_class_description SET title = 'Zentimeter',unit = 'cm' WHERE length_class_id=1 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "length_class_description SET title = 'Millimeter',unit = 'mm' WHERE length_class_id=2 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "length_class_description SET title = 'Inch',unit = 'in' WHERE length_class_id=3 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Warten' WHERE order_status_id=1 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'In Bearbeitung' WHERE order_status_id=2 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Geliefert' WHERE order_status_id=3 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Fertig' WHERE order_status_id=5 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Storniert' WHERE order_status_id=7 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Abgelehnt' WHERE order_status_id=8 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Stornierte Rückgabe' WHERE order_status_id=9 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Fehler' WHERE order_status_id=10 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Erstattet' WHERE order_status_id=11 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Gutgeschrieben' WHERE order_status_id=12 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Rücklastschrift' WHERE order_status_id=13 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Abgelaufen' WHERE order_status_id=14 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Bearbeitet' WHERE order_status_id=15 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "order_status SET name = 'Ungültig' WHERE order_status_id=16 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "stock_status SET name = 'Ausverkauft' WHERE stock_status_id=5 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "stock_status SET name = '2 -3 Tage' WHERE stock_status_id=6 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "stock_status SET name = 'Vorrätig' WHERE stock_status_id=7 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "stock_status SET name = 'Vorbestellung' WHERE stock_status_id=8 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "weight_class_description SET title= 'Kilogramm', unit='kg' WHERE weight_class_id=1 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "weight_class_description SET title= 'Gramm', unit='g' WHERE weight_class_id=2 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "weight_class_description SET title= 'Pfund', unit='lb' WHERE weight_class_id=5 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "weight_class_description SET title= 'Unze', unit='oz' WHERE weight_class_id=6 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "return_status SET name= 'Warten' WHERE return_status_id=1 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_status SET name= 'Warte auf Rücklieferung' WHERE return_status_id=2 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_status SET name= 'Vollständig' WHERE return_status_id=3 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "return_action SET name= 'Erstattet' WHERE return_action_id=1 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_action SET name= 'Gutschrift erstellt' WHERE return_action_id=2 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_action SET name= 'Ersatz versendet' WHERE return_action_id=3 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "return_reason SET name= 'defekte Lieferung' WHERE return_reason_id=1 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_reason SET name= 'falscher Artikel geliefert' WHERE return_reason_id=2 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_reason SET name= 'falscher Artikel bestellt' WHERE return_reason_id=3 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_reason SET name= 'fehlerhaft, bitte Details erfassen' WHERE return_reason_id=4 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "return_reason SET name= 'sonstiges, bitte Details erfassen' WHERE return_reason_id=5 AND language_id =" . $language_id);
				
				$this->db->query("UPDATE " . DB_PREFIX . "voucher_theme_description SET name= 'Weihnachten' WHERE voucher_theme_id=6 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "voucher_theme_description SET name= 'Geburtstag' WHERE voucher_theme_id=7 AND language_id =" . $language_id);
				$this->db->query("UPDATE " . DB_PREFIX . "voucher_theme_description SET name= 'Allgemein' WHERE voucher_theme_id=8 AND language_id =" . $language_id);
			}
		}
	}

    /**
     * Uninstalls this module
     *
     * @author Andreas Tangemann <a.tangemann@web.de>
     *
     * @return void
     *
     */
	public function uninstall() 
	{
		$this->load->model('localisation/language');
		$this->load->model('setting/setting');
		$lang = new ModelLocalisationLanguage($this->registry);
		$languages = $lang->getLanguages();
		if (isset($languages["de"])) {
			$language_id = $languages["de"]["language_id"];
			$lang->deleteLanguage($language_id);
		}

		// Change admin language and store language, if it is German
		$set = new ModelSettingSetting($this->registry);
		$config_values = $set->getSetting('config', 0);
		//TODO: Ermittlung store id
		$values_changed = false;
		if (isset($config_values["config_admin_language"]) 
			and $config_values["config_admin_language"]==='de'
		) {
			$config_values["config_admin_language"] = 'en';
			$values_changed = true;
		}
		if (isset($config_values["config_language"]) 
			and $config_values["config_language"]==='de'
		) {
			$config_values["config_language"] = 'en';
			$values_changed = true;
		}
		if ($values_changed===true) {
			$set->editSetting('config', $config_values, 0);
		}
	}
}
?>