<?php
/**
 * @version		$Id: directebanking.php 3008 2012-11-30 17:41:50Z mic $
 * @package		Directebanking - Frontend model
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

class ModelPaymentDirectebanking extends Model
{
	private $_type		= 'payment';
	private $_name		= 'directebanking';
	private $_version	= '1.0.15';

  	public function getMethod( $address, $total = null ) {
  		$this->load->language( $this->_type .'/'. $this->_name );

  		$status			= false;
		$method_data	= array();

		if( $this->config->get( $this->_name . '_status' ) ) {
			$query = '
            SELECT
                *
			FROM `' . DB_PREFIX . 'zone_to_geo_zone`
			WHERE geo_zone_id = \'' . (int) $this->config->get( $this->_name . '_geo_zone_id' ) . '\'
			AND country_id = \'' . (int) $address['country_id'] . '\'
			AND ( zone_id = \'' . (int) $address['zone_id'] . '\' OR zone_id = \'0\' )';

      		$result = $this->db->query( $query );

			if( !$this->config->get( $this->_name . '_geo_zone_id' ) ) {
        		$status = true;
      		}elseif( $result->num_rows ) {
      		  	$status = true;
      		}else{
     	  		$status = false;
			}
      	}else{
			$status = false;
		}

		// check for customer group
		if( $this->config->get( $this->_name . '_customer_groups' ) ) {
			$customer_groups = unserialize(
				$this->config->get( $this->_name . '_customer_groups' )
			);
			$customerGroupId = $this->customer->getCustomerGroupId();

			// if all customer groups are allowed (-1), no check is needed
			// otherwise check
			if( !in_array( '-1', $customer_groups ) ) {
				if( !$customerGroupId ) {
					// not set - it is a guest
					$customerGroupId = '-1';
				}

				if( false === array_search( $customerGroupId, $customer_groups ) ) {
					$status = false;

				}
			}
		}

		if( $status ) {
			$title	= $this->language->get( 'text_title' );
			$text	= '';

			if( $this->config->get( $this->_name . '_title_' . $this->config->get( 'config_language_id' ) ) ) {
				$title = html_entity_decode(
					$this->config->get( $this->_name . '_title_' . $this->config->get( 'config_language_id' ) ),
					ENT_QUOTES,
					'utf-8'
				);
			}

			/**
			 * mic - note: this is currently not used because OpenCart does NOT
			 * the following tag: text
			 * so we prepare it only for a possible usage
			 */
			if( $this->config->get( $this->_name . '_instruction_' . $this->config->get( 'config_language_id' ) ) ) {
				$text = html_entity_decode(
					$this->config->get( $this->_name . '_instruction_' . $this->config->get( 'config_language_id' ) ),
					ENT_QUOTES,
					'utf-8'
				);
			}

      		$method_data = array(
      			// OC 14x
      			'id'         	=> $this->_name,
      			// OC 15x
        		'code'			=> $this->_name,
        		'title'			=> $title,
				'sort_order'	=> $this->config->get( $this->_name . '_sort_order'),
				'text'			=> $text
      		);
    	}

    	return $method_data;
  	}

	/**
	 * get name and model from order
     * check if values are set, if not set empty string
	 */
  	public function getOrderProduct( $order_id ) {
        $ret = array();

  		$query = '
        SELECT
            name, model
  		FROM `' . DB_PREFIX . 'order_product`
  		WHERE order_id = \'' . (int) $order_id . '\'';

  		$result = $this->db->query( $query );

        if( $result->num_rows ) {
            foreach( $result->row as $k => $v) {
                if( !$v ) {
                    $ret[$k] = '';
                }else{
                    $ret[$k] = $v;
                }
            }
        }else{
            $ret = array(
                'name'  => '',
                'model' => ''
            );
        }

  		return $ret;
  	}

  	/**
  	 * get country iso_Code_2
  	 */
  	public function getCountryIso2( $id ) {
  		$query = '
        SELECT
            iso_code_2
  		FROM `' . DB_PREFIX . 'country`
  		WHERE country_id = \'' . (int) $id . '\'';

  		$result = $this->db->query( $query );

  		return $result->row['iso_code_2'];
  	}
}