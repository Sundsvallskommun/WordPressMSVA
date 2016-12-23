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

		//$temp = SK_Garbage_Scheme::get_addresses();
		//util::debug( $temp );

		ob_start();

		$a = shortcode_atts( array(
			'title'     => false,
			'text'   => false
		), $atts );

		?>
		<script type="text/javascript">
			var data = <?php SK_Garbage_Scheme::get_addresses( 'print' ); ?>;
		</script>
		<div class="widget widget-garbage-scheme">
			<?php if( $a['title'] ) : ?>
				<h3><?php echo $a['title']; ?></h3>
			<?php endif; ?>

			<?php if( $a['text'] ) : ?>
				<div class="widget-garbage-scheme__text"><?php echo $a['text']; ?></div>
			<?php endif; ?>

			<label for="sorting-material"><?php _e( 'Sök på din adress', 'msva' ); ?></label>

				<div class="input-group">
					<div class="widget-garbage-scheme__response" style="display:none;">



					</div>
					<input type="text" data-provide="typeahead" class="form-control" autocomplete="off" id="garbage-scheme-address"
					       placeholder="<?php _e( 'Ange din gatuadress', 'msva' ); ?>" name="">
					<span class="input-group-btn">
                    <button class="btn btn-secondary" id="garbage-search-btn" type="submit">Sök</button>
                </span>
				</div>

		</div>
		<?php

		if ( isset( $_GET['search_wasteguide'] ) ) {
			require_once( get_stylesheet_directory() . '/lib/sk-wasteguide/template.php' );
		}

		return ob_get_clean();

	}









}
