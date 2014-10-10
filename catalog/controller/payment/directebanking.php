<?php
/**
 * @version		$Id: directebanking.php 3251 2013-05-10 19:56:43Z mic $
 * @package		Directebanking - Controller User
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

require_once( DIR_APPLICATION . 'controller' .DIRECTORY_SEPARATOR. 'controllerCompatibility.php' );

class ControllerPaymentDirectebanking extends controllerCompatibility
{
	public $_name		= 'directebanking';
	private $_type		= 'payment';
	private $_param		= array();
	public $_ocversion	= '';
	private $_debug;
	private $_version	= '2.1.3';

	public function index() {
		$this->getBasics();
		$this->getParams();
		$this->getLanguage();
		$this->getData();
		$this->getTemplate();
		$this->getFooter();
		$this->render();
	}

	/**
	 * several basic data
	 */
	protected function getBasics() {
		$this->id			= $this->_type;
		$this->_serverShift = time() + ( $this->config->get( $this->_name . '_server_shift' ) * 3600 );
		$this->_debug		= $this->config->get( $this->_name . '_debug' );

		$this->checkOCVersion();
		$this->data['_ocversion'] = $this->_ocversion;

		$this->language->load( $this->_type .'/'. $this->_name );
	}

    /**
	 * get language vars
	 */
	private function getLanguage() {
		$vars = array(
			// standard
				// OC 14x
			'button_back',
				// OC 15x
			'button_confirm',
			// advanced
			'msg_testmode_on'
		);

		foreach( $vars as $var ) {
			$this->data[$var] = $this->language->get( $var );
		}

		/*
		// language dependent vars
		$vars = array( 'instruction' );

		foreach( $vars as $var ) {
			$this->data[$var] = html_entity_decode(
				$this->getParam(
					$var .'_'. $this->config->get( 'config_language_id' ),
					'',
					true
				),
				ENT_QUOTES
			)
			. ( $this->_param['testMode']
				? '<br />' . $this->language->get( 'text_testOrder' )
				: ''
			);
		}
		*/

		unset( $vars );
	}

	/**
	 * get params
	 */
	private function getParams() {
        // single vars
		$vars = array(
            // single vars
			'custId', 'useHash', 'hashMethod', 'testMode',
			'geo_zone_id', 'order_status_id',
		);

        foreach( $vars as $var ) {
			$this->getParam( $var );
		}

        // array vars
        $vars = array(
            'stores'
		);

        foreach( $vars as $var ) {
			$storeVars = $this->getParam( $var, '', true, true );
		}

        // assign store depending vars to _param
        foreach( $storeVars[(int) $this->config->get( 'config_store_id' )] as $k => $v ) {
            $this->_param[$k] = $v;
        }

		unset( $vars );
	}

	/**
	 * get a value either from request or config
	 * @param string	$parm	the parameter value to fetch
	 * @param string	$add	additional parameter value as suffix
	 * @param bool		$ret	optional: return the value or set value in array
	 * @return mixed
	 */
	private function getParam( $parm, $add = '', $ret = false, $serialized = false ) {
		$name = $this->_name .'_'. $parm . ( $add ? '_' . $add : '' );

		if( isset( $this->request->post[$name] ) ) {
			if( $ret ) {
				return $this->request->post[$name];
			}else{
				$this->_param[$parm] = $this->request->post[$name];
			}
		}else{
			if( $ret ) {
                if( $serialized ) {
				    return unserialize( $this->config->get( $name ) );
                }else{
                    $this->config->get( $name );
                }
			}else{
                if( $serialized ) {
				    $this->_param[$parm] = unserialize( $this->config->get( $name ) );
                }else{
                    $this->_param[$parm] = $this->config->get( $name );
                }
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
	 * collects serveral variables/data to build the data-array
	 */
	private function getData() {
		$this->load->model( 'checkout/order' );
		$this->load->model( 'payment/directebanking' );

		$this->data['testMode']	= $this->_param['testMode'];
		$this->data['continue']	= $this->buildUrl( 'checkout/success' );

		// only for OC 1.4.x
		if( $this->request->get['route'] != 'checkout/guest_step_3' ) {
			$this->data['back'] = $this->buildUrl( 'checkout/payment' );
		}else{
			$this->data['back'] = $this->buildUrl( 'checkout/guest_step_2' );
		}

		// get order info
		$order_info		= $this->model_checkout_order->getOrder( $this->session->data['order_id'] );
		// get product info
		$product_info	= $this->model_payment_directebanking->getOrderProduct( $this->session->data['order_id'] );
		// get country iso2 code
		$iso2			= $this->model_payment_directebanking->getCountryIso2( $this->config->get( 'config_country_id' ) );
		// remove any currency symbol(s)
		$amount			= preg_replace( '/[^0-9.,]/', '', $order_info['total'] );

		// get form data
		$this->getFormData( $order_info, $product_info, $iso2, $amount );
		// get success / cancel URLs
		$this->getUrls();

		if(
            $this->_debug == 2
            && $this->_ocversion == '1.4'
        )
        {
			echo '<hr />formData BEFORE hash:<br />';
			print_r( $this->formData );
			echo '<hr />';
		}

		// get the hash value
		$this->getProjectHash();

		if(
            $this->_debug == 2
            && $this->_ocversion == '1.4'
        )
        {
			echo '<hr />formData AFTER hash:<br />';
			print_r( $this->formData );
			echo '<hr />';
		}

		// build the array for the form output new (we need only those values)
		$this->data['form'] = array(
			'user_id'				=> $this->formData['user_id'],
			'project_id'			=> $this->formData['project_id'],
			'amount'				=> $this->formData['amount'],

			'reason_1'				=> $this->formData['reason_1'],
			'reason_2'				=> $this->formData['reason_2'],
			'user_variable_0'		=> $this->formData['user_variable_0'],
			'user_variable_1'		=> $this->formData['user_variable_1'],
			'user_variable_2'		=> $this->formData['user_variable_2'],
			'user_variable_3'		=> $this->formData['user_variable_3'],
			'user_variable_4'		=> htmlspecialchars( $this->formData['user_variable_4'], ENT_QUOTES, 'UTF-8' ),
			'user_variable_5'		=> $this->formData['user_variable_5'],

			'sender_holder'			=> htmlspecialchars( $this->formData['sender_holder'], ENT_QUOTES, 'UTF-8' ),
			'sender_account_number'	=> $this->formData['sender_account_number'],
			'sender_bank_code'		=> $this->formData['sender_bank_code'],
			'sender_country_id'		=> $this->formData['sender_country_id'],

			'language_id'			=> $this->formData['language_id'],
			'currency_id'			=> $this->formData['currency_id'],
			'hash'					=> $this->formData['hash']
		);

		// get possible text to display
		if( $this->config->get( 'directebanking_title_as_text') ) {
			switch( $this->config->get( 'directebanking_title_as_text') ) {
				case '2':
					$this->data['instruction'] = $this->language->get( 'text_title_w_image' );
					break;

				default:
				case '1':
					$this->data['instruction'] = $this->language->get( 'text_title' );
					break;
			}
		}else{
			$this->data['instruction'] = html_entity_decode(
				$this->config->get( 'directebanking_instruction_' . $this->config->get( 'config_language_id' ) ),
				ENT_QUOTES,
				'utf-8'
			);
		}

		$this->data['instruction'] .= ( $this->_param['testMode']
				? '<br />' . $this->language->get( 'text_testOrder' )
				: ''
			);

		// write log
		$comment	= $this->_param['testMode']
						? $this->language->get( 'text_log_testorder' )
						: $this->language->get( 'text_log_order' );
		$msg		= sprintf(
						$this->language->get( 'text_log_new_order' ),
						$this->formData['project_id'],
						$order_info['order_id'],
						$comment,
						$product_info['name'],
						$order_info['lastname'],
						$amount
						);

		$this->writeLog( $msg, 1 );
	}

	/**
	 * get template data
	 */
	private function getTemplate( $tpl = '' ) {
		if( !$tpl ) {
			$tpl = '/template/' . $this->_type .'/' . $this->_name . '.tpl';
		}

		if( file_exists( DIR_TEMPLATE . $this->config->get( 'config_template' ) . $tpl ) ) {
			$this->template = $this->config->get( 'config_template' ) . $tpl;
		}else{
			$this->template = 'default' . $tpl;
		}
	}

	/**
	 * constructs the footer
	 *
	 * Note: displaying this footer is mandatory, removing violates the license!
	 * If you do not want to display the footer, contact the author.
	 */
	private function getFooter() {
		$this->data['oxfooter']	= "\n"
		. '<!-- Module ' . ucfirst( $this->_name )
		.' v.'. $this->_version . ' by http://osworx.net (c) '
		. date('Y')
		. ' -->' . "\n";
	}

	/**
	 * **** module specific functions
	 *  - do not touch unless you know what you are doing !!!
	 * ****
	 */

	/**
	 * function is called directly from directebanking.com
	 * if the transaction was successfully and the customer comes back
	 * NOTE: the verification of the transaction is made in the function verify()
	 * which is called automatically by directebanking.com
	 */
	public function success() {
		$this->getBasics();
		$this->getParams();
		$this->getLanguage();

		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		// get order id
		if( isset( $this->request->get['order_id'] ) ) {
			$order_id = $encryption->decrypt( $this->request->get['order_id'] );
		}else{
			$order_id = 0;
		}

		// get project id
		$project_id = $this->getRequest( 'pid', 0 );

		// write log
		$msg = sprintf( $this->language->get( 'text_log_return_success' ), $project_id, $order_id );
		$this->writeLog( $msg, 2 );

		// call the template
		if( $this->request->get['security_criteria'] != 1 ) {
			// something went wrong
			// write log and msg to directebanking
			$msg = sprintf( $this->language->get( 'text_log_security_invalid'), $project_id, $order_id );
			echo $msg . "\n";

			$this->writeLog( $msg, 3 );

			$this->data['heading_title']	= $this->language->get( 'text_failed' );
			$this->data['text_message']		= sprintf(
				$this->language->get( 'text_failed_message' ),
				$this->buildUrl( 'information/contact' )
			);
			$this->data['button_continue']	= $this->language->get( 'button_continue' );
			$this->data['continue']			= $this->buildUrl( 'common/home' );

			$this->getTemplate( '/template/common/success.tpl' );

			$this->getChildren();
			$this->buildResponse();
		}else{
			// everything okay
			$this->redirect( $this->buildUrl( 'checkout/success' ) );
		}
	}

	/**
	 * this function is called directly from directebanking.com
	 * if the transaction was cancelled by the customer
	 */
	public function cancel(){
		$this->getBasics();
		$this->getParams();
		$this->getLanguage();

		// load additonal language (for breadcrumbs)
		$this->language->load( 'checkout/success' );

		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		// get order id
		if( isset( $this->request->get['order_id'] ) ) {
			$order_id = $encryption->decrypt( $this->request->get['order_id'] );
		}else{
			$order_id = 0;
		}

		// get project id
		$project_id = $this->getRequest( 'pid', 0 );

		// write log
		$msg = sprintf( $this->language->get( 'text_log_return_cancel'), $project_id, $order_id );
		$this->writeLog( $msg, 4 );

		$this->data['heading_title']	= $this->language->get( 'text_cancel' );
		$this->data['text_message']		= sprintf(
			$this->language->get( 'text_cancel_message' ),
			$this->buildUrl( 'information/contact' )
		);
		$this->data['button_continue']	= $this->language->get( 'button_continue' );
		$this->data['continue']			= $this->buildUrl( 'common/home' );

		if( $this->_ocversion == '1.4' ) {
			$breadcrumbs = array(
				array(
		        	'href'      => $this->buildUrl( 'common/home' ),
		        	'text'      => $this->language->get( 'text_home' ),
		        	'separator' => false
		      	),
		      	array(
		        	'href'      => $this->buildUrl( 'checkout/cart' ),
		        	'text'      => $this->language->get( 'text_basket' ),
		        	'separator' => $this->language->get( 'text_separator' )
		      	)
			);

			if( $this->customer->isLogged() ) {
				$breadcrumbs_add = array(
					array(
						'href'      => $this->buildUrl( 'checkout/shipping' ),
						'text'      => $this->language->get( 'text_shipping' ),
						'separator' => $this->language->get( 'text_separator' )
					),
					array(
						'href'      => $this->buildUrl( 'checkout/payment' ),
						'text'      => $this->language->get( 'text_payment' ),
						'separator' => $this->language->get( 'text_separator' )
					),
					array(
						'href'      => $this->buildUrl( 'checkout/confirm' ),
						'text'      => $this->language->get( 'text_failed' ),
						'separator' => $this->language->get( 'text_separator' )
					)
				);
			}else{
				$breadcrumbs_add = array(
					array(
						'href'      => $this->buildUrl( 'checkout/guest' ),
						'text'      => $this->language->get( 'text_guest' ),
						'separator' => $this->language->get( 'text_separator' )
					),
					array(
						'href'      => $this->buildUrl( 'checkout/guest/confirm' ),
						'text'      => $this->language->get( 'text_failed' ),
						'separator' => $this->language->get( 'text_separator' )
					)
				);
			}

			$breadcrumbs = array_merge( $breadcrumbs, $breadcrumbs_add );

	      	$breadcrumbs[] = array(
	        	'href'      => $this->buildUrl( 'checkout/success' ),
	        	'text'      => $this->language->get( 'text_failed' ),
	        	'separator' => $this->language->get( 'text_separator' )
	      	);
		}else{
			$breadcrumbs = array(
				array(
					'href'      => $this->buildUrl( 'common/home' ),
		        	'text'      => $this->language->get( 'text_home' ),
		        	'separator' => false
				),
	        	array(
		        	'href'      => $this->buildUrl( 'checkout/cart' ),
		        	'text'      => $this->language->get( 'text_basket' ),
		        	'separator' => $this->language->get( 'text_separator' )
		      	),
		      	array(
					'href'      => $this->buildUrl( 'checkout/checkout', '', 'SSL' ),
					'text'      => $this->language->get( 'text_checkout' ),
					'separator' => $this->language->get( 'text_separator' )
				),
				array(
		        	'href'      => $this->buildUrl( 'checkout/success' ),
		        	'text'      => $this->language->get( 'text_failed' ),
		        	'separator' => $this->language->get( 'text_separator' )
		      	)
	      	);
		}

		$this->getTemplate( '/template/common/success.tpl' );
		$this->buildBreadcrumbs( $breadcrumbs );
		$this->buildTitle( $this->language->get( 'text_cancel' ) );
		$this->getChildren();
		$this->buildResponse();
	}

	/**
	 * verifies the values directebanking.com sent via post
	 * note: echos are only visible at directebanking -> reports
	 */
	public function verify() {
		$this->getBasics();
		$this->getParams();
		$this->getLanguage();

		$this->load->model( 'checkout/order' );
		$this->load->library( 'encryption' );

		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

		$forbidden			= array( 'route', 'hash', 'email_sender', 'email_recipient' );
		$retData			= array();
		$securityCriteria	= 0; // integer!
		$project_id			= '0';
		$err				= false;

		// filter variables
		foreach( $this->request->post as $key => $value) {
			if( !in_array( $key, $forbidden ) ) {
				$retData[$key] = html_entity_decode( $value, ENT_QUOTES, 'UTF-8' );
			}

			// get value of security_criteria
			if( $key == 'security_criteria' ) {
				$securityCriteria = $value;
			}

			// decrypt order_id
			if( $key == 'user_variable_3' ) {
				$order_id = $encryption->decrypt( $value );
			}

			// get hash value
			if( $key == 'hash' ) {
				$this->hashValue = $value;
			}

			// get project id
			if( $key == 'project_id' ) {
				$project_id = $value;
			}
		}

		if( $this->_debug == 1 ) {
			echo '## FUNCTION verify' . "\n";
			echo '## calling getNotifyHash:' . "\n";
		}

		// calculate hash value
		$hash = $this->getNotifyHash( $retData );

		if( $this->_debug == 1 ) {
			echo "\n" . '## $_POST data from directebanking:' . "\n";
			print_r( $_POST ) . "\n";
			echo 'retData (cleaned POST for calculating hash):' . "\n";
			print_r( $retData );
			echo "\n";

			echo '--> submitted hash [' . $this->hashValue . ']' . "\n";
			echo '--> calculated hash [' . $hash . ']' . "\n";
			echo '--> securityCriteria [' . ( $securityCriteria ? 'true' : 'false' ) . ']' . "\n";

			// write also log entry
			$msg = '## $_POST data from directebanking:<br />'
			. print_r( $_POST, true )
			. '<br />retData (cleaned POST for calculating hash):<br />'
			. print_r( $retData, true )
			. '<br />--> submitted hash [' . $this->hashValue . ']<br />'
			. '--> calculated hash [' . $hash . ']<br />'
			. '--> securityCriteria [' . ( $securityCriteria ? 'true' : 'false' ) . ']<br />'
			. '~~~~~~~~~~~~~~~~~~~~~~';

			$this->writeLog( $msg );
		}

		$comment = $this->_param['testMode'] ? $this->language->get( 'text_testOrder') : '';

		// check generated and submitted hash values
		if( $hash === $this->hashValue ) {
			if( $securityCriteria == 1 && $order_id ) {
				// order is okay, set order to predefined directebanking status
				$this->model_checkout_order->confirm(
					$order_id,
					$this->config->get( 'directebanking_order_status_id'),
					$comment
				);

				$msgDb	= sprintf( $this->language->get( 'text_log_hash_okay'), $project_id );
				$msg	= sprintf( $this->language->get( 'text_log_valid'), $project_id, $order_id );
				$type	= 2;
			}else{
				// order is not okay, set order to predefined config status
				$this->model_checkout_order->confirm(
					$order_id,
					$this->config->get( 'config_order_status_id' ),
					$comment
				);

				$msgDb	= sprintf( $this->language->get( 'text_log_security_invalid'), $project_id, $order_id );
				$msg	= $msgDb;
				$type	= 3;
			}
		}else{
			// order is not okay, set order to predefined config status
			$this->model_checkout_order->confirm( $order_id, $this->config->get( 'config_order_status_id' ), $comment );

			$msgDb	= sprintf( $this->language->get( 'text_log_hash_notokay'), $project_id );
			$msg	= sprintf( $this->language->get( 'text_log_hash_dif'), $project_id, $order_id );
			$type	= 3;
		}

		// write log
		$this->writeLog( $msg, $type );
		// echo message at directebanking
		if( $this->_debug == 1 ) {
			echo $msgDb . "\n";
		}
	}

	/**
	 * get data for the form
	 *
	 * FIX values				: order_id, store_id, store_url, customer_id, shipping_iso_code_2, total, currency, date_added
	 * OPTIONAL values			: email, order_status_id
	 * FOR directebanking only	: sender_country_id, language_id
	 *
	 * NOTE: the ordering of the values shall NOT be rearranged!
	 * The hash function needs exactly this ordering:
	 * user_id|project_id|sender_holder|sender_account_number|sender_bank_code|sender_country_id|amount|currency_id|reason_1|reason_2|user_variable_0|user_variable_1|user_variable_2|user_variable_3|user_variable_4|user_variable_5|project_password
	 * @param array		$order_info		values about this order
	 * @param array		$product_info	values about the product
	 * @param string	$iso2			country code
	 * @param string	$amount			order amount
	 * @see function getProjectHash()
	 */
	private function getFormData( $order_info, $product_info, $iso2, $amount ) {
		$this->load->library( 'encryption' );
		$encryption = new Encryption( $this->config->get('config_encryption') );

		$this->formData = array();
		$productName	= html_entity_decode( $product_info['name'], ENT_QUOTES, 'UTF-8' );
		$productModel	= html_entity_decode( $product_info['model'], ENT_QUOTES, 'UTF-8' );

		// OC 1.4.x backward compatibility (currency_code & currency_value is 1.5)
		if( isset( $order_info['currency_code'] ) ) {
			$order_info['currency'] = $order_info['currency_code'];
            $order_info['value']    = $order_info['currency_value'];
		}

		// only for OC 1.4.x
		if( $this->request->get['route'] != 'checkout/guest_step_3' ) {
			$this->data['back'] = $this->buildUrl( 'checkout/payment' );
		}else{
			$this->data['back'] = $this->buildUrl( 'checkout/guest_step_2' );
		}

		// check supported currency [EUR,CHF,GBP,PLN] (as of 2012.09)
		$supported_currencies = array( 'EUR', 'CHF', 'GBP', 'PLN' );
		if( in_array( $order_info['currency'], $supported_currencies ) ) {
            $currency   = $order_info['currency'];
            $amount     = number_format(
                $order_info['total'] * $order_info['value'],
                2,
                ',',
                ''
            );
		}else{
            $currency   = 'EUR';
            $amount     = number_format(
                $this->currency->convert(
                    $order_info['total'] * $order_info['value'],
                    $order_info['currency'],
                    'EUR'
                ),
                2,
                ',',
                ''
            );

            // write log that we have to convert unsupported currency
            $msg = sprintf(
                $this->language->get( 'text_log_unsupported_currency' ),
                $order_info['currency'],
                number_format(
                    $order_info['total'] * $order_info['value'],
                    2
                ),
                $amount
            );
            $this->writeLog( $msg, 3 );
		}

		// build data arrays to merge afterwards
		$varData1 = array(
			'user_id'		=> $this->_param['custId'],
			'project_id'	=> $this->_param['projId']
		);

		switch( $this->_param['testMode'] ) {
			case '1':
				// test de
				$varData2 = array(
					'sender_holder'			=> 'Jürgen Mustermann',
					'sender_account_number'	=> '112233',
					'sender_bank_code'		=> '88888888',
					'sender_country_id'		=> 'DE',
					'amount'				=> $amount,
					'currency_id'			=> 'EUR',
					'reason_1'				=> sprintf(
												$this->language->get( 'text_test_reason_1' ),
												$order_info['order_id']
												),
					'reason_2'				=> sprintf(
												$this->language->get( 'text_reason_2' ),
												$order_info['date_added']
												)
				);
				break;

			case '2':
				// test at,be,ch,nl,uk
				$varData2 = array(
					'sender_holder'			=> 'Jürgen Mustermann',
					'sender_account_number'	=> '12345678',
					'sender_bank_code'		=> '00000',
					'sender_country_id'		=> 'AT',
					'amount'				=> $amount,
					'currency_id'			=> 'EUR',
					'reason_1'				=> sprintf(
												$this->language->get( 'text_test_reason_1' ),
												$order_info['order_id']
												),
					'reason_2'				=> sprintf(
												$this->language->get( 'text_reason_2' ),
												$order_info['date_added']
												)
				);
				break;

			case '0':
			default:
				// live
				$varData2 = array(
					'sender_holder'			=> '',
					'sender_account_number'	=> '',
					'sender_bank_code'		=> '',
					'sender_country_id'		=> $iso2,
					'amount'				=> $amount,
					'currency_id'			=> $currency,
					'reason_1'				=> sprintf(
												$this->language->get( 'text_reason_1' ),
												$order_info['order_id']
												),
					'reason_2'				=> sprintf(
												$this->language->get( 'text_reason_2' ),
												$order_info['date_added']
												)
				);
				break;
		}

		$varData3 = array(
			'user_variable_0'		=> '',
			'user_variable_1'		=> '',
			'user_variable_2'		=> 'null',
			'user_variable_3'		=> $encryption->encrypt( $order_info['order_id'] ),
			'user_variable_4'		=> $productName .' - '. $productModel,
			'user_variable_5'		=> 'null',
			'projectPassword'		=> $this->_param['projectPassword'],
			'language_id'			=> strtoupper( $this->config->get( 'config_language' ) )
		);

		$this->formData = array_merge( $varData1, $varData2, $varData3 );
		unset( $varData1, $varData2, $varData3 );
	}

	/**
	 * builts the success and cancel URLs for using in the forum
	 */
	private function getUrls() {
		// reassign the array because of a php bug (solved later with php 5.2.x @see: http://bugs.php.net/39449)
		$arr = $this->formData;

		// build bas URL for replacement variables
		$protocol	= 'http'
                    . ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ? 's' : '' )
                    . '://';
		$url		= $_SERVER['HTTP_HOST'] . dirname( $_SERVER['PHP_SELF'] ) . '/';
        $url        = str_replace( '//', '/', $url ); // some server add 2 /
        $url        = $protocol . $url . 'index.php?route=payment/directebanking/';

		if( $this->_param['successUrlStd'] ) {
			$arr['user_variable_0'] = $url . 'success'
			. '&transaction=-TRANSACTION-&security_criteria=-SECURITY_CRITERIA-'
			. '&order_id=-USER_VARIABLE_3-&pid=-PROJECT_ID-';
		}else{
			if( $this->_param['successUrl'] ) {
				$arr['user_variable_0'] = $this->_param['successUrl'];
			}
		}

		if( $this->_param['cancelUrlStd'] ) {
			$arr['user_variable_1'] = $url . 'cancel'
			. '&transaction=-TRANSACTION-&order_id=-USER_VARIABLE_3-&pid=-PROJECT_ID-';
		}else{
			if( $this->_param['cancelUrl'] ) {
				$arr['user_variable_1'] = $this->_param['cancelUrl'];
			}
		}

		// and reassign again
		$this->formData = $arr;

		unset( $arr );
	}

	/**
	 * builds the hash value for submitting with the form
	 * all values are used, except language_id which will be eliminated
	 *
	 * NOTE: building the hash value expects the variables given in this order (seperated each by a pipe):
	 * user_id|project_id|sender_holder|sender_account_number|sender_bank_code|sender_country_id|amount|currency_id|reason_1|reason_2|user_variable_0|user_variable_1|user_variable_2|user_variable_3|user_variable_4|user_variable_5|project_password
	 * @see function getFormData()
	 */
	public function getProjectHash() {
		$hash	= '';

		if( $this->_param['useHash'] ) {
			$string = '';
			$vals	= array();

			// build an array with values only - all values except 'language_id''
			foreach( $this->formData as $key => $value ) {
				if( $key != 'language_id' ) {
					$vals[] = $value;
				}
			}

			$string	= implode( '|', $vals );
			$hash	= $this->getHash( $string );

			if( $this->_debug == 1 ) {
				$msg = 'values for hash calculation:<br />'
				. 'values as array:<br />'
				. print_r( $vals, true )
				. '<hr />'
				. 'values as string:<br />'
				. $string
				. '<hr />'
				. 'hash value [' . $hash . ']';

				$this->writeLog( $msg, 5 );
			}
		}

		// add the hash value to the form datas
		$this->formData = array_merge( $this->formData, array( 'hash' => $hash ) );
	}

	/**
	 * checks the incoming variables recieved directly from directebanking.com
	 * and build the hash
	 */
	private function getNotifyHash( $data ) {
		if( $this->_param['useHash'] ) {
			// add notification password to array
			$data['notification_password'] = $this->_param['notifyPassword'];

			$string	= implode( '|', $data );

			if( $this->_debug == 1 ) {
				echo "\n" . '## function getNotifyHash - complete array for calculation:' . "\n";
				print_r( $data );
				echo "\n" . 'same as string:' . "\n";
				echo $string;
			}

			$hash = $this->getHash( $string, true );
		}else{
			$hash = $this->hashValue;
		}

		return $hash;
	}

	/**
	 * build the hash (project & notify)
	 * @param string	$val	string with all values
	 * @param bool		$log	if values came back to verify, show messages
	 * @return string
	 */
	private function getHash( $val, $log = false ) {
		$ret = '';

		if( $this->_debug == 1 && $log ) {
			echo "\n" . '## using hash method [' . $this->_param['hashMethod'] . ']' . "\n";
			echo "\n" . 'values to calculate:' . "\n";
			echo $val;
			echo "\n";
		}

		// clean value from &amp;
		$val = str_replace( '&amp;', '&', $val );
		// calculate hash
		$ret = hash( $this->_param['hashMethod'], $val );

		if( $this->_debug == 1 && $log ) {
			echo "\n" . 'generated hash:' . "\n";
			echo $ret;
			echo "\n";
		}

		return $ret;
	}
}