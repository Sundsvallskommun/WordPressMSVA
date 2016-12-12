<?php

/**
 * The FAQ shortcode class
 *
 * @package    SK_FAQ
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_FAQ_Shortcode {


	/**
	 * Constructor for the shortcode class
	 * Add the shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		add_shortcode( 'faq', array( $this, 'callback' ) );

	}


	/**
	 * The callback function for the shortcode.
	 * Provides the HTML output.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array     the shortcode attributes
	 *
	 * @return string   the html output
	 *
	 */
	public function callback( $atts ) {
		$attributes = shortcode_atts(
			array(
				'categories' => '',
				'admin'      => 'false'
			),
			$atts
		);

		$faqs       = SK_FAQ::faq( $attributes['categories'] );
		$has_access = $this->has_access();

		wp_enqueue_script( 'sk-faq-js' );
		wp_enqueue_style( 'sk-faq-css' );

		ob_start();
		?>

		<?php if ( count( $faqs ) > 0 && is_array( $faqs ) ) : ?>

			<?php
			if ( $attributes['admin'] === 'true' ) {
				echo $this->filter_options();
			}
			?>

			<?php foreach ( $faqs as $faq ) : ?>

				<?php
				if ( $faq->meta['internal_only'] && ! $has_access ) {

					// Do not show the question because it's internal only

				} else {

					echo do_shortcode(
						sprintf(
							'[utfallbar tag="h2" title="%s"]%s[/utfallbar]',
							$faq->post_title,
							$this->content( $faq, $has_access )
						)
					);

				}
				?>

			<?php endforeach; ?>

		<?php endif; ?>


		<?php
		return ob_get_clean();

	}


	/**
	 * The html output for the FAQ filtering options
	 * when user is able to administrate the FAQ.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string   the html output
	 *
	 */
	private function filter_options() {
		ob_start();
		?>
        <div class="faq-filter row">
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control search-filter"
                           placeholder="<?php _e( 'Sök efter...', 'msva' ); ?>">
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();

	}


	/**
	 * The html output for the single FAQ content.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param object    the faq post object
	 * @param boolean   if user has access
	 *
	 * @return string   the html output
	 */
	private function content( $faq, $has_access ) {
		$content       = '';
		$external_text = ( $faq->meta['external_text'] ) ? $faq->meta['external_text'] : null;
		$internal_text = ( $faq->meta['internal_text'] ) ? $faq->meta['internal_text'] : null;

		if ( $has_access ) {

			ob_start();
			?>

			<?php if ( ! empty( $external_text ) ) : ?>
                <div class="faq-external-answer">
					<?php echo $external_text ?>
                </div>
			<?php endif; ?>

			<?php if ( ! empty( $internal_text ) ) : ?>
                <div class="faq-internal-answer">
                    <h3><?php _e( 'Internt', 'msva' ); ?></h3>
					<?php echo $internal_text ?>
                </div>
			<?php endif; ?>

            <div class="row faq-edit">
                <a href="<?php echo get_edit_post_link( $faq->ID ); ?>" class="float-xs-right"
                   target="_blank"><?php _e( 'Redigera', 'msva' ); ?></a>
            </div>

			<?php
			$content = ob_get_clean();

		} else {

			ob_start();
			?>

            <div class="faq-external-answer">
				<?php echo $external_text; ?>
            </div>

			<?php
			$content = ob_get_clean();

		}

		return $content;

	}


	/**
	 * Check if user is contributor or above
	 * as a role.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return boolean      the user access
	 *
	 */
	private function has_access() {

		if ( current_user_can( 'edit_post' ) ) {
			return true;
		}

		return false;

	}

}