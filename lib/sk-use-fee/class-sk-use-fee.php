<?php

/**
 * Main class for Use Fee module.
 * Register scripts and create instances of necessary classes.
 *
 */

require_once 'class-sk-use-fee-shortcode.php';
require_once 'class-sk-use-fee-ajax.php';

class SK_Use_Fee {

	/**
	 * Class Constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		// Register scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_and_styles2' ) );

		$shortcode = new SK_Use_Fee_Shortcode();
		$ajax = new SK_Use_Fee_Ajax();

	}


	/**
	 * Register script and stylesheet for
	 * use with the shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_scripts_and_styles2() {

		wp_register_script( 'use-fee-js', get_stylesheet_directory_uri() . '/lib/sk-use-fee/assets/js/sk-use-fee.js', array('jquery') );
		wp_register_style( 'use-fee-css', get_stylesheet_directory_uri() . '/lib/sk-use-fee/assets/css/sk-use-fee.css' );


	}

}