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
			'text'  => false
		), $atts );
		printf( '<div class="sk-buttonlink"><h2><a href="%s">%s%s</a></h2></div>', $a['link'], $a['text'], get_material_icon( 'chevron_right', array( 'size' => '200%' ) ) ); 

		return ob_get_clean();

	}


}
