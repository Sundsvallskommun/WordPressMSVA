<?php

/**
 * Main class for Ongoing projects module.
 * Require necessary classes
 *
 * @package    SK_Ongoing_Projects
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

require_once locate_template( 'lib/sk-ongoing-projects/class-sk-ongoing-projects-posttype.php' );
require_once locate_template( 'lib/sk-ongoing-projects/class-sk-ongoing-projects-shortcode.php' );

class SK_Ongoing_Projects {


	/**
	 * Class Constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		$posttype = new SK_Ongoing_Projects_Posttype();
		add_action( 'init', array( $posttype, 'register_posttype' ) );

		$shortcode = new SK_Ongoing_Projects_Shortcode();
		$shortcode->register_shortcode();


	}


	/**
	 * List all projects according to the
	 * meta value for group.
	 *
	 * @since    1.0.0
	 * @access   public static
	 *
	 * @param string    the group meta value
	 *
	 * @return array
	 *
	 */
	public static function projects( $group = 'all' ) {

		$args = array(
			'post_type' => 'ongoing_projects',
			'posts_per_page' => -1
		);

		if ( $group !== 'all' ) {

			$args['meta_key'] = 'kommun';
			$args['meta_value'] = $group;

		}

		return get_posts( $args );

	}


}