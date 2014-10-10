<?php
/**
 * @version		$Id: oxsupporter.php 3216 2013-04-14 14:30:00Z mic $
 * @package		Support
 * @author		mic - http://osworx.net
 * @copyright	2013 OSWorX - http://osworx.net
 * @license		OCL OSWorX Commercial - http://osworx.net
 *
 * note: called only from backend!
 */

class ControllerModuleOXSupporter extends Controller
{
    protected $_obj;

    private static $instance;
    private $_version = '1.0.4';

    /**
     * singleton method used to access the object
     * @access public
     * @return
     */
    public static function getInstance( $object ) {
        if( !isset( self::$instance ) ) {
            $obj = __CLASS__;
            self::$instance = new $obj( $object );
        }

        return self::$instance;
    }

    /**
     * constructor
     * @access public via getInstance!
     */
    public function __construct( $object ) {
        $this->_obj = $object;
    }

	/**
     * build support links
	 */
    public function _getSupportLinks() {
        // compatibility for older extensions
        if( empty( $this->_obj->data['links'] ) ) {
            $this->_obj->data['links'] = array();
        }

        $vars = array(
            'checkVersion'  => 'index.php?route='
                . $this->_obj->_type .'/'. $this->_obj->_name
                . '/checkVersion' . '&token=' . $this->_obj->session->data['token'],
            'isValidUntil'  => 'index.php?route='
                . $this->_obj->_type .'/'. $this->_obj->_name
                . '/isValidUntil' . '&token=' . $this->_obj->session->data['token'],
            'updateNow'     => 'index.php?route='
                . $this->_obj->_type .'/'. $this->_obj->_name
                . '/updateNow' . '&token=' . $this->_obj->session->data['token']
        );

        $this->_obj->data['links'] = array_merge( $this->_obj->data['links'], $vars );

        unset( $vars );
    }

    /**
     * load lang.vars for support only
     */
    public function _getSupportLangVars() {
        $this->_obj->language->load( 'module/oxsupporter' );
        $vars = array(
            'tab_support',
            'text_version', 'text_website', 'text_support', 'text_license',
            'text_supportkey',
			'text_updating_system', 'text_checking_version', 'text_checking_date',
            'text_get_key', 'text_update_existing',
            'btn_check_version', 'btn_valid_until', 'btn_update_now'
        );

        foreach( $vars as $var ) {
            $this->_obj->data[$var] = $this->_obj->language->get( $var );
        }

        $this->_obj->data['help_update'] = sprintf(
            $this->_obj->language->get( 'help_update' ),
            date( 'Ymd_His' )
        );

        unset( $vars );
    }

    /**
     * add css & js to common document
     */
    public function _addSupportDocumentData() {
        $this->_obj->document->addStyle( 'view/stylesheet/support.css' );
    }

    /**
     * build additional data and add to global scope
     * @param bool  $supported
     */
    public function _setSupportTemplateData( $supported ) {
        if( $supported ) {
            $this->_obj->data['version']      = $this->_obj->_version;
            $this->_obj->data['isSupported']  = ( $this->_obj->data[$this->_obj->_name . '_supportKey'] ? true : false );

            if( $this->_obj->data['isSupported'] ) {
                $this->_obj->data['support']['js'] = 'require_once( DIR_SYSTEM . \'osworx/templates/support/oxsupport_java.php\' );';
            }else{
                $this->_obj->data['support']['js'] = '';
            }

            // needed for compatibilty OC 1.4.
            if( empty( $this->_obj->data['tab'] ) ) {
                $this->_obj->data['tab'] = 'href';
            }

            $this->_obj->data['support']['tab'] = '<a ' . $this->_obj->data['tab'] . '="#tab-support">' . $this->_obj->language->get( 'tab_support' ) . '</a>';
            $this->_obj->data['support']['tpl'] = 'require_once( DIR_SYSTEM . \'osworx/templates/support/oxsupport_tab.php\' );';
        }else{
            $this->_obj->data['support'] = array(
                'tab'   => '',
                'tpl'   => '',
                'js'    => ''
            );
        }
    }

    /**
     * get current extension version
     * @return mixed
     */
    public function _getVersion() {
        require_once( DIR_SYSTEM . 'osworx/libraries/tools/support.php' );
        $ret = OXSupport::getVersion(
            $this->_obj->_name,
            $this->_obj->_version,
            $this->_obj->config->get( $this->_obj->_name . '_supportKey'),
            $this->getLang()
        );

        return $ret;
    }

    /**
     * get current extension version
     * used for older extensions (compatibility)
     * @return mixed
     */
    public function _getVersionOnly() {
        require_once( DIR_SYSTEM . 'osworx/libraries/tools/support.php' );
        $ret = OXSupport::getVersionOnly(
            $this->_obj->_name,
            $this->_obj->_version,
            $this->_obj->config->get( $this->_obj->_name . '_supportKey'),
            $this->getLang()
        );

        return $ret;
    }

    /**
	 * check installed version vs current published
     * @return array
	 */
	public function _checkVersion() {
        $this->_obj->language->load( 'module/oxsupporter' );
        $err        = false;
        $current    = false;

        if( $this->_obj->config->get( $this->_obj->_name . '_supportKey') ) {
    		require_once( DIR_SYSTEM . 'osworx/libraries/tools/support.php' );

            $result     = $this->_getVersion();
            $display    = $this->translateResult( $result );

        }else{
            $err = true;
        }

        // if call was made by template
        if( !empty( $this->_obj->request->post['json'] ) ) {
            if( $err ) {
                $json = array(
                    'error'     => $this->_obj->language->get( 'text_no_supportkey' )
                );
            }else{
                $json = array(
                    'success'   => $this->_obj->language->get( 'text_version_checked_success' ),
                    'version'   => ( $result->dif ? $result->cVer : '' ),
                    'changelog' => $display['changelog'],
                    'class'     => $display['class']
                );
            }

            $this->_obj->response->setOutput( json_encode( $json ) );
        }
	}

    /**
     * get date for valid support
     * @return array
     */
    public function _isValidUntil() {
        $this->_obj->language->load( 'module/oxsupporter' );
        $err = false;
        $ret = false;

        if( $this->_obj->config->get( $this->_obj->_name . '_supportKey') ) {
    		require_once( DIR_SYSTEM . 'osworx/libraries/tools/support.php' );

    		$ret = OXSupport::isValidUntil(
                $this->_obj->_name,
                $this->_obj->_version,
                $this->_obj->config->get( $this->_obj->_name . '_supportKey' ),
                $this->getLang()
            );
        }else{
            $err = true;
        }

        // if call was made by template
        if( !empty( $this->_obj->request->post['json'] ) ) {
            if(
                $err
                || !$ret
            )
            {
                $json = array(
                    'error'     => $this->_obj->language->get( 'text_no_supportkey' )
                );
            }else{
                $json = array(
                    'success'   => $this->_obj->language->get( 'text_date_checked_success' ),
                    'date'      => $this->_obj->language->get( 'text_support_valid_until' ) . ': ' . $ret
                );
            }

            $this->_obj->response->setOutput( json_encode( $json ) );
        }
    }

    /**
     * update current extension
     * @return array
     */
    public function _updateNow() {
        $this->_obj->language->load( 'module/oxsupporter' );
        $error      = true;
        $err        = false;
        $success    = false;

        if( $this->_obj->config->get( $this->_obj->_name . '_supportKey' ) ) {
    		require_once( DIR_SYSTEM . 'osworx/libraries/tools/support.php' );

    		if( $ret = OXSupport::updateNow(
                    $this->_obj->_name,
                    $this->_obj->_version,
                    $this->_obj->config->get( $this->_obj->_name . '_supportKey'),
                    $this->_obj->request->post['backup']
                )
            )
            {
                $success    = true;
                $error      = false;
            }
        }else{
            $err = true;
        }

        if( !empty( $this->_obj->request->post['json'] ) ) {
            if( $err ) {
                $json = array(
                    'error'     => $this->_obj->language->get( 'text_no_supportkey' )
                );
            }else{
                if( $success ) {
                    $json = array(
                        'success'   => sprintf(
                            $this->_obj->language->get( 'text_update_successful' ),
                            $ret['statistic']['folders'],
                            $ret['statistic']['files']
                        ),
                        'version'   => $ret['version'],
                        'debug'     => $ret['debug']
                    );
                }else{
                    $json = array(
                        'error'     => $this->_obj->language->get( 'text_update_error' )
                    );
                }
            }

            $this->_obj->response->setOutput( json_encode( $json ) );
        }
    }

    /**
     * get used (or valid) language
     * @return string
     */
    private function getLang() {
        $allowedLangs   = array( 'de', 'en' );
        $lang           = 'en';
        $changelog      = '';

		if( in_array( $this->_obj->language->get( 'code' ), $allowedLangs ) ) {
            $lang = $this->_obj->language->get( 'code' );
		}

        return $lang;
    }

    /**
     * translate result into readable output
     * @param object    $result
     * @return array
     */
    public function translateResult( $result, $versionOnly = false ) {
        $ret = array();
        switch( $result->dif )
        {
            case 1:
            default:
                $ret['class']      = 'orange';
                $ret['changelog']  = $this->_obj->language->get( 'text_unknown_version' );
                break;

            case -1:
                $ret['class']      = 'red';
                $ret['changelog']  = $this->_obj->language->get( 'text_no_info_available' );

                if( $result->cLog ) {
                    $ret['changelog'] = '<span class="green bold">'
                        . $this->_obj->language->get( 'text_new_version_available' )
                    . '</span>'
                    . '<br />'
                    . nl2br( $result->cLog );
                }
                break;

            case 0:
            default:
                $ret['class']      = 'green';
                $ret['changelog']  = $this->_obj->language->get( 'text_version_is_current' );
                break;
        }

        if( $versionOnly ) {
            $ret['changelog'] = '';
        }

        return $ret;
    }
}