<?php

require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-posttype.php' );

class SK_Operation_Messages {


	public function __construct() {

		$post_type = new SK_Operation_Messages_Posttype();

		add_action( 'init', array( $post_type, 'register_posttype' ) );
		add_action( 'wp_enqueue_scripts', array( 'register_scripts_and_styles' ) );

		add_filter( 'template_include', array( $this, 'enqueue_scripts_and_styles' ), 99 );

	}


	public function register_scripts_and_styles() {

		wp_register_style( 'sk-operation-messages-css', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/css/sk-operation-messages.css' );


	}


	public function enqueue_scripts_and_styles( $template = '' ) {
		$file_name = '';

		if ( ! empty( $template ) ) {

			$file_name = basename( $template );

		}

		if ( $file_name = 'page-skapa-driftmeddelande.php' ) {

			wp_enqueue_style( 'sk-operation-messages-css', get_stylesheet_directory_uri() . '/lib/sk-operation-messages/assets/css/sk-operation-messages.css' );

		}

		return $template;

	}



}