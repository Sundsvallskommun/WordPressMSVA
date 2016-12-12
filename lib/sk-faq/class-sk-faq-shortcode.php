<?php


class SK_FAQ_Shortcode {


	public function __construct() {

		add_shortcode( 'faq', array( $this, 'callback' ) );

	}


	public function callback( $atts ) {
		$attributes = shortcode_atts(
			array(
				'categories' => '',
                'admin' => 'false'
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


	private function filter_options() {
	    ob_start();
	    ?>
        <div class="faq-filter row">
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control search-filter" placeholder="<?php _e( 'Sök efter...', 'msva' ); ?>">
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();

    }


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


	private function has_access() {

		if ( current_user_can( 'edit_post' ) ) {
			return true;
		}

		return false;

	}

}