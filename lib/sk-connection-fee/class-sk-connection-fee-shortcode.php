<?php

/**
 * Class wrapping shortcode output for the Connection Fee
 *
 * @package    SK_Connection_Fee
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Connection_Fee_Shortcode {


	/**
	 * Class Constructor
	 * Add shortcode and setup municipalities
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		add_shortcode( 'connection_fee', array( $this, 'callback' ) );

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
		wp_enqueue_script( 'connection-fee-js' );
		wp_enqueue_style( 'connection-fee-css' );

		ob_start();
		?>
		<div class="connection-fee-container">

			<form id="sk-connection-fee" method="post" action="#">

				<input id="connection-fee-nonce" type="hidden" value="<?php echo wp_create_nonce( 'sk-connection-fee' ); ?>" name="nonce" />

				<div class="form-group">
					<legend><?php _e( 'Välj kommun', 'msva' ); ?></legend>
					<select class="form-control" name="municipality" id="sk-connection-fee-municipality">
						<?php foreach( SK_Municipality_Adaptation::municipalities() as $key => $value ) : ?>
						<option value="<?php echo $key; ?>" <?php selected( SK_Municipality_Adaptation_Cookie::value(), $key ); ?>><?php echo $value; ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group">
					<legend><?php _e( 'För vilka tjänster gäller anslutningen?', 'msva' ); ?></legend>

					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" id="water-fee" class="form-check-input" name="water_fee" />
							<?php _e( 'Vattenförsöjning', 'msva' ); ?>
						</label>
					</div>
					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" id="spillwater-fee" class="form-check-input" name="spillwater_fee" />
							<?php _e( 'Spillvattenavlopp', 'msva' ); ?>
						</label>
					</div>
					<div class="form-check" id="rain-water-check">
						<label class="form-check-label">
							<input type="checkbox" id="rainwater-fee" class="form-check-input" name="rainwater_fee" />
							<?php _e( 'Dagvattenavlopp', 'msva' ); ?>
						</label>
					</div>

					<!-- Help section -->
					<em><?php _e( 'Vi delar in våra tjänster i så kallade nyttigheter. Avgiftsskyldighet föreligger för nyttigheterna: Dricksvatten, Spillvatten, Dagvatten. Vilka tjänster som är aktuella för dig beror på var inom verksamhetsområdet anslutningen är tänkt att genomföras. Den totala anläggningsavgiften varierar beroende vilka tjänster anslutningen avser.', 'msva' ); ?></em>
					<!-- End help section -->
				</div>

				<div class="form-group" id="sk-connection-fee-area-group">
					<legend><?php _e( 'Tomtyta för fastigheten som skall anslutas', 'msva' ); ?></legend>

					<input type="number" min="0" max="1500" class="form-control" id="sk-connection-fee-area-input" placeHolder="<?php _e( 'Tex 1500', 'msva' ); ?>" name="area">

					<div class="form-control-feedback"><?php _e('För bostadsfastighet med högst två lägenheter maximeras den avgiftspliktiga tomtytan till 1500 m².', 'msva' ); ?></div>

					<!-- Help section -->
					<em><?php _e( 'För bostadsfastighet med högst två lägenheter maximeras den avgiftspliktiga tomtytan till 1500 m².', 'msva' ); ?></em>
					<!-- End help section -->
				</div>

				<div class="form-group" id="sk-connection-fee-apartment-group">
					<legend><?php _e( 'Antal lägenheter i fastigheten som skall anslutas', 'msva' ); ?></legend>

					<input type="text" type="number" class="form-control" id="sk-connection-fee-apartment-input" placeHolder="<?php _e( 'Tex 2', 'msva' ); ?>" name="apartments" />

					<div class="form-control-feedback"><?php _e( 'Du kan beräkna anläggningsavgiften för anslutning av en normal villa eller mindre hyresfastighet med max två lägenheter.', 'msva' ); ?></div>

					<!-- Help section -->
					<em><?php _e( 'En normal villa räknas som en lägenhet i beräkningen av anslutningsavgiften.', 'msva' ); ?></em>
					<!-- End help section -->

				</div>

				<button type="button" id="sk-connection-fee-submit" class="btn btn-secondary float-xs-right"><?php _e( 'Beräkna', 'msva' ); ?></button>


			</form>

			<div class="clearfix"></div>

			<div class="row connection-fee-loader">
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

			<div class="row" id="sk-connection-fee-calculation-response"></div>

		</div>
		<?php
		return ob_get_clean();

	}

}