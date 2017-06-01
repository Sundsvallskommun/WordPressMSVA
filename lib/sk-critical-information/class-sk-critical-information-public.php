<?php

/**
 * Class SK_Critical_Information_Public
 */
class SK_Critical_Information_Public {

	/**
	 * SK_Critical_Information_Public constructor.
	 */
	function __construct() {

	}

	/**
	 * Get messages
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return array
	 */
	public static function get_messages() {

		$args = array(
			'post_type'   => 'critical_information',
			'post_status' => 'publish'
		);

		$posts = get_posts( $args );

		return $posts;

	}
}