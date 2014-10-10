<?php
/**
 * @version		$Id: directebanking.php 3251 2013-05-10 19:56:43Z mic $
 * @package		Directebanking - Admin Controller
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

require_once( DIR_APPLICATION . 'controller' .DIRECTORY_SEPARATOR. 'controllerCompatibility.php' );

class ControllerPaymentDirectebanking extends controllerCompatibility
{
	private $error		= array();
	public $_version	= '2.1.3';
	public $_type		= 'payment';
    public $_name		= 'directebanking';
	public $_url		= '';
	public $_ocversion	= '';
	public $editor;		// defines the editor to use (CKEditor or TinyMCE)

	/**
	 * get and define several basic data
	 */
	protected function getBasics() {
		$this->checkOCVersion();

		$this->load->language( $this->_type .'/'. $this->_name );

		$this->data['_name']		= $this->_name;
		$this->data['_type']		= $this->_type;
		$this->data['_ocversion']	= $this->_ocversion;
		$this->data['version']		= $this->_version;
		$this->data['token']		= ( !empty( $this->session->data['token'] ) ? $this->session->data['token'] : '' );

		$this->_url					= HTTPS_SERVER . 'index.php?route=%s&token=' . $this->data['token'];
		$this->_serverShift			= time() + ( $this->config->get( $this->_name . '_server_shift' ) * 3600 );
		$this->_debug				= $this->config->get( $this->_name . '_debug' );
		$this->_images				= HTTPS_SERVER . 'view/image/osworx/';

		// vars for editor tinymce
		$this->data['baseUrl']		= HTTP_CATALOG;

		if(
			// OC15
			$this->config->get( 'config_use_ssl' )
			// OC14
			|| $this->config->get( 'config_ssl' )
		)
		{
			$this->data['baseUrl']	= str_replace( 'http://', 'https://', $this->data['baseUrl'] );
		}
		$this->data['langCode']		= $this->language->get( 'code' );

  		$this->defEditor();
  		$this->getFooter();
	}

	/**
     * default values
     * @param bool	$install	indicates if call come from an installation
     * 							to get serialized db.vars
     */
    public function getDefaultValues( $install = false ) {
    	$vars = array(
			// standard
			'target'			=> 'user', // admin
			'supportKey'		=> '',
			'status'			=> 0,
			'sort_order'		=> 1,
			// module specific
			'custId'			=> '',
			'projId'			=> '',
			'successUrlStd'		=> 1,
			'successUrl'		=> '',
			'cancelUrlStd'		=> 1,
			'cancelUrl'			=> '',
			'useHash'			=> '',
			'projectPassword'	=> '',
			'notifyPassword'	=> '',
			'hashMethod'		=> 'sha1',
			'testMode'			=> '',
			'debug'				=> '',
			'server_shift'		=> '',
			'geo_zone_id'		=> '',
			'order_status_id'	=> '',
			'pass'				=> '',
			'title_as_text'		=> 1
		);

		foreach( $vars as $k => $v ) {
			$this->getParam( $k, $v );
		}

		$this->getLangVars();

		// localized vars
		$vars = array(
			'instruction'	=> '',
			'title'			=> ''
		);
		foreach( $this->data['languages'] as $lang ) {
			foreach( $vars as $k => $v ) {
				$this->getParam( $k .'_'. $lang['language_id'], $v );
			}
		}

		if( !$install ) {
			// get serialized values
			$this->data[$this->_name . '_customer_groups'] = unserialize(
				$this->getParam(
					'customer_groups',
					serialize( array( '-1' ) ),
					true
				)
			);

            $this->data[$this->_name . '_stores'] = unserialize(
				$this->getParam(
					'stores',
					serialize( array( 0 => $this->getStoreDefaultValues() ) ),
					true
				)
			);
		}

		unset( $vars );
    }

    /**
     * set several default values (for multistores)
     * @return array
     */
    private function getStoreDefaultValues() {
        $vars = array(
            'status'            => 0,
            'projId'            => '',
            'successUrlStd'     => 1,
            'successUrl'        => '',
            'cancelUrlStd'      => 1,
            'cancelUrl'         => '',
            'projectPassword'   => '',
            'notifyPassword'	=> '',
            'protection'        => 0
        );

        return $vars;
    }

	/**
	 * main function
	 */
	public function index() {
		$this->getBasics();
		$this->getLangVars();
		$this->getDocument();

		$this->act	= $this->getRequest( 'act', null );
		$this->mode = $this->getRequest( 'mode', null );

		switch( $this->act ) {
			case 'settings':
				$this->getSettings();
				break;

			case 'saveSetting':
				$this->saveSetting();
			break;

			case 'text':
				$this->editText();
				break;

			case 'log':
				$this->showLog();
				break;

			case 'showNewProject':
				$this->showNewProject();
				break;

            case 'support':
                $this->support();
                break;

            case 'saveText':
            case 'saveSupport':
                $this->updateSetting();
                break;

			default:
				$this->cpanel();
			break;
		}
	}

    /**
     * defines the editor to use (checking if tinymce is installed - see path)
     */
    private function defEditor() {
    	if( file_exists( DIR_APPLICATION . 'view/javascript/tinymce/jscripts/tiny_mce/tiny_mce.js' ) ) {
            // OSWorX
    		$this->editor = 'tinymce';
        }elseif( file_exists( '../media/editors/tinymce/jscripts/tiny_mce/tiny_mce.js' ) ) {
            // Joomla (e.g. aceshop) - use embedded tinyMCE
            $this->editor = '';
        }elseif( file_exists( DIR_APPLICATION . 'view/javascript/ckeditor/ckeditor.js' ) ) {
            // OC native
            $this->editor = 'ckeditor';
    	}else{
    		$this->editor = '';
    	}

    	$this->data['editor'] = $this->editor;
    }

	/**
	 * - add javascript and css into document header
	 * - defines document title
	 */
	private function getDocument() {
		$styles = array(
			array( 'rel' => 'stylesheet',	'href' => 'view/stylesheet/tooltip_simple.css',	'media' => 'screen' ),
			array( 'rel' => 'stylesheet',	'href' => 'view/stylesheet/osworx_system.css',	'media' => 'screen' ),
			array( 'rel' => 'stylesheet',	'href' => 'view/stylesheet/cqi.css',			'media' => 'screen' )
		);

		$scripts = array(
			'view/javascript/osworx/system.js',
			'view/javascript/jquery/tooltip/jquery.tools_tooltips.min.js',
			'view/javascript/osworx/editor.js'
		);

        if( $this->_ocversion == '1.4' ) {
            $scripts[] = 'view/javascript/jquery/ui/ui.accordion.js';
        }

		foreach( $styles as $style ) {
			$this->document->addStyle( $style['href'] );
		}

		foreach( $scripts as $script ) {
			$this->document->addScript( $script );
		}

		$this->buildTitle( $this->language->get( 'plain_title' ) );

		// mic: either CKEditor OR TinyMCE @TODO: switch via OC.config ?
		if( $this->editor ) {
			if( $this->editor == 'tinymce' ) {
				$ed = 'tinymce/jscripts/tiny_mce/tiny_mce.js';
			}elseif( $this->editor = 'ckeditor' ) {
				$ed = 'ckeditor/ckeditor.js';
			}

			$this->document->addScript( 'view/javascript/' . $ed );
		}
	}

	/**
	 * displays the cpanel
	 */
	private function cpanel() {
	 	$this->getDefaultValues();
		$this->getBreadcrumbs();
		$this->getMessages();
		$this->getTemplate( '_cpanel', $this->_name );
		$this->getLangVars();

		$icons = array(
			array(
				'act'		=> 'settings',
				'title'		=> $this->language->get( 'text_settings' ),
				'accKey'	=> 'S',
				'img'		=> 'settings'
			),
			array(
				'act'		=> 'text',
				'title'		=> $this->language->get( 'text_text' ),
				'accKey'	=> 'T',
				'img'		=> 'text'
			),
			array(
				'act'		=> 'log',
				'title'		=> $this->language->get( 'text_log' ),
				'accKey'	=> 'L',
				'img'		=> 'log'
			),
			array(
				'act'		=> 'showNewProject',
				'title'		=> $this->language->get( 'text_new_project' ),
				'accKey'	=> 'N',
				'img'		=> 'settings_2'
			),
			array(
				'act'		=> '',
				'title'		=> $this->language->get( 'text_help' ),
				'accKey'	=> 'H',
				'img'		=> 'helpcenter',
				'link'		=> 'http://osworx.net?option=com_search&amp;searchword=' . $this->_name,
				'target'	=> '_blank'
			)
		);

        if( $this->getSupportData( false ) ) {
            $icons[] = array(
				'act'		=> 'support',
				'title'		=> $this->language->get( 'text_support' ),
				'accKey'	=> 'M',
				'img'		=> 'connect'
			);
        }

        $this->checkCurrentVersion();
		$this->getIcons( $icons );

		$this->data['links'] = array(
			'help' => array(
				'href' 	=> 'mailto:info@osworx.net?subject=Support%20OpenCart%20Module%20-%20'
							. $this->_name . '%20[v.' . $this->_version . ']',
				'title'	=> $this->language->get( 'text_help' )
			)
		);

		// get total figures
		$total = $this->getTotal();
		$this->data['cpanel_total_amount']	= $this->currency->format(
			$total['total'],
			$this->config->get( 'config_currency' )
		);
		$this->data['cpanel_total_used']	= $total['used'];
		$this->data['cpanel_total_percent']	= $total['percent'];
		$this->data['supportKey']			= $this->config->get( $this->_name . '_supportKey' );
		$this->data['extension'] = $this->_name;

		// mic: not needed, prepared only
		// $this->getStatusIcons();

		$this->buildResponse();
	 }

	/**
	 * build breadcrumbs
	 * @param array	additional breadcrumbs (optional) as text
	 */
	private function getBreadcrumbs( $add = null ) {
		$breadcrumbs = array(
			array(
	       		'href'      => $this->buildUrl( 'common/home' ),
	       		'text'      => $this->language->get( 'text_home' ),
	      		'separator' => false
		  	),
	  		array(
	       		'href'      => $this->buildUrl( 'extension/' . $this->_type ),
	       		'text'      => $this->language->get( 'text_payment' ),
	      		'separator' => ' :: '
	   		),
	   		array(
		   		'href'      => $this->buildUrl( $this->_type .'/'. $this->_name ),
	       		'text'      => $this->language->get( 'plain_title' ),
	      		'separator' => ' :: '
     		)
 		);

 		if( $add ) {
			foreach( $add as $ad ) {
	   			$breadcrumbs[] =
				   	array(
			       		'href'      => 'javascript:void(0);',
			       		'text'      => $ad,
			      		'separator' => ' :: '
			   		);
 			}
		}

		$this->buildBreadcrumbs( $breadcrumbs );
	}

	/**
	 * get locale, active languages
	 */
	private function getLocaleLangs() {
		$this->load->model( 'localisation/language' );
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
	}

	/**
	 * get language vars
	 * @param string	$lng	optional language file to load
	 */
	private function getLangVars() {
		$this->getLocaleLangs();

		$vars = array(
			'heading_title', 'plain_title',
			'text_payment', 'text_success', 'text_enabled', 'text_disabled',
			'text_success_log', 'text_log_empty', 'text_personal_data',
			'text_current_time', 'text_copy', 'text_create_pw', 'text_show_hide',
			'text_settings', 'text_help', 'text_text', 'text_log',
			'text_new_project', 'text_common', 'text_accesskey_shift',
			'text_accesskey', 'text_module', 'text_installed', 'text_current',
			'text_license', 'text_copyright', 'text_author', 'text_support',
			'text_advanced', 'text_total_amount', 'text_total_used',
			'text_total_percent', 'text_yes', 'text_no', 'text_support_key',

			'entry_status', 'entry_sort_order', 'entry_order_status',
			'entry_geo_zone', 'entry_custId', 'entry_projId',
			'entry_successUrl', 'entry_cancelUrl', 'entry_useHash',
			'entry_projectPassword', 'entry_notifyPassword', 'entry_hashMethod',
			'entry_testMode', 'entry_successUrlStd', 'entry_cancelUrlStd',
			'entry_salutation', 'entry_user_name', 'entry_debug',
			'entry_server_shift', 'entry_street', 'entry_zip', 'entry_city',
			'entry_email', 'entry_country', 'entry_phone',
			'entry_account_holder', 'entry_account_number',
			'entry_bank_code_number', 'entry_bank_bic', 'entry_bank_iban',
			'entry_proj_password', 'entry_notify_password',
			'entry_encryption_method', 'entry_proj_name',
			'entry_proj_responsible', 'entry_email_notification',
			'entry_email_language', 'entry_legal_form', 'entry_supportKey',
			'entry_text', 'entry_title', 'entry_customer_groups', 'entry_store',
            'entry_protection',

			'help_advanced', 'help_entry_order_status', 'help_custId',
			'help_projId', 'help_successUrl', 'help_cancelUrl',
			'help_useHash', 'help_projectPassword', 'help_notifyPassword',
			'help_hashMethod', 'help_instruction', 'help_logText',
			'help_testMode', 'help_test', 'help_newProject',
			'help_newProject_error', 'help_debug', 'help_server_shift',
			'help_copy_data', 'help_password', 'help_supportKey', 'help_text',
			'help_customer_groups', 'help_store', 'help_protection',

			'entry_yes', 'entry_no', 'sel_sha1', 'sel_sha256', 'sel_sha512',
			'sel_md5', 'sel_mode1', 'sel_mode2', 'sel_select', 'sel_debug1',
			'sel_debug2', 'sel_company', 'sel_mister', 'sel_miss',
			'sel_legal1', 'sel_legal2', 'sel_legal3', 'sel_legal4',
			'sel_legal5', 'sel_legal6', 'sel_legal7', 'sel_legal8',
			'sel_legal9', 'sel_legal10', 'sel_legal11', 'sel_legal12',
			'sel_legal13', 'sel_legal14', 'sel_legal15', 'sel_legal16',
			'sel_legal17', 'sel_legal18', 'sel_legal19', 'sel_legal20',
			'sel_legal21', 'sel_legal22', 'sel_legal23', 'sel_legal24',
			'sel_legal25', 'sel_legal26',
			'sel_german', 'sel_english', 'sel_italian', 'sel_french',
			'sel_text_as_self', 'sel_text_as_text', 'sel_text_as_image',

			'button_help', 'button_apply', 'button_clear',
			'btn_create_password', 'btn_copy_data',

			'tab_common', 'tab_advanced', 'tab_log', 'tab_html',
			'tab_new_project', 'tab_stores',

			'leg_account_data', 'leg_project_data', 'leg_address',
			'leg_banking_details', 'leg_various_project_data',
			'leg_project_banking_details',

			'msg_successful_project_creation', 'msg_unsuccessful_project_creation',
			'msg_all_fields_must_be_filled', 'msg_submit_form',
			'msg_back_from_directebanking', 'msg_test_mode_on',

			// 1.0.5
			'button_save', 'button_cancel', 'text_all_zones'
		);

		foreach( $vars as $var ) {
			$this->data[$var] = $this->language->get( $var );
		}

		unset( $vars );
	}

	/**
	 * get a value either from request or config
	 * @param string	$key	key
	 * @param string	$v		value
	 */
	public function getParam( $key, $v, $ret = false ) {
		$k = $this->_name .'_'. $key;

		if( isset( $this->request->post[$k] ) ) {
      		$this->data[$k] = $this->request->post[$k];
    	}elseif( !is_null( $this->config->get( $k ) ) ) {
    		if( $ret ) {
    			return $this->config->get( $k );
    		}else{
      			$this->data[$k] = $this->config->get( $k );
   			}
    	}else{
    		if( $ret ) {
    			return $v;
    		}else{
				$this->data[$k] = $v;
			}
		}
	}

	/**
	 * get a value from a request
	 * @param string	$value		value to fetch
	 * @param mixed		$default	default value if empty or not set [optional]
	 * @param string	$from		Force to where the var should come from (POST, GET, FILES, COOKIE, SERVER, METHOD) [optional]
	 * @return mixed
	 */
	private function getRequest( $value, $default = null, $from = '' ) {
		$from	= strtolower( $from );
		$ret	= $default;

		if( $from && isset( $this->request->{$from}[$value] ) ) {
			$ret = $this->request->{$from}[$value];
		}else{
			if( isset( $this->request->post[$value] ) ) {
				$ret = $this->request->post[$value];
			}elseif( isset( $this->request->get[$value] ) ) {
				$ret = $this->request->get[$value];
			}
		}

		return $ret;
	}

	/**
	 * define template params
	 * @param string	$suffix	optional suffix (e.g. voucher_cpanel.tpl where _cpanel IS the suffix)
	 * @param string	$folder	optional folder (within type/folder ) if template is NOT in the main folder
	 */
	private function getTemplate( $suffix = '', $folder = '' ) {
		$this->template = $this->_type .'/'. ( $folder ? $folder . '/' : '' ) . $this->_name . $suffix . '.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	}

	/**
	 * get error/success messages
	 * @param array	$ext	extended messages
	 */
	private function getMessages( $ext = array() ) {
		$std	= array( 'warning' );
		$vars	= array_merge( $std, $ext );

		foreach( $vars as $var ) {
			if( isset( $this->session->data[$var] ) ) {
				$this->data['error_' . $var] = $this->session->data[$var];

				unset( $this->session->data[$var] );
			}elseif( isset( $this->error[$var] ) ) {
				$this->data['error_' . $var] = $this->error[$var];

				// needed at apply function
				$this->session->data[$var] = $this->error[$var];
                $this->session->data['error_warning'] = $this->language->get( 'error_fields' );

			}else{
				$this->data['error_' . $var] = '';
			}
		}

		unset( $vars );

		foreach( $this->data['languages'] as $lang ) {
			if( isset( $this->error['code' . $lang['language_id']] ) ) {
				$this->data['error_code' . $lang['language_id']] = $this->error['code' . $lang['language_id']];
			}else{
				$this->data['error_code' . $lang['language_id']] = '';
			}
		}

		if( isset( $this->session->data['success'] ) ) {
			$this->data['success'] = $this->session->data['success'];
			unset( $this->session->data['success'] );
		}else{
			$this->data['success'] = '';
		}
	}

    /**
     * get stored stored - 4 multistore solution
     * @return array
     */
    private function getStores() {
        $this->load->model( 'setting/store' );

        $ret = array();

        // set default
		$ret[] = array(
			'store_id' => 0,
			'name'     => $this->config->get( 'config_name' ) .'<br />'. $this->language->get( 'text_default' ),
			'url'      => HTTP_CATALOG
		);

        $results = $this->model_setting_store->getStores();

    	foreach( $results as $result ) {
			$ret[] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']   ,
				'url'      => $result['url']
			);
		}

        return $ret;
    }

	/**
	 * constructs the footer
	 *
	 * Note: displaying this footer is mandatory, removing violates the license!
	 * If you do not want to display the footer, contact the author.
	 */
	private function getFooter() {
		$this->data['oxfooter']	= '<div style="text-align:center; color:#666666; margin-top:5px">'
		. ucfirst( $this->_name ) . ' v.' .$this->_version. ' &copy; ' . date( 'Y' )
		. ' by <a href="http://osworx.net" onclick="window.open(this);return false;" title="OSWorX">OSWorX</a>'
		. '</div>';
	}

    /**
     * check users permission
     * @return bool
     */
    private function checkPermission() {
        if( !$this->user->hasPermission( 'modify', $this->_type . '/' . $this->_name ) ) {
			$this->error['warning'] = $this->language->get( 'error_permission' );
		}

        if( !$this->error ) {
			return true;
		}else{
			return false;
		}
    }

	/**
	 * validates user permission and checks specific module vars
	 * @param array		values to check
	 * @return bool
	 */
	private function validate( $vars = '' ) {
        $errLevel = false;

		$this->checkPermission();

		if( $vars ) {
			foreach( $vars as $var ) {
				if( !$this->request->post[$this->_name . '_' . $var] ) {
					$this->error[$var] = $this->language->get( 'error_' . $var );
					$errLevel = true;
				}
			}
		}

        $vars = array(
            'projId', 'projectPassword', 'notifyPassword'
        );

        $stores = $this->request->post[$this->_name .'_stores'];

        foreach( $stores as $k => $v ) {
            foreach( $vars as $var ) {
                if( !$stores[$k][$var] ) {
                    $this->error[$var] = $this->language->get( 'error_' . $var );
                    $errLevel = true;
                }
            }
        }

		if(
            $errLevel
            && empty( $this->error['warning'] )
        )
        {
			$this->error['warning'] = $this->language->get( 'error_fields' );
		}

        unset( $vars );

		if( !$this->error ) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * function is called only by directebanking directly after a new project is created through this extension
	 * note: directepayment send back ALL submitted values (see template) PLUS these 2 values automatically via GET:
	 * - user_id=xxxxx
	 * - project_id=yyyyyy
	 * - all variables are encoded, but $_GET is already decoded!
	 */
	public function newProject() {
		require_once( DIR_SYSTEM . 'osworx/libraries/osworx/generator/forms/OXGENList.php' );

		$this->load->model( 'localisation/order_status' );
		$this->load->model( 'localisation/geo_zone' );
		$this->load->model( 'localisation/country' );
		$this->load->model( 'setting/setting' );

		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		$this->getBasics();
		$this->getLangVars();
		$this->getDefaultValues();

        $this->setTabDef();
		$this->getTemplate( '_settings', 'directebanking' );
		$this->getBreadcrumbs( array( 'ad' => $this->language->get( 'text_settings' ) ) );
		$this->getMessages( array( 'custId', 'projId', 'projectPassword', 'notifyPassword' ) );
		$this->getFooter();
		$this->getDocument();
        $this->getCustomerGroups();

		$lngSelect = $this->language->get( 'sel_select' );

		// settings
        $this->data['stores']           = $this->getStores();
        $this->data['defaultValues']    = $this->getStoreDefaultValues();
		$this->data['order_statuses']	= $this->model_localisation_order_status->getOrderStatuses();
		$this->data['geo_zones']		= $this->model_localisation_geo_zone->getGeoZones();
			// check if encryption is used
		$this->data['use_encryption']	= $this->config->get( 'config_encryption' );
		$this->data['encrypt']			= urlencode( $encryption->encrypt( $this->config->get( 'config_encryption' ) ) );
		$this->data['current_time']		= sprintf(
			$this->language->get( 'text_current_time' ),
			date( 'Y-m-d H:i:s',
			$this->_serverShift )
		);

		// hash algo
		$arr = array(
			array( 'id' => 'sha1', 'name'	=> $this->language->get(  'sel_sha1' ) ),
			array( 'id' => 'sha256', 'name' => $this->language->get(  'sel_sha256' ) ),
			array( 'id' => 'sha512', 'name' => $this->language->get(  'sel_sha512' ) ),
			array( 'id' => 'md5', 'name'	=> $this->language->get(  'sel_md5' ) )
		);
		$this->data['lists']['project_hash_algorithm'] = OXGENList::buildSelectList(
			$arr,
			'project_hash_algorithm',
			'sha1',
			null,
			true,
			true,
			$lngSelect
		);

		$this->data['links'] = array(
			'action'	=> $this->buildUrl(
								$this->_type .'/'. $this->_name,
								true,
								'act=saveSetting'
							),
			'cancel'	=> $this->buildUrl( $this->_type .'/'. $this->_name ),
			'vendor'	=> '<a href="http://www.directebanking.com/" target="_blank" title="'
							. $this->language->get( 'plain_title' ).'">'
							. $this->language->get( 'plain_title' ).'</a>',
			'settings'	=> $this->buildUrl( 'setting/setting' )
		);

		$configEncryption 	= $this->config->get( 'config_encryption' );
		$getEncryption		= $encryption->decrypt( $this->getRequest( 'enc', null, 'GET' ) );
		$projId				= $this->getRequest( 'project_id', null, 'GET' );
        $storeId            = $this->getRequest( 'storeId', null, 'GET' );

		// check if encryption is set and is equal
		if( $configEncryption === $getEncryption ) {
			// override data with new fields
            $this->data['directebanking_custId'] = $this->getRequest( 'user_id', null, 'GET' );
            // multistore
            $this->data[$this->_name . '_stores'][$storeId] = array(
                'projId'            => $projId,
                'projectPassword'   => $this->getRequest( 'pppw', null, 'GET' ),
                'notifyPassword'    => $this->getRequest( 'pnp', null, 'GET' ),
                'status'            => 0,
                'successUrlStd'     => 1,
                'successUrl'        => '',
                'cancelUrlStd'      => 1,
                'cancelUrl'         => '',
                'protection'        => $this->getRequest( 'consumer_protection', null, 'GET' )
            );

			$this->data['success'] = $this->language->get( 'msg_back_from_directebanking' );

			$msg	= sprintf(
						$this->language->get( 'msg_successful_project_creation' ),
						$this->user->getId(),
						$this->user->getUsername(),
						$projId
						);
			$type	= 0;
		}else{
			$msg	= sprintf(
						$this->language->get( 'msg_unsuccessful_project_creation' ),
						$this->user->getId(),
						$this->user->getUsername(),
						$projId
						);
			$type	= 3;
		}

		$this->writeLog( $msg, $type );

		$this->buildResponse();
	}

	/**
	 * write lines into a log file which is accessable through the backend
	 * note: new entries at first position
	 * note 2: because of the behaviour of OpenCart (files are readable from outside!), data is encrypted
	 * @param string	$msg	valid string to write into the log file
	 * @param int		$type	type of the message
	 * [0 = normal, 1 = new transaction, 2 = valid transaction, 3 = error, 4 = cancel, 5 = test]
	 * @param string	$file	optional file name
	 */
	public function writeLog( $msg, $type = 0, $file = '' ) {
		$file		= DIR_LOGS . ( $file ? $file : $this->_name . '.txt' );
		$logData	= '';

		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		if( file_exists( $file ) ) {
			$handle		= fopen( $file, 'rb' );
			$logData	= file_get_contents( $file );
			$logData	= $encryption->decrypt( $logData );
			fclose( $handle );
		}

		$tag = '';
		switch( $type ) {
			case 1:
				$tag = '#TRANS_NEW#';
				break;

			case 2:
				$tag = '#TRANS_VALID#';
				break;

			case 3:
				$tag = '#ERR#';
				break;

			case 4:
				$tag = '#CANCEL#';
				break;

			case 5:
				$tag = '#TEST#';
				break;

			case 0:
			default:
				$tag = '';
				break;
		}

		$logData = ( $tag ? $tag : '' )
		. date( 'Y-m-d H:i:s', $this->_serverShift )
		. ' - '
		. 'IP [' . $this->getIp() . ']'
	 	. ' - '
		. str_replace( '<br />', "\n", $msg )
		. ( $tag ? '#END#' : '' )
		. ( $logData ? "\n" . $logData : '' );

		// encrypt data
		$logData = $encryption->encrypt( $logData );

		$handle	= fopen( $file, 'wb' );
		fwrite( $handle, $logData );
		fclose( $handle );
	}

	/**
	 * fetch a ip address
	 * notes:
	 * - check first with HTTP_X_... if a proxy is used
	 * - we get there the last address because the proxy could submit several
	 * - if the address is in ipv4 format it is converted to an mapped ipv6 address
	 * - check for mapped ipv4 addresses must be done in corresponding function (like whois)
	 * see mapping/converting: http://en.wikipedia.org/wiki/IPv6#IPv4-mapped_IPv6_addresses
	 * & http://tools.ietf.org/html/rfc4291#section-2.5.5.2
	 * @param bool		$ipv6	return ipv4 in ipv6 notation
	 * @return string
	 */
	private function getIp( $ipv6 = true ) {
		if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		    $ip = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
		    if( is_array( $ip ) ) {
		    	$ip = end( $ip );
		    }else{
		    	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		    }
  		}elseif( isset( $_SERVER['HTTP_CLIENT_IP'] ) ){
    		return $_SERVER['HTTP_CLIENT_IP'];
		}else{
		    $ip = $_SERVER['REMOTE_ADDR'];
		}

		if( $ipv6 ) {
			// simple check: ipv6 does not contain a .
			if( strpos( $ip, '.' ) !== false ) {
				$ip = '::ffff:' . $ip;
			}
		}

		return $ip;
	}

	/**
	 * get the data of the logfile
	 * convert tags to valid html
	 * @param string	$file	optional file name
	 * @return string
	 */
	private function getLog( $file = '' ) {
		$file	= DIR_LOGS . ( $file ? $file : $this->_name . '.txt' );
		$log	= array( 'content' => '', 'notEmpty' => false );
		$txt	= '';

		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		if( file_exists( $file ) ) {
			$txt = file_get_contents( $file, FILE_USE_INCLUDE_PATH, null );
			$txt = $encryption->decrypt( $txt );
		}

		// replace tags from log file
		if( strlen( $txt ) != 0 ) {
			$txt = str_replace( '#ERR#',		'<span style="color: #FF0000;">', $txt );
			$txt = str_replace( '#TRANS_NEW#',	'<span style="color: #000DFF;">', $txt );
			$txt = str_replace( '#TRANS_VALID#','<span style="color: #0C8201;">', $txt );
			$txt = str_replace( '#CANCEL#',		'<span style="color: #AF7201;">', $txt );
			$txt = str_replace( '#TEST#',		'<span style="color: #9801AF;">', $txt );
			$txt = str_replace( '#END#',		'</span>', $txt );
			$txt = str_replace( "\n",			'<br />', $txt );

			$log['content']		= $txt;
			$log['notEmpty']	= true;
		}else{
			$log['content']		= $this->language->get( 'text_log_empty' );
		}

		return $log;
	}

	/**
	 * clear the logfile
	 * @param string	$file	optional file name
	 */
	public function clearLog( $file = '' ) {
		$this->getBasics();
		$this->getLangVars();
		$this->getDefaultValues();

		$file	= DIR_LOGS . ( $file ? $file : $this->_name . '.txt' );
		$handle = fopen( $file, 'w+' );
		fclose( $handle );

		$this->session->data['success'] = $this->language->get( 'text_success_log' );

		$this->redirect(
			$this->buildUrl(
				$this->_type .'/'. $this->_name,
				false
			)
		);
	}

    /**
	 * check installed vs current version
	 */
	private function checkCurrentVersion() {
        $this->load->language( $this->_type .'/'. $this->_name );

		$version = $this->getVersionOnly();

		$this->data['cVersion'] = $version->cVer;
        $this->data['xmlReply'] = '';

        switch( $version->dif )
        {
            case 1:
            default:
                $this->data['class']	= 'orange';
                $this->data['outDated']	= true;
                $this->data['xmlReply'] = $this->language->get( 'text_unknown_version' );
                break;

            case -1:
    			$this->data['class']	= 'red';
    			$this->data['outDated']	= true;
                $this->data['xmlReply'] = $this->language->get( 'text_no_info_available' );

                if( $version->cLog ) {
                    $this->data['xmlReply'] = $this->language->get( 'text_new_version_available')
                    . '<br />'
                    . nl2br( $version->cLog );
                }

                break;

            case 0:
            default:
                $this->data['class']	= 'green';
                $this->data['outDated']	= false;
                break;
		}
	}

	/**
	 * builds cpanel icons
	 */
	private function getIcons( $icons ) {
		require_once( DIR_SYSTEM . 'osworx/libraries/browser/browser_detection_simple.php' );
		$isMoz = ( browser_detection( 'browser' ) == 'moz' ) ? true : false;

		$this->data['icons'] = array();

		foreach( $icons as $icon ) {
			$href = ( !empty( $icon['link'] )
				? $icon['link']
				: $this->buildUrl(
						$this->_type .'/'. $this->_name,
						true,
						'act=' . $icon['act']
					)
			);
			$targ = ( !empty( $icon['target'] ) ? ' target="' . $icon['target'] . '"' : '' );

		 	$this->data['icons'][] = '<div style="float:left;">' . "\n"
			. "\t" . '<div class="cqi">'
				. '<a href="' . $href . '"'
					. ' title="' . $icon['title']
					. ( $icon['accKey']
						? ( $isMoz
								? ' [' . $this->language->get( 'text_accesskey_shift' )
								: $this->language->get( 'text_accesskey' )
							)
							. ' + '
							. $icon['accKey'] . ']" accesskey="' . $icon['accKey'] . '"'
						: ''
					)
					. $targ
					. '>'
						. '<img src="' . $this->_images . 'cpanel/' . $icon['img'] . '.png' . '"'
						. ' alt="' . $icon['title'] . '" border="0" />'
			    		. '<span>' . $icon['title'] . '</span>'
				. '</a>'
				. '</div>'
			. '</div>';
		}
	}

	/**
	 * get several status icons (16x16)
	 */
	private function getStatusIcons() {
		$img = '<img src="view/image/osworx/16/%sokay.png" height="16" width="16" title="%s" alt="%s" border="0" />';
		$this->data['imgNokay']	= sprintf( $img, 'not_' );
		$this->data['imgOkay']	= sprintf( $img, '' );
	}

	/**
	 * get several data
	 */
	private function getSettings() {
		require_once( DIR_SYSTEM . 'osworx/libraries/osworx/generator/forms/OXGENList.php' );

		$this->getDefaultValues();
		$this->getBreadcrumbs( $add = array( 'ad' => $this->language->get( 'text_settings' ) ) );
		$this->getMessages( array( 'custId', 'projId', 'projectPassword', 'notifyPassword' ) );

		/*
		// mic: because of the bug in the original file, we have the correct function here
		$this->load->model( 'setting/setting' );
		$this->model_setting_setting->getSetting( $this->_name );
		*/
		$this->getSetting();

		$this->load->model( 'localisation/order_status' );
		$this->load->model( 'localisation/geo_zone' );
		$this->load->model( 'localisation/country' );

		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		$lngSelect = $this->language->get( 'sel_select' );

		$this->data['links'] = array(
			'action'	=> $this->buildUrl(
								$this->_type .'/'. $this->_name,
								true,
								'act=saveSetting'
							),
			'cancel'	=> $this->buildUrl(
								$this->_type .'/'. $this->_name
							),
			'vendor'	=> '<a href="http://www.sofort.com/" target="_blank"'
							. ' title="'.$this->language->get( 'plain_title' ).'">'
							. $this->language->get( 'plain_title' )
							.'</a>',
			'settings'	=> $this->buildUrl(
								'setting/setting',
								true,
								'act=saveSetting'
							)
		);

		// settings
		$this->data['order_statuses']	= $this->model_localisation_order_status->getOrderStatuses();
		$this->data['geo_zones']		= $this->model_localisation_geo_zone->getGeoZones();
			// check if encryption is used
		$this->data['use_encryption']	= $this->config->get( 'config_encryption' );
		$this->data['encrypt']			= urlencode( $encryption->encrypt( $this->config->get( 'config_encryption' ) ) );
		$this->data['current_time']		= sprintf(
											$this->language->get( 'text_current_time' ),
											date( 'Y-m-d H:i:s', $this->_serverShift )
											);
        $this->data['defaultValues']    = $this->getStoreDefaultValues();
        $this->data['stores']           = $this->getStores();

		// hash algo
		$arr = array(
			array( 'id' => 'sha1', 'name'	=> $this->language->get(  'sel_sha1' ) ),
			array( 'id' => 'sha256', 'name' => $this->language->get(  'sel_sha256' ) ),
			array( 'id' => 'sha512', 'name' => $this->language->get(  'sel_sha512' ) ),
			array( 'id' => 'md5', 'name'	=> $this->language->get(  'sel_md5' ) )
		);
		$this->data['lists']['project_hash_algorithm'] = OXGENList::buildSelectList(
			$arr,
			'project_hash_algorithm',
			'sha1',
			null,
			true,
			true,
			$lngSelect
		);

		$this->getCustomerGroups();
		$this->getTemplate( '_settings', 'directebanking' );
		$this->setTabDef();
		$this->buildResponse();
	}

	/**
	 * save settings
	 */
	private function saveSetting() {
		$vars = array(
            'custId'
        );

		if(
            ( $this->request->server['REQUEST_METHOD'] == 'POST' )
            && ( $this->validate( $vars ) )
		)
		{
			$this->_updateSetting( $this->request->post );

			$this->session->data['success'] = $this->language->get( 'text_success' );
		}else{
			$this->mode = 'apply';
			$this->getMessages( array( 'custId', 'projId', 'projectPassword', 'notifyPassword' ) );
		}

		if( $this->mode == 'apply' ) {
			$this->redirect(
				$this->buildUrl(
					$this->_type .'/'. $this->_name,
					false,
					'act=settings'
				)
			);
		}else{
			$this->redirect(
				$this->buildUrl(
					$this->_type .'/'. $this->_name,
					false
				)
			);
		}
	}

	/**
	 * get text data
	 */
	private function editText() {
		$this->getDefaultValues();
		$this->getBreadcrumbs( $add = array( 'ad' => $this->language->get( 'text_text' ) ) );
		$this->getMessages();

		$this->data['links'] = array(
			'action'	=> $this->buildUrl(
								$this->_type .'/'. $this->_name,
								true,
								'act=saveText'
							),
			'cancel'	=> $this->buildUrl(
								$this->_type .'/'. $this->_name
							),
			// needed for filemanager CKEditor
			'filemgr'	=> $this->buildUrl(
								'common/filemanager',
								true,
								'act=saveSetting'
							)
		);

		// get sample text for instruction
		foreach( $this->data['languages'] as $lang ) {
			$this->data['sample'][$lang['language_id']] = $this->language->get( 'text_sample' );
		}

		$this->getTemplate( '_text', 'directebanking' );
		$this->setTabDef();
		$this->buildResponse();
	}

    /**
	 * get support data
	 */
	private function support() {
		$this->getDefaultValues();
		$this->getBreadcrumbs( $add = array( 'ad' => $this->language->get( 'text_support' ) ) );
		$this->getMessages();

		$this->data['links'] = array(
			'action'	=> $this->buildUrl(
				$this->_type .'/'. $this->_name,
				true,
				'act=saveSupport'
			),
			'cancel'	=> $this->buildUrl(
				$this->_type .'/'. $this->_name
			)
		);

        $this->getSupportData();

		$this->getTemplate( '_support', 'directebanking' );
		$this->setTabDef();
		$this->buildResponse();
	}

    /**
     * save a single setting value
     */
    private function updateSetting() {
        if(
			( $this->request->server['REQUEST_METHOD'] == 'POST' )
			&& ( $this->checkPermission() )
		)
		{
			$this->_updateSetting( $this->request->post );

			$this->session->data['success'] = $this->language->get( 'text_success' );
		}

		if( $this->mode == 'apply' ) {
            $act = strtolower ( str_replace( 'save', '', $this->act ) );
			$this->redirect(
				$this->buildUrl(
					$this->_type .'/'. $this->_name,
					false,
					'act=' . $act
				)
			);
		}else{
			$this->redirect(
				$this->buildUrl(
					$this->_type .'/'. $this->_name,
					false
				)
			);
		}
    }

	/**
	 * show existing log (file)
	 */
	private function showLog() {
		$this->getDefaultValues();
		$this->getBreadcrumbs( $add = array( 'ad' => $this->language->get( 'text_log' ) ) );
		$this->getMessages();

		$this->data['links'] = array(
			'cancel'	=>	$this->buildUrl(
								$this->_type .'/'. $this->_name
							),
			'clear'		=>	$this->buildUrl(
								$this->_type .'/'. $this->_name .'/clearlog'
							)
		);

		// get log data
		$this->data['log'] = $this->getLog();

		$this->getTemplate( '_log', 'directebanking' );
		$this->buildResponse();
	}

	/**
	 * prepare data for creating a new project
	 */
	private function showNewProject() {
		require_once( DIR_SYSTEM . 'osworx/libraries/osworx/generator/forms/OXGENList.php' );

		$shopUrl = HTTP_CATALOG . 'index.php?route=payment/directebanking/';

		// check for secured protocol
		if(
			isset( $_SERVER['HTTPS'] )
			&& $_SERVER['HTTPS'] == 'on'
		)
		{
			$shopUrl = str_replace( 'http', 'https', $shopUrl );
		}

		$this->load->model( 'localisation/country' );

		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		$this->getDefaultValues();
		$this->getBreadcrumbs( $add = array( 'ad' => $this->language->get( 'text_new_project' ) ) );
		$this->getMessages();

		$this->data['links'] = array(
			'cancel'		=> $this->buildUrl( $this->_type .'/'. $this->_name ),
			'newProject'	=> 'https://www.directebanking.com/payment/createNew/',
			'notifyUrl'		=> $shopUrl . 'verify',
			'successUrl'	=> '-USER_VARIABLE_0-', // $shopUrl . 'success&transaction=-TRANSACTION-&security_criteria=-SECURITY_CRITERIA-&order_id=-USER_VARIABLE_3-&pid=-PROJECT_ID-',
			'cancelUrl'		=> '-USER_VARIABLE_1-', // $shopUrl . 'cancel&transaction=-TRANSACTION-&order_id=-USER_VARIABLE_3-&pid=-PROJECT_ID-',
			'backLink'		=> $this->buildUrl( 'payment/directebanking/newProject' ),
			'homePage'		=> HTTP_CATALOG,
			'settings'		=> $this->buildUrl( 'setting/setting' )
		);

		// check if encryption is used
		$this->data['use_encryption']	= $this->config->get( 'config_encryption' );
		$this->data['encrypt']			= urlencode( $encryption->encrypt( $this->config->get( 'config_encryption' ) ) );

		// build lists
		$lngSelect = $this->language->get( 'sel_select' );

		$arr = array(
			array( 'id' => '1', 'name' => $this->language->get( 'sel_company' ) ),
			array( 'id' => '2', 'name' => $this->language->get( 'sel_mister' ) ),
			array( 'id' => '3', 'name' => $this->language->get( 'sel_miss' ) )
		);
		$this->data['lists']['user_salutation'] = OXGENList::buildSelectList(
            $arr,
            'user_salutation',
            null,
            null,
            true,
            true,
            $lngSelect
        );

		$arr = array(
			array( 'id' => '1', 'name' => $this->language->get(  'sel_legal1' ) ),
			array( 'id' => '2', 'name' => $this->language->get(  'sel_legal2' ) ),
			array( 'id' => '3', 'name' => $this->language->get(  'sel_legal3' ) ),
			array( 'id' => '4', 'name' => $this->language->get(  'sel_legal4' ) ),
			array( 'id' => '5', 'name' => $this->language->get(  'sel_legal5' ) ),
			array( 'id' => '6', 'name' => $this->language->get(  'sel_legal6' ) ),
			array( 'id' => '7', 'name' => $this->language->get(  'sel_legal7' ) ),
			array( 'id' => '8', 'name' => $this->language->get(  'sel_legal8' ) ),
			array( 'id' => '9', 'name' => $this->language->get(  'sel_legal9' ) ),
			array( 'id' => '10', 'name' => $this->language->get(  'sel_legal10' ) ),
			array( 'id' => '11', 'name' => $this->language->get(  'sel_legal11' ) ),
			array( 'id' => '12', 'name' => $this->language->get(  'sel_legal12' ) ),
			array( 'id' => '13', 'name' => $this->language->get(  'sel_legal13' ) ),
			array( 'id' => '14', 'name' => $this->language->get(  'sel_legal14' ) ),
			array( 'id' => '15', 'name' => $this->language->get(  'sel_legal15' ) ),
			array( 'id' => '16', 'name' => $this->language->get(  'sel_legal16' ) ),
			array( 'id' => '17', 'name' => $this->language->get(  'sel_legal17' ) ),
			array( 'id' => '18', 'name' => $this->language->get(  'sel_legal18' ) ),
			array( 'id' => '19', 'name' => $this->language->get(  'sel_legal19' ) ),
			array( 'id' => '20', 'name' => $this->language->get(  'sel_legal20' ) ),
			array( 'id' => '21', 'name' => $this->language->get(  'sel_legal21' ) ),
			array( 'id' => '22', 'name' => $this->language->get(  'sel_legal22' ) ),
			array( 'id' => '23', 'name' => $this->language->get(  'sel_legal23' ) ),
			array( 'id' => '24', 'name' => $this->language->get(  'sel_legal24' ) ),
			array( 'id' => '25', 'name' => $this->language->get(  'sel_legal25' ) ),
			array( 'id' => '26', 'name' => $this->language->get(  'sel_legal26' ) )
		);
		$this->data['lists']['user_legal_form_id'] = OXGENList::buildSelectList(
			$arr,
			'user_legal_form_id',
			null,
			null,
			true,
			true,
			$lngSelect
		);

		// country lists
        $countries  = $this->model_localisation_country->getCountries();
        $index      = array( 'id' => 'iso_code_2', 'name' => 'name' );

		$this->data['lists']['user_country_id'] = OXGENList::buildSelectList(
			$countries,
			'user_country_id',
			null,
			null,
			true,
			true,
			$this->language->get( 'sel_select' ),
			$index
		);
		$this->data['lists']['usersdirectdebitbankaccount_country_id'] = OXGENList::buildSelectList(
			$countries,
			'usersdirectdebitbankaccount_country_id',
			null,
			null,
			true,
			true,
			$lngSelect,
			$index
		);
		$this->data['lists']['project_country_id'] = OXGENList::buildSelectList(
			$countries,
			'project_country_id',
			null,
			null,
			true,
			true,
			$lngSelect,
			$index
		);
		$this->data['lists']['projectsbankaccount_country_id'] = OXGENList::buildSelectList(
			$countries,
			'projectsbankaccount_country_id',
			null,
			null,
			true,
			true,
			$lngSelect,
			$index
		);

		// other lists
		$arr = array(
			array( 'id' => 'DE', 'name' => $this->language->get(  'sel_german' ) ),
			array( 'id' => 'EN', 'name' => $this->language->get(  'sel_english' ) ),
			array( 'id' => 'IT', 'name' => $this->language->get(  'sel_italian' ) ),
			array( 'id' => 'FR', 'name' => $this->language->get(  'sel_french' ) )
		);
		$this->data['lists']['projectsnotification_email_language_id'] = OXGENList::buildSelectList(
			$arr,
			'projectsnotification_email_language_id',
			null,
			null,
			true,
			true,
			$lngSelect
		);

		// hash algo
		$arr = array(
			array( 'id' => 'sha1', 'name'	=> $this->language->get(  'sel_sha1' ) ),
			array( 'id' => 'sha256', 'name' => $this->language->get(  'sel_sha256' ) ),
			array( 'id' => 'sha512', 'name' => $this->language->get(  'sel_sha512' ) ),
			array( 'id' => 'md5', 'name'	=> $this->language->get(  'sel_md5' ) )
		);
		$this->data['lists']['project_hash_algorithm'] = OXGENList::buildSelectList(
			$arr,
			'project_hash_algorithm',
			'sha1',
			null,
			true,
			true,
			$lngSelect
		);

        // stores
        $stores = $this->getStores();
        $arr    = array();

        foreach( $stores as $store ) {
            $arr[] = array(
                'id'    => $store['store_id'],
                'name'  => $store['name']
            );
        }
        $this->data['lists']['stores'] = OXGENList::buildSelectList(
            $arr,
            'store',
            null,
            null,
            true,
            true,
            $lngSelect
        );

        $this->data['stores'] = $stores;

        unset( $arr, $stores );

		$this->getTemplate( '_new_project', 'directebanking' );
		$this->buildResponse();
	}

	/**
	 * update only defined setting value(s)
	 * @param array		$vals	key/value pairs ($_POST)
	 * @param string	$group	optional definition for config group (name)
	 */
	private function _updateSetting( $vals, $group = '' ) {
		$ignore = array( 'task', 'mode', 'act' );

		if( !$group ) {
			$group = $this->_name;
		}

		// check for arrays and serialize
		foreach( $vals as $key => $value ) {
			if( is_array( $value ) ) {
				$vals[$key] = serialize( $value );
			}
		}

		foreach( $vals as $key => $value ) {
			if( !in_array( $key, $ignore ) ) {
				if( strpos( $key, $this->_name ) == 0 ) {
					$query = '
                    DELETE
                    FROM
                        `' . DB_PREFIX . 'setting`
					WHERE
                        `key` = \'' . $this->db->escape( $key ) . '\'';

					$this->db->query( $query );

					$query = '
                    INSERT
                    INTO
                        `' . DB_PREFIX . 'setting`
					SET
                        `group` = \'' . $this->db->escape( $group ) . '\',
						`key` = \'' . $this->db->escape( $key ) . '\',
						`value` = \'' . $this->db->escape( $value ) . '\'';

					$this->db->query( $query );
				}
			}
		}
	}

    /**
	 * because of a bug in OC vesion prior 1.4.8 we use this function here
	 * @return array
	 */
	private function getSetting() {
		$sql = '
        SELECT
            *
		FROM
            `' . DB_PREFIX . 'setting`
		WHERE
            `group` = \'' . $this->db->escape( $this->_name ) . '\'';

        $result = $this->db->query( $sql );

		foreach( $result->rows as $res ) {
            if( $this->isSerialized( $res['value'] ) ) {
                $data[$res['key']] = unserialize( $res['value'] );
            }else{
                $data[$res['key']] = $res['value'];
            }
		}
	}

    /**
     * check if a value is serialized
     * @param string    $str
     * @return bool
     */
    private function isSerialized( $str ){
        $st["array"] = "a}";
        $st["object"] = "O}";
        $st["string"] = "s;";
        $st["integer"] = "i;";
        $st["float"] = "d;";
        $st["bool"] = "b;";

        foreach( $st as $t => $p ) {
            if( preg_match( "/^" . $p[0] . ":.*\\" .$p[1] . "$/is", $str ) ) {
                return true;
            }
        }

        return false;
    }

	/**
	 * gets the total amount paid with this module
	 * @return array
	 */
	private function getTotal() {
		// get total overall amount
		$this->load->model( 'sale/order' );
		$total_sale = $this->model_sale_order->getTotalSales();

		// get total with this payment
		$payMethods 	= array( 'sofortüberweisung', 'directebanking' );
		$ret			= array( 'total' => 0, 'used' => 0 );

		$query = '
        SELECT
            payment_method, total
		FROM
            `' . DB_PREFIX . 'order`
		WHERE
            order_status_id > 0';

		$result = $this->db->query( $query );

		foreach( $result->rows as $row ) {
			if(
                stristr( $row['payment_method'], 'sofortüberweisung' ) !== false
                || stristr( $row['payment_method'], 'directebanking' ) !== false
			)
			{
				$ret['total'] += $row['total'];
				++$ret['used'];
			}
		}

		// get percent of overall
		if( $ret['total'] ) {
			$ret['percent'] = number_format( ( $ret['total'] / $total_sale ) * 100, 2 );
		}else{
			$ret['percent'] = '0,00';
		}

		return $ret;
	}

	/**
	 * get customergroups and build html select list
	 */
	private function getCustomerGroups() {
		$this->load->model( 'sale/customer_group' );

		$customer_groups	= $this->model_sale_customer_group->getCustomerGroups();
		$count				= count( $customer_groups );

		if( $count < 10 ) {
			$size = $count + 2;
		}else{
			$size = 10;
		}

		$list = '<select name="' . $this->_name . '_customer_groups[]"'
		. ' id="' . $this->_name . '_customer_groups" multiple="multiple"'
		. ' size="' . $size . '"'
		. '>' . "\n"
		. '<option value="-1"'
		. ( in_array( '-1', $this->data[$this->_name . '_customer_groups'] )
				? ' selected="selected"'
				: ''
			)
		. '>' . $this->language->get( 'text_all' ) . '</option>' . "\n"
		. '<option value="" disabled="disabled"'
			. ' style="color:#999999">- - - - - - - - -</option>' . "\n"
		;

		foreach( $customer_groups as $cGroup ) {
			$list .= '<option value="' . $cGroup['customer_group_id'] . '"'
			. ( in_array( $cGroup['customer_group_id'], $this->data[$this->_name . '_customer_groups'] )
				? ' selected="selected"'
				: ''
			)
			. '>' . $cGroup['name'] . '</option>' . "\n";
		}

		$list .= '</select>' . "\n";

		$this->data['lists']['customer_groups'] = $list;
	}

    /** ######### support functions ######### */

    /**
     * support: add all relevant support data to object
     * @param bool  $tpl    define also various template data
     */
    private function getSupportData( $tpl = true ) {
        $supported = false;

        if( $supporter = $this->getSupportInstance() ) {
            $supporter->_getSupportLinks();
            $supporter->_getSupportLangVars();
            $supporter->_addSupportDocumentData();

            $supported = true;

            if( $tpl ) {
                $this->setSupportTemplateData( $supporter, $supported  );
            }
        }

        return $supported;
    }

    /**
	 * support: get current version ( and changelog if newer )
	 */
    public function getVersionOnly() {
        if( $supporter = $this->getSupportInstance() ) {
            return $supporter->_getVersionOnly();
        }
    }

    /**
	 * support: checks installed version vs current published
	 */
    public function checkVersion() {
        if( $supporter = $this->getSupportInstance() ) {
            $supporter->_checkVersion();
        }
    }

    /**
     * support: get date for valid support
     * @return array
     */
    public function isValidUntil() {
        if( $supporter = $this->getSupportInstance() ) {
            $supporter->_isValidUntil();
        }
    }

    /**
     * support: update current extension
     * @return array
     */
    public function updateNow() {
        if( $supporter = $this->getSupportInstance() ) {
            $supporter->_updateNow();
        }
    }

    /**
     * get instance of supporter class
     * @return mixed object/false
     */
    private function getSupportInstance() {
        $lib = DIR_SYSTEM . 'osworx/libraries/tools/oxsupporter.php';

        if( file_exists( $lib ) ) {
            require_once( $lib );
            return ControllerModuleOXSupporter::getInstance( $this );
        }

        return false;
    }

    /**
     * build additional data and add to global scope
     * @param bool  $supported
     */
    private function setSupportTemplateData( $supporter, $supported ) {
        $supporter->_setSupportTemplateData( $supported );
    }
}