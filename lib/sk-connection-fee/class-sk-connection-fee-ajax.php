<?php

/**
 * Ajax functionality for the Connection Fee Shortcode
 *
 * @package    SK_Connection_Fee
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Connection_Fee_Ajax {

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

		add_action( 'wp_ajax_nopriv_calculate_connection_fee', array( $this, 'calculate' ) );
		add_action( 'wp_ajax_calculate_connection_fee', array( $this, 'calculate' ) );

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

		check_ajax_referer( 'sk-connection-fee', 'nonce' );

		$params = array();
		parse_str( $_POST['data'], $params );

		// Get chosen municipality
		$municipality = $params['municipality'];

		// Get user supplied parameters
		$area = absint( $params['area'] );
		$apartments = absint( $params['apartments'] );

		// Init municipal fees
		$this->setup_standard_fees( $municipality );

		// Calculate area and apartment fees
		$area_fee = $this->area_fee( $municipality, $area, $apartments );
		$apartment_fee = $this->apartment_fee( $municipality, $apartments );


		// The sum for Nordanstig municipality differs from the others
		if ( $municipality == 'nordanstig' ) {

			$this->sum = $apartment_fee + $this->service_fee;
			$this->sum_before_reduction = $this->sum;

		} else {

			$this->sum = $this->point_fee + $area_fee + $apartment_fee;
			$this->sum_before_reduction = $this->sum + $this->service_fee;

		}


		// Set reduction for water fee
		if( isset( $params['water_fee'] ) ) {

			$water_fee = $this->sum * 0.3;
			$this->nr_of_services++;

		}

		// Set reduction for spill water fee
		if ( isset( $params['spillwater_fee'] ) ) {

			$spillwater_fee = $this->sum * 0.6;
			$this->nr_of_services++;

		}

		// Set reduction for rain water fee. Only for Sundsvall and Timrå.
		if ( isset( $params['rainwater_fee'] ) && $municipality != 'nordanstig' ) {

			$rain_fee = $this->sum * 0.1;
			$this->nr_of_services++;

		}


		if ( $municipality != 'nordanstig' ) { // Calculate final sum for Sundsvall and Timrå

			$this->calculate_service_fee();
			$this->sum = $water_fee + $spillwater_fee + $rain_fee + $this->service_fee;

		} else { // Calculate final sum for Nordanstig

			if( $this->nr_of_services == 1 ) {
				$this->sum = $this->sum * 0.75;
			} elseif( $this->nr_of_services > 2 ) {
				$this->sum = 0;
			}

		}

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
  Anläggningsavgiften i %s för de valda tjänserna blir <strong>%d</strong> kr inklusive moms.
  </p>
  <p class="card-text">
	Anslutningen är möjlig om fastigheten ligger inom kommunens verksamhetsområde för allmänna vatten- och avloppstjänster.
	Den anläggningsavgift som du räknar ut här på sidan måste bekräftas av MittSverige Vatten och Avfall.
  </p>
</div>
eol;

		return sprintf( $response_markup, $municipality, $sum );

	}


	/**
	 * Setup standard fees for given municipality
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string    the municipality
	 */
	private function setup_standard_fees( $municipality ) {

		switch ( $municipality ) {

			case 'sundsvall':
				$this->service_fee = 100000;
				$this->point_fee = 50000;
				break;
			case 'timra':
				$this->service_fee = 83000;
				$this->point_fee = 40000;
				break;
			case 'nordanstig':
				$this->service_fee = 150000;
				break;
			default:
				break;


		}


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
	private function area_fee( $municipality, $area, $apartments ) {

		switch ( $municipality ) {

			case 'sundsvall':
				$area_fee = $this->area_fee_sundsvall( $area, $apartments );
				break;

			case 'timra':
				$area_fee = $this->area_fee_timra( $area, $apartments );
				break;

			case 'nordanstig':
				$area_fee = 0;
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

			case 'timra':
				$apartment_fee = $this->apartment_fee_timra( $apartments );
				break;

			case 'nordanstig':
				$apartment_fee = $this->apartment_fee_nordanstig( $apartments );
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
	private function area_fee_sundsvall( $area, $apartments ) {

		if ( $area <= 1000 ) {

			$area_fee = $area * 30;

		} elseif ( $area > 1000 && $area < 2000 ) {

			$area_fee = 30000 + ( $area - 1000 ) * 15;

		} else {

			$area_fee = 30000 + 15000 + ( $area - 2000 ) * 9;

		}

		// What is this check for?
		if ( $area > 1500 && $apartments >= 1 ) {
			$area_fee = 30000 + 500*15;
		}

		return $area_fee;

	}


	/**
	 * Check and eturn the correct area fee for Timrå
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param integer   the area to be calculated
	 * @param integer   the nr of apartments to be calculated
	 *
	 * @return integer  the area fee
	 */
	private function area_fee_timra( $area, $apartments ) {

		if ( $area <= 1000 ) {

			$area_fee = $area * 25;

		} elseif ( $area >= 1001 && $area < 2000 ) {

			$area_fee = 25000 + ( $area - 1000 ) * 12.5;

		} else {

			$area_fee = 25000 + 12500 + ( $area - 2000 ) * 7.5;

		}

		// What is this check for?
		if ( $area > 1500 && $apartments >= 1 ) {
			$area_fee = 25000 + 500 * 12.5;
		}

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


	/**
	 * Check and eturn the correct apartment fee for Timrå
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param integer   the nr of apartments to be calculated
	 *
	 * @return integer  the apartment fee
	 */
	private function apartment_fee_timra( $apartments ) {

		if ( $apartments <= 40 ) {

			$apartment_fee = $apartments * 7000;

		} elseif ( $apartments >=41 && $apartments <= 50  ) {

			$apartment_fee = 280000 + ( $apartments - 40 ) * 5000;

		} elseif( $apartments > 50 ) {

			$apartment_fee = 280000 + 50000 + ( $apartments - 50 ) * 3500;

		}

		return $apartment_fee;

	}


	/**
	 * Check and eturn the correct apartment fee for Nordanstig
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param integer   the nr of apartments to be calculated
	 *
	 * @return integer  the apartment fee
	 */
	private function apartment_fee_nordanstig( $apartments ) {

		if ( $apartments > 1 ) {

			$apartment_fee = 20000;

		} else {

			$apartment_fee = 0;

		}

		return $apartment_fee;

	}

}