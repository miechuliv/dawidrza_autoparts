<?php

namespace SMSApi\Api\Response;

class SendersResponse extends AbstractResponse {

	private $list;

	function __construct( $data ) {
		parent::__construct( $data );

		$this->list = new \ArrayObject();

		if ( isset( $this->obj ) ) {
			foreach ( $this->obj as $res ) {
				$this->list->append( new SenderResponse( $res ) );
			}
		}
	}

	public function getList() {
		return $this->list;
	}

}
