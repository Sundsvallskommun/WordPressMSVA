<?php

/**
 * Register the post type Material
 *
 * @package    Wp_Plugin_Wasteguide
 * @subpackage Wp_Plugin_Wasteguide/includes
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Wasteguide_Posttype_Material {

	/**
	 * Register the Material posttype
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function register_posttype() {

		register_post_type( 'material', $this->posttype_arguments() );

	}

	/**
	 * The labels used for the post type
	 *
	 * @since    1.0.0
	 * @access   private
	 * @return   array
	 */
	private function posttype_labels() {

		return array(
			'name'               => _x( 'Sorteringsguide', 'post type general name', 'wp-plugin-wasteguide' ),
			'singular_name'      => _x( 'Sak', 'post type singular name', 'wp-plugin-wasteguide' ),
			'menu_name'          => _x( 'Sorteringsguide', 'admin menu', 'wp-plugin-wasteguide' ),
			'name_admin_bar'     => _x( 'Sorteringsguide', 'add new on admin bar', 'wp-plugin-wasteguide' ),
			'add_new'            => _x( 'Ny sak', 'material', 'wp-plugin-wasteguide' ),
			'add_new_item'       => __( 'Lägg till ny Sak', 'wp-plugin-wasteguide' ),
			'new_item'           => __( 'Ny sak', 'wp-plugin-wasteguide' ),
			'edit_item'          => __( 'Ändra sak', 'wp-plugin-wasteguide' ),
			'view_item'          => __( 'Visa sak', 'wp-plugin-wasteguide' ),
			'all_items'          => __( 'Alla saker', 'wp-plugin-wasteguide' ),
			'search_items'       => __( 'Sök sak', 'wp-plugin-wasteguide' ),
			'parent_item_colon'  => __( 'Nuvarande sak:', 'wp-plugin-wasteguide' ),
			'not_found'          => __( 'Inga saker funna.', 'wp-plugin-wasteguide' ),
			'not_found_in_trash' => __( 'Inga saker funna i papperskorgen.', 'wp-plugin-wasteguide' )
		);

	}


	/**
	 * The argumets used for registering the post type
	 *
	 * @since    1.0.0
	 * @access   private
	 * @return   array
	 */
	private function posttype_arguments() {

		return array(
			'labels'             => $this->posttype_labels(),
			'description'        => __( 'Description.', 'wp-plugin-wasteguide' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'sorteringsguide' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

	}


	/**
	 * Register the custom taxonomy and categories
	 * for the post type.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function register_taxonomies() {

		register_taxonomy(
			'material_sorting',
			'material',
			array(
				'hierarchical' => true,
				'label' => 'Sorteras som',
				'query_var' => true,
				'rewrite' => array(
					'slug' => 'sorteras-som',
					'with_front' => false
				)
			)
		);

		register_taxonomy(
			'material_deposit',
			'material',
			array(
				'hierarchical' => true,
				'label' => 'Lämnas till',
				'query_var' => true,
				'rewrite' => array(
					'slug' => 'lamnas-till',
					'with_front' => false
				)
			)
		);

	}

}