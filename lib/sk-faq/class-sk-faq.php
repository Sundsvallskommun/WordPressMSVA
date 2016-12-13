<?php

/**
 * Main class for SK FAQ module.
 *
 * @package    SK_FAQ
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

require_once locate_template( 'lib/sk-faq/class-sk-faq-posttype.php' );
require_once locate_template( 'lib/sk-faq/class-sk-faq-shortcode.php' );

class SK_FAQ {


	/**
	 * Create necessary instances.
	 * Register actions and filters
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		$posttype  = new SK_FAQ_Posttype();
		$shortcode = new SK_FAQ_Shortcode();

		add_action( 'init', array( $posttype, 'register_posttype' ) ); // Create the posttype FAQ
		add_filter( 'enter_title_here', array(
			$posttype,
			'change_title_placeholder'
		) ); // Set new placeholder text for title

		// Enqueue scripts and styles for later use.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles_and_scripts' ) );

		// Add the single FAQ template to the template list
		add_filter( 'template_include', array( $this, 'add_single_faq_template' ) );

	}


	/**
	 * Include the single faq template if this is
	 * a faq and user has access to it.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the template
	 *
	 * @return string   the new template|the old template
	 */
	public function add_single_faq_template( $template ) {
		global $wp, $post;

		if ( isset( $wp->query_vars['post_type'] ) && $wp->query_vars['post_type'] == 'faq' ) {

			$internal_only = get_post_meta( $post->ID, 'visa_endast_internt', true );

			// Redirect to 404 if user not allowed access
			if ( $internal_only && ! current_user_can( 'edit_post' ) ) {

				status_header( 404 );
				get_template_part( 404 );
				exit();

			} else { // Include the single template for FAQs

				$template_filename = 'single-faq.php';
				if ( file_exists( get_stylesheet_directory() . '/lib/sk-faq/assets/templates/' . $template_filename ) ) {

					return get_stylesheet_directory() . '/lib/sk-faq/assets/templates/' . $template_filename;

				}

			}

		}

		return $template;

	}


	/**
	 * Register styles and scripts
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_styles_and_scripts() {

		wp_register_script( 'sk-faq-js', get_stylesheet_directory_uri() . '/lib/sk-faq/assets/js/sk-faq.js', array( 'jquery' ) );
		wp_register_style( 'sk-faq-css', get_stylesheet_directory_uri() . '/lib/sk-faq/assets/css/sk-faq.css' );

	}


	/**
	 * Get faqs according to given categories
	 *
	 * @since    1.0.0
	 * @access   public static
	 *
	 * @param string    comma separated categories
	 *
	 * @return array    the faqs found
	 */
	public static function faq( $categories = '' ) {

		$args = array(
			'post_type'       => 'faq',
			'number_of_posts' => - 1
		);

		// Filter in category by the category names given
		if ( ! empty( $categories ) ) {
			$args['category_name'] = $categories;
		}

		$posts = get_posts( $args );

		if ( count( $posts ) > 0 && is_array( $posts ) ) {

			// Get meta data for each FAQ
			foreach ( $posts as $key => $post ) {

				$post->meta = array();

				$post->meta['external_text'] = get_post_meta( $post->ID, 'fritext_for_extern_visning', true );
				$post->meta['internal_text'] = get_post_meta( $post->ID, 'fritext_for_intern_visning', true );
				$post->meta['internal_only'] = get_post_meta( $post->ID, 'visa_endast_internt', true );

				$posts[ $key ] = $post;

			}

		}

		return $posts;

	}


}