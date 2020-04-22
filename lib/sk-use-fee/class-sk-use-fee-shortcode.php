<?php

/**
 * Class wrapping shortcode output for the use Fee
 *
 */

class SK_Use_Fee_Shortcode {


	/**
	 * Class Constructor
	 * Add shortcode and setup municipalities
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		add_shortcode( 'use_fee', array( $this, 'callback' ) );

	}


	/**
	 * Shortcode callback. Returns markup
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array     the attributes
	 *
	 * @return string   the markup
	 */
	public function callback( $atts = array() ) {

		// Only load scripts when shortcode is used
		wp_enqueue_script( 'use-fee-js' );
		wp_enqueue_style( 'use-fee-css' );

		ob_start();
		?>	
		<div class="use-fee-container">

			<form id="sk-use-fee" method="post" action="#">

				<input id="use-fee-nonce" type="hidden" value="<?php echo wp_create_nonce( 'sk-use-fee' ); ?>" name="nonce" />

				<div class="form-group">
					<legend><?php _e( 'Ange de tjänster du är ansluten till', 'msva' ); ?></legend>

					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" id="service-v" class="form-check-input" name="service_v" value="V">
							<?php _e( 'Vatten', 'msva' ); ?>
						</label>
						<p><?php _e( 'Tjänst som innebär att vi levererar dricksvatten till dig', 'msva' ); ?></p>
					</div>
					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" id="service-s" class="form-check-input" name="service_s" value="S">
							<?php _e( 'Spillvatten', 'msva' ); ?>
						</label>
						<p><?php _e( 'Tjänst som innebär att vattnet du spolar ner i avloppet åker till ett avloppsreningsverk', 'msva' ); ?></p>
					</div>
					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" id="service-dg" class="form-check-input" name="service_dg" value="DG">
							<?php _e( 'Dagvatten gata', 'msva' ); ?>
						</label>
						<p><?php _e( 'Tjänst som innebär att dagvatten leds bort från din tomt', 'msva' ); ?></p>
					</div>
					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" id="service-df" class="form-check-input" name="service_df" value="DF">
							<?php _e( 'Dagvatten fastighet', 'msva' ); ?>
						</label>
						<p><?php _e( 'Tjänst som innebär att dagvatten leds bort från vägar till och från din fastighet', 'msva' ); ?></p>
					</div>
				</div>

				<div class="form-group" id="sk-use-fee-area-group">

				<legend><?php _e( 'Förbrukning (kubikmeter)', 'msva' ); ?></legend>
					<input type="number" min="0" class="form-control" id="user-use" placeHolder="<?php _e( 'Ange din årliga förbrukning', 'msva' ); ?>" name="user_use">
				</div>

				<div class="form-group" id="sk-use-fee-apartment-group">
					<legend><?php _e( 'Typ av fastighet', 'msva' ); ?></legend>
					
					<div class="form-check">
						<label class="form-check-label">
							<input type="radio" class="form-check-input type-of-estate" name="estate-type" value="private-estate">
							<?php _e( 'En- eller flerbostadsfastighet', 'msva' ); ?>
						</label>

						<div class="form-field-toggle mt-1">
							<input type="number" class="form-control" id="user-apartments" placeHolder="<?php _e( 'Antal lägenheter', 'msva' ); ?>" name="user_apartments">
							<em><?php _e( 'Ange antal lägenheter. En villa räknas som en lägenhet i beräkningen.', 'msva' ); ?></em>
						</div>
					</div>

					<div class="form-check mb-3">
						<label class="form-check-label">
							<input type="radio" class="form-check-input type-of-estate" name="estate-type" value="business-estate">
							<?php _e( 'Verksamhetsfastighet', 'msva' ); ?>
						</label>

						<div class="form-field-toggle mt-1">
							<input type="number" class="form-control" id="user-business-area" placeHolder="<?php _e( 'Ange lokalyta i kvm', 'msva' ); ?>" name="user_business_area">
						</div>
					</div>


				</div>
				
				<button type="button" id="sk-use-fee-clear-form" class="btn btn-secondary"><?php _e( 'Rensa formulär', 'msva' ); ?></button>
				<button type="button" id="sk-use-fee-submit" class="btn btn-secondary float-xs-right"><?php _e( 'Beräkna', 'msva' ); ?></button>


			</form>

			<div class="clearfix"></div>

			<div class="use-fee-loader mt-3">
				<div class="card card-block">
					<h4 class="card-title"><?php _e( 'Beräknar avgift', 'msva' ); ?></h4>
					<div class="sk-circle">
						<div class="sk-circle1 sk-child"></div>
						<div class="sk-circle2 sk-child"></div>
						<div class="sk-circle3 sk-child"></div>
						<div class="sk-circle4 sk-child"></div>
						<div class="sk-circle5 sk-child"></div>
						<div class="sk-circle6 sk-child"></div>
						<div class="sk-circle7 sk-child"></div>
						<div class="sk-circle8 sk-child"></div>
						<div class="sk-circle9 sk-child"></div>
						<div class="sk-circle10 sk-child"></div>
						<div class="sk-circle11 sk-child"></div>
						<div class="sk-circle12 sk-child"></div>
					</div>
				</div>
			</div>

			<div id="sk-use-fee-calculation-response" class="mt-3"></div>

		</div>
		<?php
		return ob_get_clean();

	}

}