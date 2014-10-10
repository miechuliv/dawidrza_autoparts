<?php
/**
 * @version		$Id: controllerCompatibility.php 3221 2013-04-14 14:41:22Z mic $
 * @package		OCIE
 * @author		mic - http://osworx.net
 * @copyright	2011-2013 OSWorX - http://osworx.net
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 * description: independent class for compatibility
 * to work with this class, set a public var $_ocversion in the calling controller
 * afterwards call once checkOCVersion();
 * and whenever needed call one of the functions below instead the original functions
 * or call the below functions and replace the lines in the original controller
 */

class controllerCompatibility extends controller
{
	public $_ocversion;
	private $_version = '1.0.6';

    /**
     * basic check for OpenCart version
	 */
    public function checkOCVersion() {
		$this->_ocversion = '1.5';

		// OC = 1.4.x
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
	 * build a url
	 * note: OCIE handles url->link in a different way
	 * @param string	$url	the url to convert (should be only the route)
	 * @param bool		$xhtml	build XHTML valid urls
	 * @param bool		$ssl	build ssl url
	 * @param string	$args	additonal arguments (used only at OC 15x)
	 * @return	string
	 */
	public function buildUrl( $url, $xhtml = true, $ssl = true, $args = '' ) {
		if( $this->_ocversion == '1.4' ) {
			$ret = ( $ssl ? HTTPS_SERVER : HTTP_SERVER )
			. 'index.php?route=' . $url;

			if( $args ) {
				$args = '&' . trim( $args, '&' );

				$ret .= $args;
			}

			if( $xhtml ) {
				$ret = str_replace( '&amp;', '&', $ret );
				$ret = str_replace( '&&', '&', $ret );
				$ret = str_replace( '&', '&amp;', $ret );
			}
		}else{
			$ret = $this->url->link( $url, $args, ( $ssl ? 'SSL' : 'NONSSL' ) );
		}

		return $ret;
	}

	/**
	 * build breadcrumbs
	 * @param array	$data
	 */
	public function buildBreadcrumbs( $data ) {
		if( $this->_ocversion == '1.4' ) {
			$this->document->breadcrumbs	= $data;
		}else{
			$this->data['breadcrumbs']		= $data;
		}

		unset( $data );
	}

	/**
	 * build response
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
	 * build title
	 */
	public function buildTitle( $title ) {
		if( $this->_ocversion == '1.4' ) {
			$this->document->title = $title;
		}else{
			$this->document->setTitle( $title );
		}
	}

	/**
	 * build tabs
	 */
	public function setTabDef() {
		if( $this->_ocversion == '1.4' ) {
			$this->data['tab'] = 'tab';
		}else{
			$this->data['tab'] = 'href';
		}
	}

	/**
	 * build children
	 */
	public function getChildren(){
		if( $this->_ocversion == '1.4' ) {
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
		}else{
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
		}
	}

	/**
	 * encode a string
	 * @param string	$val
	 * @return string
	 */
	public function htmlEntityDecode( $val ) {
		return html_entity_decode( $val, ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * get tax rates
	 * note: with OC 1.5.2 a new tax variable (rate) is introduced
	 * to keep it simple, we take the tax[amount] and calculate if tax[type] = P(ercentage)
	 * @param string	$total			product total net amount
	 * @param string	$tax_class_id	product tax class id
	 * @return string
	 */
	public function getTaxRates( $total, $tax_class_id ) {
		$ret	= 0;
		$type	= '';
		$arr	= null;

		if( $tax_class_id ) {
			if( $this->_ocversion > '1.4' ) {
				$vals = $this->tax->getRates( $total, $tax_class_id );

				if( count( $vals ) ) {
					foreach( $vals as $k => $v ) {
						if( isset( $v['rate'] ) ) {
							// OC 1.5.2
							$ret	= $v['rate'];
						}else{
							// < OC 1.5.2
							$arr	= $v;
						}

						break;
					}
				}

				// calculate
				if( $arr ) {
					switch( $arr['type'] ) {
						case 'P':
							$ret = ( $ret / $total ) * 100;
							break;

						case 'F':
							$this->pseudoProducts[] = array(
								'tax_rate_id'	=> $arr['tax_rate_id'],
								'name'			=> $arr['name'],
								'amount'		=> $arr['amount']
							);
							break;
					}
				}
			}else{
	        	$ret = $this->tax->getRate( $tax_class_id );
	    	}
    	}

    	return $ret;
	}

	/**
	 * check order info currency and reassign if set
	 * note: used at process/confirm functions
	 * @param array	$order_info
	 * @return array
	 */
	public function checkOrderInfo( &$order_info ) {
		if( isset( $order_info['currency_code'] ) ) {
			$order_info['currency'] = $order_info['currency_code'];
		}
		if( isset( $order_info['currency_value'] ) ) {
			$order_info['value'] = $order_info['currency_value'];
		}

		return $order_info;
	}

	/**
	 * define checkout buttons
	 * needed for OC < 1.5.x
	 */
	public function setCheckoutSteps() {
		if( $this->_ocversion == '1.4' ) {
			$this->data['continue'] = $this->buildUrl( 'checkout/success' );

			if( $this->request->get['route'] != 'checkout/guest_step_3' ) {
				$this->data['back'] = $this->buildUrl( 'checkout/payment' );
			}else{
				$this->data['back'] = $this->buildUrl( 'checkout/guest_step_2' );
			}
		}
	}

	/**
	 * Json output
	 * @param array		$data
	 */
	public function getJsonOutput( $data ) {
		if( $this->_ocversion == '1.5.1.3' ) {
			$this->response->setOutput( json_encode( $data ) );
		}else{
			$this->load->library( 'json' );
			$this->response->setOutput( Json::encode( $data ) );
		}
	}

	/** ### helper functions ### **/

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
        $logLevel       = $this->config->get( $this->_name . '_log_level' );
        $serverShift    = time() + ( $this->config->get( $this->_name . '_server_shift' ) * 3600 );
        $file           = DIR_LOGS . ( $file ? $file : $this->_name );
		$logData	= '';
        $tag            = '';
        $log            = false;

		// check for older extension
		if( !$logLevel ) {
			$logLevel = 2;
		}

		switch( $type ) {
			case 1:
				$tag = '#TRANS_NEW#';
                if( $logLevel > 1 ) {
                    $log = true;
                }
				break;

			case 2:
				$tag = '#TRANS_VALID#';
                if( $logLevel > 1 ) {
                    $log = true;
                }
				break;

			case 3:
				$tag = '#ERR#';
                if( $logLevel == 1 ) {
                    $log = true;
                }
				break;

			case 4:
				$tag = '#CANCEL#';
                if( $logLevel > 1 ) {
                    $log = true;
                }
				break;

			case 5:
				$tag = '#TEST#';
                if( $logLevel ) {
                    $log = true;
                }
				break;

			case 0:
			default:
				$tag = '';
                if( $logLevel > 1 ) {
                    $log = true;
                }
				break;
		}

        if( $log ) {
            $this->load->library( 'encryption' );
    		$encryption = new Encryption( $this->config->get( 'config_encryption' ) );

            $logFile = $file .'_'. date( 'Y_m' ) . '.txt';

            // check size, because on some systems an error will be shown
            // we limit this here to 16MB (16777216 bytes )
            if(
                file_exists( $logFile )
                && filesize( $logFile ) > '16777216'
            )
            {
                $logFile = $file .'_'. date( 'Y_m_W' ) . '.txt';
            }

    		if( file_exists( $logFile ) ) {
    			$handle		= fopen( $logFile, 'rb' );
    			$logData	= file_get_contents( $logFile );
    			$logData	= $encryption->decrypt( $logData );
    			fclose( $handle );
    		}

			$logData = ( $tag ? $tag : '' )
	    		. date( 'Y-m-d H:i:s', $serverShift )
			. ' - '
			. 'IP [' . $this->getIp() . ']'
		 	. ' - '
			. str_replace( '<br />', "\n", $msg )
			. ( $tag ? '#END#' : '' )
	    		. ( $logData ? "\n\n" . $logData : '' );
	
			// encrypt data
			$logData = $encryption->encrypt( $logData );
	
	    		$handle	= fopen( $logFile, 'wb' );
			fwrite( $handle, $logData );
			fclose( $handle );
        }
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
	public function getIp( $ipv6 = true ) {
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
	 * send an email message to admin
	 * @param array	$details
	 */
	public function sendAdminEmail( $data ) {
		// convert possible <br />
		$body = str_replace( '<br />', "\n", $data['body'] );

		$headers = 'MIME-Version: 1.0' . PHP_EOL
	    . 'Content-type: text/plain; charset=utf-8' . PHP_EOL
	    . 'Content-Transfer-Encoding: 8bit' . PHP_EOL
		. 'From: ' . $data['address'];

		mail( $data['address'], $data['subject'], $body, $headers );
	}
}