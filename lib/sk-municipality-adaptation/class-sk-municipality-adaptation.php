<?php

/**
 * Main class for the module Municipality Adaption.
 * Initializes objects
 *
 * @package    SK_Municipality_Adaptation
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

require_once 'class-sk-municipality-adaptation-settings.php';
require_once 'class-sk-municipality-adaptation-query.php';
require_once 'class-sk-municipality-adaptation-cookie.php';
require_once 'class-sk-municipality-adaptation-admin.php';

class SK_Municipality_Adaptation {


	/**
	 * Class constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		$this->init();

	}


	/**
	 * Initialize module classes
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	private function init() {

		$settings = new SK_Municipality_Adaptation_Settings();
		$query = new SK_Municipality_Adaptation_Query();
		$admin = new SK_Municipality_Adaptation_Admin();

	}


	/**
	 * Check if the given post id and it's connected
	 * municipals against the users municipality adaption cookie.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param integer
	 *
	 * @return boolean
	 */
	public static function page_access( $post_id ) {

		if ( ! SK_Municipality_Adaptation_Cookie::exists() ) return true;

		$post_municipalities = SK_Municipality_Adaptation_Admin::chosen_municipalities( $post_id );
		return SK_Municipality_Adaptation_Cookie::match( $post_municipalities );

	}


	/**
	 * Load the partial for when the user does
	 * not have page access.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function municipality_no_access_markup() {

		load_template( get_stylesheet_directory() . '/lib/sk-municipality-adaptation/partials/missing-municipality-access.php' );

	}

}