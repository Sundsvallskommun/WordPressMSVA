<?php


class SK_FAQ_Posttype {

	/**
	 * Register the post type
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_posttype() {

		register_post_type( 'faq', $this->posttype_arguments() );

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
			'description'        => __( 'Description.', 'faq' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'faq' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'taxonomies'         => array( 'category' )
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
			'name'               => _x( 'Frågor och svar', 'post type general name', 'faq' ),
			'singular_name'      => _x( 'Frågor och svar', 'post type singular name', 'faq' ),
			'menu_name'          => _x( 'Frågor och svar', 'admin menu', 'faq' ),
			'name_admin_bar'     => _x( 'Frågot och svar', 'add new on admin bar', 'faq' ),
			'add_new'            => _x( 'Ny Fråga och svar', 'material', 'faq' ),
			'add_new_item'       => __( 'Lägg till ny Fråga och svar', 'faq' ),
			'new_item'           => __( 'Ny Fråga och svar', 'faq' ),
			'edit_item'          => __( 'Ändra Fråga och svar', 'faq' ),
			'view_item'          => __( 'Visa Fråga och svar', 'faq' ),
			'all_items'          => __( 'Alla Frågor och svar', 'faq' ),
			'search_items'       => __( 'Sök Frågor och svar', 'faq' ),
			'parent_item_colon'  => __( 'Nuvarande Frågor och svar:', 'faq' ),
			'not_found'          => __( 'Inga Frågor och svar funna.', 'faq' ),
			'not_found_in_trash' => __( 'Inga Frågor och svar funna i papperskorgen.', 'faq' )
		);


	}


	/**
	 * Set the new placeholder text for the custom post type title
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the old title
	 *
	 * @return string   the new title
	 *
	 */
	public function change_title_placeholder( $title ) {

		$screen = get_current_screen();
		if ( 'faq' == $screen->post_type ) {
			$title = __( 'Ange fråga här', 'msva' );
		}

		return $title;
	}

}