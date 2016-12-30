<?php

/**
 * Functionality for modifying and checking the wordpress
 * queries to be able to pick only posts that matches user's
 * choice of municipality
 *
 * @package    SK_Municipality_Adaptation
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Municipality_Adaptation_Query {


	/**
	 * Class constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		// Add filter for querys
		add_action( 'pre_get_posts', array( $this, 'modify_query' ) );
		add_action( 'wp', array( $this, 'modify_single_query' ) );

	}


	/**
	 * Check to see if a visitor has access to a certain post.
	 * Redirect to 404 if not.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function modify_single_query() {
		global $post, $wp_query;


		if ( is_object( $post ) ) {

			if( $wp_query->is_posts_page ) {
				return true;
			}

			if ( ! SK_Municipality_Adaptation::page_access( $post->ID ) ) {

				$wp_query->set_404();
				status_header( 404 );

			}

		}

	}


	/**
	 * Check if conditions are met. If so
	 * then we modify the wp query to get posts matching
	 * user's municipality adaption cookie.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $query
	 *
	 * @return object   $query
	 */
	public function modify_query( $query ) {

		// Check if on frontend and that this is a post type we should modify results for
		if( ! is_admin() && isset( $query->query_vars['post_type'] ) && $this->query_has_valid_post_type( $query->query_vars['post_type'] ) && SK_Municipality_Adaptation_Cookie::exists() ) {

			$query->set(
				'meta_query',
				array(
					'relation' => 'OR',
					array(
						'key' => 'municipality_adaptation',
						'value' => SK_Municipality_Adaptation_Cookie::value(),
						'compare' => 'LIKE'
					),
					array(
						'key' => 'municipality_adaptation',
						'compare' => 'NOT EXISTS'
					)
				)

			);

		} else {

			if ( is_object( $query ) ) {

				$post_type = isset( $query->query['post_type'] ) ? $query->query['post_type'] : null;

				if ( defined('DOING_AJAX') && DOING_AJAX && isset( $post_type ) && $this->query_has_valid_post_type( $post_type ) && SK_Municipality_Adaptation_Cookie::exists() ) {

					$query->set(
						'meta_query',
						array(
							'relation' => 'OR',
							array(
								'key' => 'municipality_adaptation',
								'value' => SK_Municipality_Adaptation_Cookie::value(),
								'compare' => 'LIKE'
							),
							array(
								'key' => 'municipality_adaptation',
								'compare' => 'NOT EXISTS'
							)
						)

					);

				}

			}

		}

		return $query;

	}


	/**
	 * Check if the given post type is one of the
	 * valid post types for modifying
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string|array
	 *
	 * @return boolean
	 */
	private function query_has_valid_post_type( $post_type ) {

		$result = true;
		if( is_array( $post_type ) ) {
			foreach( $post_type as $type ) {

				if ( ! in_array( $type, SK_Municipality_Adaptation_Settings::valid_post_types() ) ) {
					$result = false;
				}

			}
		} else {

			if ( in_array( $post_type, SK_Municipality_Adaptation_Settings::valid_post_types() ) ) {
				$result = true;
			} else {
				$result = false;
			}
		}

		return $result;

	}

}