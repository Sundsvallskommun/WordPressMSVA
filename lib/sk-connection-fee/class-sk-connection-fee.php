<?php

/**
 * Main class for Connection Fee module.
 * Register scripts and create instances of necessary classes.
 *
 * @package    SK_Connection_Fee
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

require_once 'class-sk-connection-fee-shortcode.php';
require_once 'class-sk-connection-fee-ajax.php';

class SK_Connection_Fee {

	/**
	 * Class Constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		// Register scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_and_styles' ) );

		$shortcode = new SK_Connection_Fee_Shortcode();
		$ajax = new SK_Connection_Fee_Ajax();

	}


	/**
	 * Register script and stylesheet for
	 * use with the shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_scripts_and_styles() {

		wp_register_script( 'connection-fee-js', get_stylesheet_directory_uri() . '/lib/sk-connection-fee/assets/js/sk-connection-fee.js', array('jquery') );
		wp_register_style( 'connection-fee-css', get_stylesheet_directory_uri() . '/lib/sk-connection-fee/assets/css/sk-connection-fee.css' );

	}

}