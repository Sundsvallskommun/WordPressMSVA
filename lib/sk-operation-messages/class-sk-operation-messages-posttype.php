<?php

/**
 * Wrapper class for the Operation Messages post type
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Operation_Messages_Posttype {

	/**
	 * Register the post type
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_posttype() {

		register_post_type( 'operation_message', $this->posttype_arguments() );

	}


	/**
	 * The posttype arguments
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return array    the arguments
	 */
	private function posttype_arguments() {

		return array(
			'labels'             => $this->posttype_labels(),
			'description'        => __( 'Description.', 'msva' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'operation_message' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' )
		);

	}


	/**
	 * The labels for the custom post type
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return array    the labels
	 *
	 */
	private function posttype_labels() {

		return array(
			'name'               => _x( 'Driftmeddelanden', 'post type general name', 'msva' ),
			'singular_name'      => _x( 'Driftmeddelande', 'post type singular name', 'msva' ),
			'menu_name'          => _x( 'Driftmeddelanden', 'admin menu', 'msva' ),
			'name_admin_bar'     => _x( 'Driftmeddelanden', 'add new on admin bar', 'msva' ),
			'add_new'            => _x( 'Nytt meddelande', 'operation_message', 'msva' ),
			'add_new_item'       => __( 'Lägg till nytt Driftmeddelande', 'msva' ),
			'new_item'           => __( 'Nytt meddelande', 'msva' ),
			'edit_item'          => __( 'Ändra Driftmeddelande', 'msva' ),
			'view_item'          => __( 'Visa Driftmeddelande', 'msva' ),
			'all_items'          => __( 'Alla meddelanden', 'msva' ),
			'search_items'       => __( 'Sök Driftmeddelanden', 'msva' ),
			'parent_item_colon'  => __( 'Nuvarande Driftmeddelande:', 'msva' ),
			'not_found'          => __( 'Inga Driftmeddelanden funna.', 'msva' ),
			'not_found_in_trash' => __( 'Inga Driftmeddelanden funna i papperskorgen.', 'msva' )
		);


	}

}