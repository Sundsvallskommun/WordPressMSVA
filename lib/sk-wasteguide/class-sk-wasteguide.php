<?php
/**
 * Created by PhpStorm.
 * User: andreasfarnstrand
 * Date: 21/10/16
 * Time: 19:16
 */

require_once locate_template( 'lib/sk-wasteguide/class-sk-wasteguide-posttype-material.php' );
require_once locate_template( 'lib/sk-wasteguide/class-sk-wasteguide-search.php' );


class SK_Wasteguide {

	public function __construct() {

		$this->add_hooks();

	}


	private function add_hooks() {

		$material = new SK_Wasteguide_Posttype_Material();
		$shortcode = new SK_Wasteguide_Search();

		add_action( 'init', array( $material, 'register_posttype' ) );
		add_action( 'init', array( $material, 'register_taxonomies' ) );


	}

}