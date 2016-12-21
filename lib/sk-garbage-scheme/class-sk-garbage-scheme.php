<?php

require_once 'class-sk-garbage-scheme-public.php';
require_once 'class-sk-garbage-scheme-admin.php';

class SK_Garbage_Scheme {

	/**
	 * SK_Garbage_Scheme constructor.
	 */
	function __construct() {
		add_action('after_setup_theme', function () {
			$this->init();
		});

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init(){
		$sk_garbage_scheme_public = new SK_Garbage_Scheme_Public();
		$sk_garbage_scheme_public = new SK_Garbage_Scheme_Public();
	}


}