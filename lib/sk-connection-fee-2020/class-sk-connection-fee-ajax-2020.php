<?php

/**
 * Ajax functionality for the Connection Fee Shortcode
 *
 * @package    SK_Connection_Fee
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Connection_Fee_Ajax_2020 {

	protected $nr_of_services = 0;
	protected $service_fee = 0;
	protected $point_fee = 0;
	protected $sum = 0;
	protected $sum_before_reduction = 0;

	/**
	 * Setup ajax hooks for admin and frontend
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		add_action( 'wp_ajax_nopriv_calculate_connection_fee_2020', array( $this, 'calculate' ) );
		add_action( 'wp_ajax_calculate_connection_fee_2020', array( $this, 'calculate' ) );

	}


	/**
	 * The ajax callback calculating the total fee
	 * and outputing the finished markup in json response.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function calculate() {

		check_ajax_referer( 'sk-connection-fee-2020', 'nonce' );

		$params = array();
		parse_str( $_POST['data'], $params );

		// Get chosen municipality
		$municipality = $params['municipality'];

		// Get user supplied parameters
		//$area = absint( preg_replace('/\s+/', '', $params['area'] ) ); // remove whitespace, not needed for area i guess.
		$area = absint( $params['area'] );
		$apartments = absint( $params['apartments'] );

		// Init municipal fees
		$this->setup_standard_fees();

		// Calculate area and apartment fees
		$area_fee = $this->area_fee( $municipality, $area );
		$apartment_fee = $this->apartment_fee( $municipality, $apartments );


		$this->sum = $apartment_fee + $this->service_fee;
		$this->sum_before_reduction = $apartment_fee + $area_fee + $this->point_fee;		

		// Set reduction for water fee
		$water_fee = 0;
	
		if( isset( $params['water_fee'] ) ) {
			$water_fee = $this->sum_before_reduction * 0.3;
			$this->nr_of_services++;

		}

		// Set reduction for spill water fee
		$spillwater_fee = 0;

		if ( isset( $params['spillwater_fee'] ) ) {

			$spillwater_fee = $this->sum_before_reduction * 0.5;
			$this->nr_of_services++;

		}

		// Set reduction for rain water fee. Only for Sundsvall and Timrå.
		$rain_fee = 0;

		if ( isset( $params['rainwater_fee'] ) ) {

			$rain_fee = $this->sum_before_reduction * 0.2;
			$this->nr_of_services++;

		}

		$this->calculate_service_fee();
		
		$this->sum = $water_fee + $spillwater_fee + $rain_fee + $this->service_fee;		
	
		// Send the response
		wp_send_json_success( $this->markup( $municipality, $this->sum ) );

	}


	/**
	 * Create the resulting markup for the ajax request
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the municipality
	 * @param integer   the sum
	 *
	 * @return string   the markup
	 */
	private function markup( $municipality, $sum ) {

		$response_markup = <<<eol
<div class="card card-block">
  <p class="card-text">
  Anläggningsavgiften i %s för de valda tjänsterna blir <strong>%s</strong> kr inklusive moms.
  </p>
  <p class="card-text">
	Anslutningen är möjlig om fastigheten ligger inom kommunens verksamhetsområde för allmänna vatten- och avloppstjänster.
	Den anläggningsavgift som du räknar ut här på sidan måste bekräftas av MittSverige Vatten och Avfall.
  </p>
</div>
eol;

		return sprintf( $response_markup, ucfirst( $municipality ), number_format( $sum,  0, ' ', ' ' ) );

	}


	/**
	 * Setup standard fees for given municipality
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the municipality
	 */
	private function setup_standard_fees() {

		$this->service_fee = 100000;
		$this->point_fee = 50000;

	}


	/**
	 * Calculate the service fee depending on
	 * nr of services used.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	private function calculate_service_fee() {

		switch( $this->nr_of_services ) {
			case 1:
				$this->service_fee = $this->service_fee * 0.7;
				break;

			case 2:
				$this->service_fee = $this->service_fee * 0.85;
				break;

			case 3:
				break;

			default:
				$this->service_fee = 0;
		}

	}


	/**
	 * Check the given municipality and return the correct area fee
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the municipality
	 * @param integer   the area to be calculated
	 * @param integer   the nr of apartments to be calculated
	 *
	 * @return integer  the area fee
	 */
	private function area_fee( $municipality, $area ) {

		switch ( $municipality ) {

			case 'sundsvall':
				$area_fee = $this->area_fee_sundsvall( $area );
				break;

			default:
				break;

		}

		return $area_fee;

	}


	/**
	 * Check the given municipality and return the correct area fee
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the municipality
	 * @param integer   the area to be calculated
	 * @param integer   the nr of apartments to be calculated
	 *
	 * @return integer  the area fee
	 */
	private function apartment_fee( $municipality, $apartments ) {

		switch( $municipality ) {

			case 'sundsvall':
				$apartment_fee = $this->apartment_fee_sundsvall( $apartments );
				break;

			default:
				break;

		}

		return $apartment_fee;

	}


	/**
	 * Check and eturn the correct area fee for Sundsvall
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param integer   the area to be calculated
	 * @param integer   the nr of apartments to be calculated
	 *
	 * @return integer  the area fee
	 */
	private function area_fee_sundsvall( $area ) {

		$area_fee = $area * 30;
		return $area_fee;

	}




	/**
	 * Check and eturn the correct apartment fee for Sundsvall
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param integer   the nr of apartments to be calculated
	 *
	 * @return integer  the apartment fee
	 */
	private function apartment_fee_sundsvall( $apartments ) {

		if ( $apartments <= 40 ) {

			$apartment_fee = $apartments * 15000;

		} elseif ( $apartments >=41 && $apartments <= 50  ) {

			$apartment_fee = 600000 + ( $apartments - 40 ) * 10700;

		} elseif( $apartments > 50 ) {

			$apartment_fee = 600000 + 107000 + ( $apartments - 50 ) * 7500;

		}

		return $apartment_fee;

	}

}