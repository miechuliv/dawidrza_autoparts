<?php

namespace SMSApi\Api\Response;

class ContactsResponse extends CountableResponse {

	private $list;

	function __construct( $data ) {
		parent::__construct( $data );

		$this->list = new \ArrayObject();

		if ( isset( $this->obj->list ) ) {
			foreach ( $this->obj->list as $res ) {
				$this->list->append( new ContactResponse( $res ) );
			}
		}
	}

	public function getList() {
		return $this->list;
	}

}
