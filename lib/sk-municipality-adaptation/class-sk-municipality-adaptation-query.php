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

		if( is_array( $post_type ) ) {
			foreach( $post_type as $type ) {

				if ( in_array( $type, SK_Municipality_Adaptation_Settings::valid_post_types() ) ) return true;

			}
		}

		if ( in_array( $post_type, SK_Municipality_Adaptation_Settings::valid_post_types() ) ) return true;

		return false;

	}

}