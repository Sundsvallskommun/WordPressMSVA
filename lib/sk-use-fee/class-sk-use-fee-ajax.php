<?php
/**
 * Ajax functionality for the Use Fee Shortcode
 */

class SK_Use_Fee_Ajax {

	protected $nr_of_services = 0;
	protected $service_fee = 0;
	protected $sum = 0;
	protected $sum_before_reduction = 0;

	protected $type_of_services = ['V', 'S', 'DF', 'DG'];
	protected $point_fee = 2585;
	protected $apartment_fee = 1162;
	protected $use_cost = 26.56;

	public $services = array();
	public $user_use = '';
	public $user_apartments = '';
	public $user_business_area = '';

	public $error = array();


	/**
	 * Setup ajax hooks for admin and frontend
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		add_action( 'wp_ajax_nopriv_calculate_use_fee', array( $this, 'init' ) );
		add_action( 'wp_ajax_calculate_use_fee', array( $this, 'init' ) );

	}

	/**
	 * Init method.
	 *
	 * @return void
	 */
	public function init(){
		
		$this->set_posted_data();
		$this->validate_data();

		if( empty( $this->error ) ){
			$this->calculate( $this->services, $this->user_use, $this->user_apartments, $this->user_business_area );
		}else{
			wp_send_json_success( $this->print_error() );
		}

	}

	/**
	 * Validating user input data.
	 *
	 * @return void
	 */
	public function validate_data(){

		if( empty( $this->services )){
			$this->error[] = 'Inga tjänster är valda.'; 
		}

		if( empty( $this->user_use ) ) {
			$this->error[] = 'Förbrukning saknas.';
		}

		if( empty( $this->user_apartments ) && empty( $this->user_business_area ) ) {
			$this->error[] = 'Kontrollera dina värden under typ av fastighet.';
		}
		
	}

	/**
	 * Set user input data.
	 *
	 * @return void
	 */
	public function set_posted_data(){

		check_ajax_referer( 'sk-use-fee', 'nonce' );

		$data = array();
		parse_str( $_POST['data'], $data );

		if( isset( $data['service_s'] ) ){
			$this->services[] = $data['service_s'];
		}
		if( isset( $data['service_v'] ) ){
			$this->services[] = $data['service_v'];
		}
		if( isset( $data['service_dg'] ) ){
			$this->services[] = $data['service_dg'];
		}
		if( isset( $data['service_df'] ) ){
			$this->services[] = $data['service_df'];
		}

		if( isset( $data['user_use'] ) && is_numeric( $data['user_use'] ) ){
			$this->user_use = intval( $data['user_use'] );
		}

		if( isset( $data['user_apartments'] ) && is_numeric( $data['user_apartments'] ) ){
			$this->user_apartments = intval( $data['user_apartments'] );
		}

		if( isset( $data['user_business_area'] ) && is_numeric( $data['user_business_area'] ) ){
			$this->user_business_area = intval( $data['user_business_area'] );
		}

	}

	/**
	 * Sumarize and respond wiht json.
	 *
	 * @param [type] $services
	 * @param [type] $user_use
	 * @param boolean $user_apartments
	 * @param boolean $user_business_area
	 * @return void
	 */
	public function calculate( $services, $user_use, $user_apartments = false, $user_business_area = false ){

		$point_fee = round( $this->get_point_fee( $services ) );
		$apartment_fee = round( $this->get_apartment_fee( $services ) );
		$use_fee = $this->get_use_cost( $services );

		if( !empty( $user_business_area ) ){
			$user_apartments = ceil( $user_business_area / 140 );
		}
		
		$sum_apartments_fee = $apartment_fee * $user_apartments;

		//total sum for use fee
		$sum_use_fee = $user_use * $use_fee;

		//total sum
		$sum_final = $sum_apartments_fee + $sum_use_fee + $point_fee; 
		
		// Send the response
		wp_send_json_success( $this->markup(  round( $sum_final ) ) );
		die();
		
	}


	/**
	 * Calculate point fee.
	 *
	 * @param array $services
	 * @return void
	 */
	public function get_point_fee( $services = array() ){

		$fees_reduction = [
			'V' => 0.4,
			'S' => 0.5,
			'DF' => 0.09,
			'DG' => 0.01
		];

		$reduction = 0;
		foreach( $services as $service ){
			$reduction += $fees_reduction[ $service ];
		}
	
		return $reduction * $this->point_fee;
		
	}

	/**
	 * Calculate apartment fee.
	 *
	 * @param array $services
	 * @return void
	 */
	public function get_apartment_fee( $services = array() ){

		$fees_reduction = [
			'V' => 0.4,
			'S' => 0.5,
			'DF' => 0.09,
			'DG' => 0.01
		];

		$reduction = 0;
		foreach( $services as $service ){
			$reduction += $fees_reduction[ $service ];
		}
		
		return $reduction * $this->apartment_fee;

	}


	/**
	 * Calculate use fee.
	 *
	 * @param array $services
	 * @return void
	 */
	public function get_use_cost( $services = array() ){

		$fees_reduction = [
			'V' => 0.43,
			'S' => 0.57
		];

		$reduction = 0;
		foreach( $services as $service ){
			$reduction += isset( $fees_reduction[ $service ] ) ? $fees_reduction[ $service ] : null;
		}
	
		return $reduction * $this->use_cost;
		
	}

	/**
	 * Create the resulting markup for the ajax request
	 *
	 * @param [type] $sum
	 * @return void
	 */
	private function markup( $sum ) {
		ob_start();
		?>
		<div class="card card-block">
			<p class="card-text">
				<?php printf( __('Avgiften för dina tjänster blir <strong>%s</strong> kr inklusive moms.', 'msva' ), number_format( $sum,  0, ' ', ' ' ) ); ?>
			</p>
		</div>

		<?php
		$content = ob_get_clean();
		return $content;
	}
	

	/**
	 * Printing out error.
	 *
	 * @return void
	 */
	private function print_error() {
		ob_start();
		?>
		<div class="card card-block">
			<h3><?php _e('Något har gått fel vid uträkningen', 'msva'); ?></h3>
			<ul>
				<?php foreach( $this->error as $error ) : ?>
				<li><?php echo $error; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}

}