<?php

/**
 * Class for garbage scheme public face
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
class SK_Garbage_Scheme_Public {

	/**
	 * SK_Garbage_Scheme_Public constructor.
	 */
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
		add_shortcode( 'sopbilen', array( $this, 'output' ) );
	}


	/**
	 * HTML output for the search for run schedule form.
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
			'title' => false,
			'text'  => false
		), $atts );

		?>
		<script type="text/javascript">
			var data = <?php SK_Garbage_Scheme::get_addresses( 'print' ); ?>;
		</script>
		<div class="widget widget-garbage-scheme">
			<?php if ( $a['title'] ) : ?>
				<h3><?php echo $a['title']; ?></h3>
			<?php endif; ?>

			<?php if ( $a['text'] ) : ?>
				<div class="widget-garbage-scheme__text"><?php echo $a['text']; ?></div>
			<?php endif; ?>

			<label for="sorting-material"><?php _e( 'Sök på din adress', 'msva' ); ?></label>

			<div class="input-group">
				<div class="widget-garbage-scheme__response" style="display:none;">


				</div>
				<input type="text" data-provide="typeahead" class="form-control" autocomplete="off"
				       id="garbage-scheme-address"
				       placeholder="<?php _e( 'Ange din gatuadress', 'msva' ); ?>" name="">
				<span class="input-group-btn">
                    <button class="btn btn-secondary" id="garbage-search-btn" type="submit"><span><?php _e('Sök', 'msva'); ?></span></button>
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
