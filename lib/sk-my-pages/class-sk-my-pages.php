<?php

/**
 * Main class for the module My Pages.
 * Initializes objects
 *
 * @package    SK_My_Pages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

require_once 'class-sk-my-pages-shortcode.php';

class SK_My_Pages {

	/**
	 * Constructor for the main class.
	 * Init instances and setup shortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		$shortcode = new SK_My_Pages_Shortcode();

		add_shortcode( 'my_pages', array( $shortcode, 'callback' ) );

	}

}