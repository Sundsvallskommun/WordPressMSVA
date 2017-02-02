<?php

/**
 * Class SK_Critical_Information_Admin
 */
class SK_Critical_Information_Admin {

	/**
	 * SK_Critical_Information_Admin constructor.
	 */
	function __construct() {
		$this->init();
	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_post_type' ), 10 );
	}

	/**
	 * Register post type for Critical Information
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function register_post_type() {

		register_post_type( 'critical_information',
			array(
				'labels'          => array(
					'menu_name'     => __( 'Krismeddelande', 'sk_tivoli' ),
					'name'          => __( 'Krismeddelande', 'sk_tivoli' ),
					'singular_name' => __( 'Krismeddelande', 'sk_tivoli' ),
					'add_new'       => __( 'Lägg till krismeddelande', 'sk_tivoli' ),
					'add_new_item'  => __( 'Lägg till nytt krismeddelande', 'sk_tivoli' ),
					'edit_item'     => __( 'Ändra krismeddelande', 'sk_tivoli' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'menu_position'   => 40,
				'menu_icon'       => 'dashicons-megaphone',
				'capability_type' => 'post',
				'hierarchical'    => false,
				'rewrite'         => array( 'slug' => 'critical-information', 'with_front' => false ),
				'supports'        => array( 'title', 'editor' )
			)
		);
	}

}