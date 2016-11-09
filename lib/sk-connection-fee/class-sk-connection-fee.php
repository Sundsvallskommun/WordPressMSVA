<?php

require_once 'class-sk-connection-fee-shortcode.php';
require_once 'class-sk-connection-fee-ajax.php';

class SK_Connection_Fee {

	protected $shortcode;

	public function __construct() {

		$shortcode = new SK_Connection_Fee_Shortcode();
		$ajax = new SK_Connection_Fee_Ajax();

	}

}