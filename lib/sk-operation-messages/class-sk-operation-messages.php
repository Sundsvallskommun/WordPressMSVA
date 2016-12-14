<?php

require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-posttype.php' );
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-ajax.php' );
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-shortcode.php' );

class SK_Operation_Messages {


	public function __construct() {

		$post_type = new SK_Operation_Messages_Posttype();
		$ajax = new SK_Operation_Messages_Ajax();
		$shortcode = new SK_Operation_Message_Shortcode();

		// Register the post type for operation messages
		add_action( 'init', array( $post_type, 'register_posttype' ) );

		// Register script and style if page uses the correct template
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_and_styles' ), 99 );

		// Setup ajax callbacks
		add_action( 'wp_ajax_new_message', array( $ajax, 'callback' ) );
		add_action( 'wp_ajax_nopriv_new_message', array( $ajax, 'callback' ) );

		// Add meta data fields to the post type edit page
		add_action( 'add_meta_boxes', array( $post_type, 'add_meta_boxes' ) );

		add_shortcode( 'operation_message_form', array( $shortcode, 'callback' ) );


	}


	public function register_scripts_and_styles( $template = '' ) {

		wp_register_style( 'sk-operation-messages-css', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/css/sk-operation-messages.css' );
		wp_register_style( 'sk-operation-messages-datepicker', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/css/datepicker/datepicker.css' );
		wp_register_script( 'sk-operation-messages-js', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/js/sk-operation-messages.js', array( 'jquery' ), null, true );

		wp_register_script( 'sk-operation-messages-datepicker', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/js/datepicker/bootstrap-datepicker.js', array( 'jquery' ) );
		wp_register_script( 'sk-operation-messages-datepicker-locale', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/js/datepicker/locales/bootstrap-datepicker.sv.js', array( 'jquery' ) );

	}


	public static function messages( $statuses = array( 'publish' ) ) {

		$messages = get_posts(

			array(
				'posts_per_page'    => -1,
				'post_type'         => 'operation_message',
				'post_status'       => $statuses,
			)

		);


		if ( count( $messages ) > 0 && is_array( $messages ) ) {

			foreach ( $messages as $key => $message ) {

				$meta = get_post_custom( $message->ID );
				$message->meta = $meta;
				$messages[$key] = $message;

			}

		}

		return $messages;

	}



}