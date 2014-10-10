<?php
/**
 * @version		$Id: controllerCompatibility.php 2942 2012-10-11 13:14:36Z mic $
 * @package		OCIE
 * @author		mic - http://osworx.net
 * @copyright	2011 OSWorX - http://osworx.net
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

class controllerCompatibility extends controller
{
	private $_version = '1.0.7';

	/**
	 * install this module with default settings
	 * - if supported by the system redirect into extension
	 */
    public function install() {
    	$this->getBasics();
    	$this->getDefaultValues( true );
        $this->load->model( 'setting/setting' );

        $this->model_setting_setting->editSetting(
			$this->_name,
			$this->data
		);

		$this->session->data['success'] = $this->language->get( 'text_success_installed' );

		$this->redirect( $this->buildUrl( 'extension/' . $this->_type, false ) );
    }

    /**
     * basic check for OpenCart version
	 */
    public function checkOCVersion() {
		$this->_ocversion = '1.5';

		if( method_exists( $this->document, 'addBreadcrumb' ) ) {
			$this->_ocversion = '1.4';
		}

		// check for sub versions of 1.5.
		if( $this->_ocversion == '1.5' ) {
			if( method_exists( $this->tax, 'getRates' ) ) {
				// >= 1.5.1.3
				$this->_ocversion = '1.5.1.3';
			}

			if( function_exists( 'utf8_strlen' ) ) {
				// 1.5.2
				$this->_ocversion = '1.5.2';
			}
		}
	}

	/**
	 * check if SSL is enabled
	 */
	public function checkSSL() {
		if(
			// OC15
			$this->config->get( 'config_use_ssl' )
			// OC14
			|| $this->config->get( 'config_ssl' )
		)
		{
			$this->_useSSL = true;
		}
	}

	/**
	 * replace in a url the plain & with &amp; and vice versa
	 * note: OCIE handles url->link in a different way
	 * @param string	$url	the url to convert (should be only the route)
	 * @param bool		$xhtml	build XHTML valid urls
	 * @param string	$args	additonal arguments (used only at OC 15x)
	 * @return	string
	 */
	public function buildUrl( $url, $xhtml = true, $args = '' ) {
		if( $args ) {
				$args = '&' . trim( $args, '&' );
		}

		if( $this->_ocversion == '1.4' ) {
			$url = sprintf( $this->_url, $url ) . $args;

			if( $xhtml ) {
				return str_replace( '&', '&amp;', $url );
			}else{
				return str_replace( '&amp;', '&', $url );
			}
		}else{
			return $this->url->link(
				$url,
				$args . '&token=' . $this->session->data['token'],
				true,
				$xhtml,
				true
			);
		}
	}

	/**
	 * OC.Verseion depending build if breadcrumbs
	 * @param array	$data
	 */
	public function buildBreadcrumbs( $data ) {
		if( $this->_ocversion == '1.4' ) {
			$this->document->breadcrumbs	= $data;
		}else{
			$this->data['breadcrumbs']		= $data;;
		}

		unset( $data );
	}

	/**
	 * OC.version depending build of response
	 */
	public function buildResponse() {
		if( $this->_ocversion == '1.4' ) {
			$this->response->setOutput(
				$this->render( true),
				$this->config->get( 'config_compression' )
			);
		}else{
			$this->response->setOutput( $this->render() );
		}
	}

	/**
	 * OC.verson depending build of title
	 */
	public function buildTitle( $title ) {
		if( $this->_ocversion == '1.4' ) {
			$this->document->title = $title;
		}else{
			$this->document->setTitle( $title );
		}
	}

	/**
	 * OC.verson depending build of title
	 */
	public function setTabDef() {
		if( $this->_ocversion == '1.4' ) {
			$this->data['tab'] = 'tab';
		}else{
			$this->data['tab'] = 'href';
		}
	}

	/**
	 * add script to global array
	 */
	public function addScript( $data ) {
		if( $this->_ocversion == '1.4' ) {
			$this->document->scripts[] = $data;
		}else{
			$this->document->addScript( $data );
		}
	}

	/**
	 * support function for JQuery DatePicker
	 * checks if the current language is supported, otherwise use default (en)
	 * NOTE: if your language is not supported, open the folder jquery/ui/i18n
	 * and create a new language file (use one of the existing as sample).
	 * Afterwards add your newly created language code in the array below
	 * @see http://docs.jquery.com/UI/Datepicker
	 */
	protected function getDatePicker() {
		$code = $this->language->get( 'code' );

		if( $this->_ocversion == '1.4' ) {
			$this->addScript( 'view/javascript/jquery/ui/ui.datepicker.js' );
		}else{
			$this->addScript( 'view/javascript/jquery/ui/jquery-ui-timepicker-addon.js' );
		}

		// get language
		$arr = array (
			'ar','bg','ca','cs','da','de','el','eo','es','fa','fi','fr','he',
			'hr','hu','id','is','it','ja','ko','lt','lv','ms','nl','no','pl',
			'pt-BR','ro','ru','sk','sl','sq','sr','sr-SR','sv','th','uk',
			'zh-CN','zh-TW'
		);

		if( in_array( $code, $arr ) ) {
			$this->addScript( 'view/javascript/jquery/ui/i18n/ui.datepicker-' . $code . '.js' );
		}
	}

	/**
	 * Json output
	 * @param array		$data
	 */
	public function getJsonOutput( $data ) {
		if( version_compare( $this->_ocversion, '1.5.1.3', '>=' ) ) {
			$this->response->setOutput( json_encode( $data ) );
		}else{
			$this->load->library( 'json' );
			$this->response->setOutput( Json::encode( $data ) );
		}
	}
}