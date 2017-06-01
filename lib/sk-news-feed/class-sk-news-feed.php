<?php

/**
 * Main class for the custom RSS Feed.
 * Outputs feed for news to be published
 * to the intranet.
 *
 * @package    SK_News_Feed
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_News_Feed {

	/**
	 * Constructor for the main class.
	 * Adds feed on init
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'add_feed' ) );

	}


	/**
	 * Register and add the custom feed.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_feed() {

		add_feed( 'parallell', array( $this, 'feed' ) );

	}


	/**
	 * The feed callback for the ouput.
	 * Get the template for the feed.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function feed() {

		get_template_part( 'rss', 'parallell' );

	}

}