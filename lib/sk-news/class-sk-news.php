<?php


class SK_News {

	/**
	 * SK_Garbage_Scheme constructor.
	 */
	function __construct() {
		add_action( 'after_setup_theme', function () {
			$this->init();
		} );

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init() {
		add_shortcode( 'nyheter', array( $this, 'output' ) );
	}


	/**
	 * HTML output for the search form
	 *
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function output( $atts, $content ) {

		//$temp = SK_Garbage_Scheme::get_addresses();
		//util::debug( $temp );

		ob_start();

		$a = shortcode_atts( array(
			'title'     => false,
			'text'   => false
		), $atts );

		?>
		<div class="widget widget-latest-news">
			<div class="mobile-news" id="news">
				<?php get_template_part('partials/latest-news'); ?>
			</div>
			<?php //require_once( get_stylesheet_directory() . '/lib/sk-wasteguide/template.php' ); ?>
			<div class="clearfix"></div>
		</div>
		<?php
		return ob_get_clean();

	}


}