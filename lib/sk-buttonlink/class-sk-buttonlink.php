<?php

/**
 * Class for short code link button.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
class SK_Buttonlink {

	function __construct() {
		$this->init();
	}

	/**
	 * Init method.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init() {
		add_shortcode( 'knapplank', array( $this, 'output' ) );
		add_action('init', array( $this, 'tinymce_buttonlink_button_init'));
	}


	/**
	 * Add custom button to TinyMCE.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 * 
	 */
	public function tinymce_buttonlink_button_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
			return;
		}

		add_filter('mce_buttons', array($this, 'register_tinymce_buttonlink_button'));
		add_filter('mce_external_plugins', array($this, 'add_tinymce_buttonlink_button_plugin'));
	}

	/**
	 * Register the cutom button.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 * 
	 * @param [type] $buttons
	 * @return void
	 */
	public function register_tinymce_buttonlink_button($buttons) {
		$buttons[] = "sk_buttonlink";
		return $buttons;
	}

	/**
	 * Inject the script for the button.
	 * 
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param [type] $plugin_array
	 * @return void
	 */
	public function add_tinymce_buttonlink_button_plugin( $plugin_array ) {
		$plugin_array['sk_buttonlink'] = get_stylesheet_directory_uri().'/lib/sk-buttonlink/sk-buttonlink.js';
		return $plugin_array;
	}

	/**
	 * HTML output for shortcode
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function output( $atts, $content ) {

		ob_start();

		$a = shortcode_atts( array(
			'link' => false,
			'title'  => false
		), $atts );
		printf( '<div class="sk-buttonlink"><h2><a href="%s">%s%s</a></h2></div>', $a['link'], $a['title'], get_material_icon( 'chevron_right', array( 'size' => '200%' ) ) ); 

		return ob_get_clean();

	}


}
