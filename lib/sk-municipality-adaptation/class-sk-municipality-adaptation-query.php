<?php

class SK_Municipality_Adaptation_Query {

	public function __construct() {

		// Add filter for querys
		add_action( 'pre_get_posts', array( $this, 'modify_query' ) );

	}


	public function modify_query( $query ) {

		// Check if on frontend and that this is a post type we should modify results for
		if( ! is_admin() && $this->query_has_valid_post_type( $query->query_vars['post_type'] ) ) {

			$query->set(
				'meta_query',
				array(
					array(
						'key' => 'municipality_adaptation',
						'value' => 'sundsvall',
						'compare' => 'LIKE'
					)
				)
			);
		}

		return $query;

	}


	private function query_has_valid_post_type( $post_type ) {

		if ( in_array( $post_type, SK_Municipality_Adaptation::valid_post_types() ) ) return true;

		return false;

	}

}