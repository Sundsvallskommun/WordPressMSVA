<?php

/**
 * Class for the post type ongoing_projects.
 * Wraps all functionality for the post type.
 *
 * @package    SK_Ongoing_Projects
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Ongoing_Projects_Posttype {


	/**
	 * Register the post type
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_posttype() {

		register_post_type( 'ongoing_projects', $this->posttype_arguments() );

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
			'description'        => __( 'Description.', 'ongoing_projects' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'pagaende-arbeten' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor' )
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
			'name'               => _x( 'Pågående arbeten', 'post type general name', 'ongoing_projects' ),
			'singular_name'      => _x( 'Pågående Arbete', 'post type singular name', 'ongoing_projects' ),
			'menu_name'          => _x( 'Pågående Arbeten', 'admin menu', 'ongoing_projects' ),
			'name_admin_bar'     => _x( 'Pågående Arbeten', 'add new on admin bar', 'ongoing_projects' ),
			'add_new'            => _x( 'Nytt Arbete', 'material', 'ongoing_projects' ),
			'add_new_item'       => __( 'Lägg till nytt Arbete', 'ongoing_projects' ),
			'new_item'           => __( 'Nytt Arbete', 'ongoing_projects' ),
			'edit_item'          => __( 'Ändra Arbete', 'ongoing_projects' ),
			'view_item'          => __( 'Visa Arbete', 'ongoing_projects' ),
			'all_items'          => __( 'Alla Arbeten', 'ongoing_projects' ),
			'search_items'       => __( 'Sök Arbeten', 'ongoing_projects' ),
			'parent_item_colon'  => __( 'Nuvarande arbeten:', 'ongoing_projects' ),
			'not_found'          => __( 'Inga arbeten funna.', 'ongoing_projects' ),
			'not_found_in_trash' => __( 'Inga arbeten funna i papperskorgen.', 'ongoing_projects' )
		);


	}


}