<?php

require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-posttype.php' );
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-ajax.php' );

class SK_Operation_Messages {


	public function __construct() {

		$post_type = new SK_Operation_Messages_Posttype();
		$ajax = new SK_Operation_Messages_Ajax();

		// Register the post type for operation messages
		add_action( 'init', array( $post_type, 'register_posttype' ) );

		// Register script and style if page uses the correct template
		add_filter( 'template_include', array( $this, 'enqueue_scripts_and_styles' ), 99 );

		// Setup ajax callbacks
		add_action( 'wp_ajax_new_message', array( $ajax, 'callback' ) );
		add_action( 'wp_ajax_nopriv_new_message', array( $ajax, 'callback' ) );

	}


	public function enqueue_scripts_and_styles( $template = '' ) {
		$file_name = '';

		if ( ! empty( $template ) ) {

			$file_name = basename( $template );

		}

		if ( $file_name = 'page-skapa-driftmeddelande.php' ) {

			wp_enqueue_style( 'sk-operation-messages-css', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/css/sk-operation-messages.css' );
			wp_enqueue_script( 'sk-operation-messages-js', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/js/sk-operation-messages.js', array( 'jquery' ) );

		}

		return $template;

	}



}