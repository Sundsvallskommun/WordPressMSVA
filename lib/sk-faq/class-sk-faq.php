<?php

require_once locate_template( 'lib/sk-faq/class-sk-faq-posttype.php' );
require_once locate_template( 'lib/sk-faq/class-sk-faq-shortcode.php' );

class SK_FAQ {


	public function __construct() {

		$posttype  = new SK_FAQ_Posttype();
		$shortcode = new SK_FAQ_Shortcode();

		add_action( 'init', array( $posttype, 'register_posttype' ) ); // Create the posttype FAQ
		add_filter( 'enter_title_here', array(
			$posttype,
			'change_title_placeholder'
		) ); // Set new placeholder text for title

	}


	public static function faq( $categories = '' ) {

		$args = array(
			'post_type'       => 'faq',
			'number_of_posts' => - 1
		);

		if ( ! empty( $categories ) ) {
			$args['category_name'] = $categories;
		}

		$posts = get_posts( $args );

		if ( count( $posts ) > 0 && is_array( $posts ) ) {

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