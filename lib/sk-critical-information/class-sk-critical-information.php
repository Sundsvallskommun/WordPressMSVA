<?php

require_once 'class-sk-critical-information-public.php';
require_once 'class-sk-critical-information-admin.php';

class SK_Critical_Information {

	/**
	 * SK_Critical_Information constructor.
	 */
	function __construct() {
		add_action( 'after_setup_theme', function () {
			$this->init();
		} );

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init() {
		$sk_critical_information_public = new SK_Critical_Information_Public();
		$sk_critical_information_admin  = new SK_Critical_Information_Admin();
	}

	/**
	 * Call for message html output.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return array
	 */
	public static function output() {
		return SK_Critical_Information_Public::get_messages();
	}

}