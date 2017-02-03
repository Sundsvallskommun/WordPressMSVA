<?php

/**
 * Main class for the Sk Operation Messages Package
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-posttype.php' );
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-ajax.php' );
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-shortcode.php' );
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-cron.php' );
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-post.php' );

class SK_Operation_Messages {


	/**
	 * Create instances, add actions and filters.
	 * Register the shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		$post_type = new SK_Operation_Messages_Posttype();
		$ajax      = new SK_Operation_Messages_Ajax();
		$shortcode = new SK_Operation_Message_Shortcode();
		$cron      = new SK_Operation_Messages_Cron();

		// Register the post type for operation messages
		add_action( 'init', array( $post_type, 'register_posttype' ) );

		// Register script and style if page uses the correct template
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_and_styles' ), 99 );

		// Setup ajax callbacks
		add_action( 'wp_ajax_new_message', array( $ajax, 'callback' ) );
		add_action( 'wp_ajax_nopriv_new_message', array( $ajax, 'callback' ) );

		// Add meta data fields to the post type edit page
		add_action( 'add_meta_boxes', array( $post_type, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $post_type, 'save' ) );

		add_shortcode( 'operation_message_form', array( $shortcode, 'callback' ) );

		// Add the single operation message template and archive to the template list
		add_filter( 'template_include', array( $this, 'add_templates' ) );


	}


	/**
	 * Register scripts and styles for later enqueueing.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_scripts_and_styles() {

		wp_register_style( 'sk-operation-messages-css', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/css/sk-operation-messages.css' );
		wp_register_style( 'sk-operation-messages-datepicker', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/css/datepicker/datepicker.css' );
		wp_register_script( 'sk-operation-messages-js', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/js/sk-operation-messages.js', array( 'jquery' ), null, true );

		wp_register_script( 'sk-operation-messages-datepicker', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/js/datepicker/bootstrap-datepicker.js', array( 'jquery' ) );
		wp_register_script( 'sk-operation-messages-datepicker-locale', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/js/datepicker/locales/bootstrap-datepicker.sv.js', array( 'jquery' ) );

	}


	/**
	 * List messages according to parameters.
	 * Supports different statuses. Loading with meta fields and
	 * Checking for archived messages only.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array     allowed statuses
	 * @param boolean   populate with meta
	 * @param boolean   list only previously archived posts
	 *
	 * @return array    the posts
	 */
	public static function messages( $statuses = array( 'publish' ), $with_meta = true, $archived = false, $ready_to_unpublish = false ) {

		$args = array(
			'posts_per_page' => - 1,
			'post_type'      => 'operation_message',
			'post_status'    => $statuses
		);

		if ( $archived ) {

			if ( $ready_to_unpublish ) { // Check for operation messages to unpublish

				$compare_date = self::get_archive_limit_date();

				$args['meta_query'] = array(
					array(
						'key'     => 'om_archived_at',
						'value' => $compare_date,
						'type' => 'DATE',
						'compare' => '<='
					)
				);

			} else { // Check for messages that are archived

				$args['meta_query'] = array(
					array(
						'key'     => 'om_archived_at',
						'compare' => 'EXISTS'
					)
				);
			}


		} else { // Check for messages that are published and should be on front page

			$args['meta_query'] = array(
				array(
					'key'     => 'om_archived_at',
					'compare' => 'NOT EXISTS'
				),
			);

		}

		$messages = get_posts( $args );

		if ( $with_meta ) {

			if ( count( $messages ) > 0 && is_array( $messages ) ) {

				foreach ( $messages as $key => $message ) {

					$meta             = get_post_custom( $message->ID );
					$message->meta    = $meta;
					$messages[ $key ] = $message;

				}

			}

		}

		return $messages;

	}


	/**
	 * Calculate and return the date 30 days ago.
	 * That is the archive storing limit date.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return string   the archive limit date
	 *
	 */
	private static function get_archive_limit_date() {

		$date_and_time = current_time( 'Y-m-d H:i' );
		$splits        = explode( ' ', $date_and_time );
		$splits[1]     = '16:00';
		$date_and_time = implode( ' ', $splits );
		$compare_date  = date_i18n( 'Y-m-d H:i', strtotime( $date_and_time . ' -30 days' ) );

		return $compare_date;

	}


	/**
	 * Include the single template if this is
	 * a operation message post type.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the template
	 *
	 * @return string   the template to use
	 */
	public function add_templates( $template ) {
		global $wp;

		if ( isset( $wp->query_vars['post_type'] ) && $wp->query_vars['post_type'] == 'operation_message' ) {

			if(is_single()) {
				$template_filename = 'single-operation_message.php';
				if ( file_exists( get_stylesheet_directory() . '/lib/sk-operation-messages/assets/templates/' . $template_filename ) ) {
					return get_stylesheet_directory() . '/lib/sk-operation-messages/assets/templates/' . $template_filename;
				}
			}

			elseif(is_archive() ) {
				$template_filename = 'archive-operation_message.php';
				if ( file_exists( get_stylesheet_directory() . '/lib/sk-operation-messages/assets/templates/' . $template_filename ) ) {
					return get_stylesheet_directory() . '/lib/sk-operation-messages/assets/templates/' . $template_filename;
				}
			}

		}

		return $template;

	}

}