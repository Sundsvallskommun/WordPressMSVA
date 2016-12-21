<?php

/**
 * Shortcode wrapper class.
 *
 * @package    SK_My_Pages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_My_Pages_Shortcode {


	/**
	 * Callback function for the my pages shortcode.
	 * Outputs the html for the shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array     the shortcode attributes
	 *
	 * @return string   the html output
	 */
	public function callback( $atts = array() ) {
		ob_start();

		// If we have a municipality cookie and it is set correctly
		if ( $url = $this->get_iframe_address() ) {
			?>

			<h4><?php echo sprintf( __( 'Vald kommun är: %s', 'msva' ), SK_Municipality_Adaptation_Cookie::print_value() ); ?></h4>

			<iframe height="600" src="<?php echo $url; ?>" frameborder="0" width="100%" scrolling="no"></iframe>

			<?php

		} else {

			?>

			<div class="municipality-adaptation-error">
				<?php _e( 'Du måste först välja kommuntillhörighet', 'msva' ); ?>
			</div>

			<?php

		}

		return ob_get_clean();

	}


	/**
	 * Checks municipality cookie and sets up
	 * the url for the iframe.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return string|boolean   the address to use|false if no address or cookie.
	 */
	private function get_iframe_address() {

		if ( ! SK_Municipality_Adaptation_Cookie::exists() ) return false;

		$municipality = SK_Municipality_Adaptation_Cookie::value();
		switch ( $municipality ) {

			case 'sundsvall':
				return 'http://masvall.mittsverigevatten.se';
				break;
			case 'timra':
				return 'http://matimra.mittsverigevatten.se';
				break;
			case 'nordanstig':
				return 'http://manstig.mittsverigevatten.se';
				break;
			default:
				break;

		}

		return false;

	}

}