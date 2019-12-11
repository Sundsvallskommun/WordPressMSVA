<?php

/**
 * Main class for Connection Fee module.
 * Register scripts and create instances of necessary classes.
 *
 * @package    SK_Connection_Fee
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

require_once 'class-sk-connection-fee-shortcode-2020.php';
require_once 'class-sk-connection-fee-ajax-2020.php';

class SK_Connection_Fee_2020 {

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

		$shortcode = new SK_Connection_Fee_Shortcode_2020();
		$ajax = new SK_Connection_Fee_Ajax_2020();

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

		wp_register_script( 'connection-fee-js-2020', get_stylesheet_directory_uri() . '/lib/sk-connection-fee-2020/assets/js/sk-connection-fee-2020.js', array('jquery') );
		wp_register_style( 'connection-fee-css-2020', get_stylesheet_directory_uri() . '/lib/sk-connection-fee-2020/assets/css/sk-connection-fee-2020.css' );


	}

}