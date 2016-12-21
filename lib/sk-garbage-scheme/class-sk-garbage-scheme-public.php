<?php

/**
 * Class for page template flexible
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
class SK_Garbage_Scheme_Public {

	/**
	 * SK_Blocks_Public constructor.
	 */
	function __construct() {
		$this->init();
	}


	public function init(){
		add_shortcode( 'sopbilen', array( $this, 'output' ) );
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
		ob_start();

		$a = shortcode_atts( array(
			'title'     => false,
			'text'   => false
		), $atts );

		?>
		<div class="widget-garbage-scheme">

			<?php if( $a['title'] ) : ?>
				<h3><?php echo $a['title']; ?></h3>
			<?php endif; ?>

			<?php if( $a['text'] ) : ?>
				<div class="widget-wasteguide__text"><?php echo $a['text']; ?></div>
			<?php endif; ?>

			<!--<form method="get" action="<?php echo get_permalink( $waste_guide_id ); ?>">-->

				<label for="sorting-material"><?php _e( 'sopbil?', 'msva' ); ?></label>
				<div class="input-group">
					<input type="text" class="form-control" id="sorting-material"
					       placeholder="<?php _e( 'T.ex diskborste', 'msva' ); ?>" name="search_wasteguide">
					<span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Sök</button>
                </span>
				</div>
			<!--</form>-->
		</div>
		<?php

		if ( isset( $_GET['search_wasteguide'] ) ) {
			require_once( get_stylesheet_directory() . '/lib/sk-wasteguide/template.php' );
		}

		return ob_get_clean();

	}









}
