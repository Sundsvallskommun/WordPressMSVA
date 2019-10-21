<?php

/**
 * Class SK_Cookie_Informer
 */


class SK_Cookie_Informer {

	/**
	 * SK_Cookie_Informer constructor.
	 */
	function __construct() {
		add_action( 'after_setup_theme', function () {
			$this->init();
		} );

	}

	/**
	 * Init method
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init(){
		if ( ! isset( $_COOKIE['sk_cookie_informer'] ) ) {
			add_action( 'wp_footer', [ $this, 'cookie_informer_output' ] );
		}
		add_action( 'acf/save_post', [ $this, 'on_acf_save_privacy' ], 20 );
	}


	/**
	 * Runs when save post with ACF fields.
	 * Save to transient.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function on_acf_save_privacy() {
		$screen = get_current_screen();

		if (strpos($screen->id, 'acf-options-integritet-och-kakor') == true) {
			delete_transient( 'sk_cookie_informer' );
			set_transient( 'sk_cookie_informer', self::set_transient_data() );
		}
		
	}


	/**
	 * Store output in transient.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return string
	 */
	public static function set_transient_data(){
		ob_start();

		$informer['text']              = get_field( 'sk_cookie_informer_text', 'option' );
		$informer['privacy_link']      = get_field( 'sk_cookie_informer_privacylink', 'option' );
		$informer['privacy_link_text'] = get_field( 'sk_cookie_informer_privacylink_text', 'option' );
		$informer['approve_text']      = get_field( 'sk_cookie_informer_approve_text', 'option' );
		$informer['panel_bg']          = get_field( 'sk_cookie_informer_panel_bg', 'option' );
		$informer['panel_text_color']  = get_field( 'sk_cookie_informer_panel_text_color', 'option' );
		$informer['button_bg']         = get_field( 'sk_cookie_informer_button_bg', 'option' );
		$informer['button_text_color'] = get_field( 'sk_cookie_informer_button_text_color', 'option' );

		if( empty( $informer['text'])){
			return '';
		}

		$panel_bg = false;
		if( !empty($informer['panel_bg'])){
			$panel_bg = sprintf( ' style="background-color: %s"', $informer['panel_bg']);
		}

		$panel_text_color = false;
		if( !empty($informer['panel_text_color'])){
			$panel_text_color = sprintf( ' style="color: %s"', $informer['panel_text_color']);
		}

		$button_bg = false;
		if( !empty($informer['button_bg'])){
			$button_bg = sprintf( ' style="background-color: %s"', $informer['button_bg']);
		}

		$button_text_color = false;
		if( !empty($informer['button_text_color'])){
			$button_text_color = sprintf( ' style="color: %s"', $informer['button_text_color']);
		}

	?>
		<div class="cookie-informer"<?php echo isset($panel_bg) ? $panel_bg : null; ?>>
			<div class="cookie-informer__inner"<?php echo isset($panel_text_color) ? $panel_text_color : null; ?>>
				<span class="cookie-informer__inner--text"><?php echo $informer['text']; ?></span>
				<button id="btn-accept-cookies" class="cookie-informer__accept"<?php echo isset($button_bg) ? $button_bg : null; ?>><span<?php echo isset($button_text_color) ? $button_text_color : null; ?>><?php echo ! empty( $informer['approve_text'] ) ? $informer['approve_text'] : __( 'Acceptera' ); ?></span></button>
					<?php if ( ! empty( $informer['privacy_link'] ) ): ?>
						<a class="cookie-informer__readmore" href="<?php echo $informer['privacy_link']; ?>"><?php echo ! empty( $informer['privacy_link_text'] ) ? $informer['privacy_link_text'] : __( 'Läs mer' ); ?></a>
					<?php endif;?>
			</div>
		</div>
		<?php
		$output = ob_get_contents();
		ob_get_clean();
		return $output;
	}


	/**
	 * Print out the cookie informer panel in footer from a transient.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function cookie_informer_output(){
		$activated = get_field( 'sk_cookie_informer_activated', 'options');

		if( $activated ) {
			$output = get_transient( 'sk_cookie_informer' );
			echo $output;
		}
	}

}