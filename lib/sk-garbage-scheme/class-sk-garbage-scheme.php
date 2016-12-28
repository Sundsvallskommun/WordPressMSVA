<?php

require_once 'class-sk-garbage-scheme-public.php';
require_once 'class-sk-garbage-scheme-admin.php';

class SK_Garbage_Scheme {

	/**
	 * SK_Garbage_Scheme constructor.
	 */
	function __construct() {
		add_action( 'after_setup_theme', function () {
			$this->init();
		} );

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init() {
		$this->create_db_table();
		//$this->populate_data();


		//$results = self::get_item_by_address( 'Skogsduvevägen 6' );
		/*
		global $wpdb;
		$results = $wpdb->get_results(
			"SELECT * FROM $wpdb->garbage_scheme LIMIT 100"

		);

		foreach ( $results as $result ){
			echo self::scheme_output( $result->run_list_name );
			//echo self::scheme_by_code( $result->run_list_name );
			//echo $result->run_list_name . ' ' .self::scheme_by_code( $result->run_list_name ). '<br>';

			//echo substr($result->run_list_name, -3);

		}
		*/

		add_action( 'init', array( $this, 'importer' ) );
		add_action( 'wp_ajax_garbage_run', array( $this, 'ajax_garbage_run' ) );
		add_action( 'wp_ajax_nopriv_garbage_run', array( $this, 'ajax_garbage_run' ) );
		//$this->get_data();
		//$this->get_scheme();
		$sk_garbage_scheme_public = new SK_Garbage_Scheme_Public();
		$sk_garbage_scheme_public = new SK_Garbage_Scheme_Public();


	}

	public function importer() {
		if ( isset( $_GET['import_run'] ) ) {
			self::populate_data();
		}

	}


	static function get_addresses( $print = false ) {
		global $wpdb;

		$sql     = "SELECT DISTINCT street_address FROM $wpdb->garbage_scheme ORDER BY street_address ASC";
		$results = $wpdb->get_results( $sql );

		//util::Debug( json_encode( $results ));

		if ( empty( $results ) ) {
			return false;
		}

		if ( $print ) {
			echo json_encode( $results );
			//util::debug( json_encode( $results ));
			//foreach ($results as $result){
			//echo $result->street_address . ',';
			//}
		} else {
			return $results;
		}


	}

	public function ajax_garbage_run() {
		if ( isset( $_POST['address'] ) ) {
			$address = $_POST['address'];
		}

		if ( empty( $address ) ) {
			return false;
		}

		$results = self::get_item_by_address( $address );
		?>


		<div class="widget-garbage-scheme__response-close"><a
				href="#"><?php material_icon( 'cancel', array( 'size' => '1.5em' ) ); ?></a></div>
		<?php if ( ! empty( $results ) ) : ?>
			<?php foreach ( $results as $result ) : ?>
				<div>
					<?php material_icon( 'local shipping', array( 'size' => '1.5em' ) ); ?><?php _e( sprintf( 'Dina sopor hämtas på %s. Nu är det vecka %d.', self::scheme_output( $result->run_list_name ), (int) date('W')), 'msva' ); ?>
				</div>

				<!--
				<div><?php material_icon( 'local shipping', array( 'size' => '1.5em' ) ); ?><?php _e( 'Nästa gång sopbilen kommer till din adress är ', 'msva' ); ?>
					<span class="run-date"><?php echo $result->run_list_name; ?><?php echo self::scheme_by_code( $result->run_list_name ); ?></span></div>
					-->
			<?php endforeach; ?>
		<?php else : ?>
			<div><?php material_icon( 'local shipping', array( 'size' => '1.5em' ) ); ?><?php _e( 'Hittar ingen tid i ditt område', 'msva' ); ?></div>
		<?php endif; ?>

		<?php


		die();
	}

	static function get_item_by_address( $address = '' ) {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
				FROM $wpdb->garbage_scheme 
				WHERE street_address = %s
			",
			$address

		) );

		return $results;


	}

	/**
	 * Get print friendly run scheme by run code.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $run_code
	 */
	static function scheme_output( $run_code ){
		$run_code = trim( $run_code );

		//echo substr( $run_code, 0, 2 ) . '<br>';
		//var_dump( $run_code );
		$week = mb_substr( $run_code, -1 );
		$day = mb_substr( $run_code, -3, 2 );

		$output = sprintf( '%s %s veckor', self::get_local_day( $day ), $week % 2 == 0 ? 'jämna' : 'udda' );

		return $output;

	}

	/**
	 * Get day in local lang by run code.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $day
	 *
	 * @return string
	 */
	static function get_local_day( $day ){

		switch ( $day ) {
			case 'MÅ':
				$localize = 'måndagar';

				break;
			case 'TI':
				$localize = 'tisdagar';

				break;
			case 'ON':
				$localize = 'onsdagar';

				break;
			case 'TO':
				$localize = 'torsdagar';

				break;
			case 'FR':
				$localize = 'fredagar';

				break;
		}

		return $localize;

	}


	/**
	 * Printing out next date for garbage run.
	 * Not in use.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $run_code
	 */
	static function scheme_by_code( $run_code ){

		$date = '2017-04-08';
		$date = '2016-12-27';

		//echo date( 'Y-m-d', strtotime( 'next sunday +0 week', strtotime( $date ) ) );

		$run_code = trim( $run_code );

		//echo substr( $run_code, 0, 2 ) . '<br>';
		//var_dump( $run_code );
		$week = mb_substr( $run_code, -1 );
		$day = mb_substr( $run_code, -3, 2 );


		if( $week % 2 == 0 ) {
			$odd_or_even = 0;
		}else {
			$odd_or_even = 1;
		}

		//echo $day;
		switch ( $day ) {
			case 'MÅ':
				$day_full = 'monday';
				$output = printf( 'måndagar %s veckor', $week % 2 == 0 ? 'jämna' : 'udda' );

				break;
			case 'TI':
				$day_full = 'tuesday';

				break;
			case 'ON':
				$day_full = 'wednesday';

				break;
			case 'TO':
				$day_full = 'thursday';

				break;
			case 'FR':
				$day_full = 'friday';

				break;
		}

		$current_week =  (int) date('W', strtotime( $date ));

		/*
		if( $week % 2 == 0 ) {
			if( $current_week % 2 == 0 ){
				$next_run = date('Y-m-d', strtotime('next ' . $day_full . ' +0 week', strtotime( $date . '-1 day'  )));
			}else{
				$next_run = date('Y-m-d', strtotime('next ' . $day_full . ' +1 week', strtotime( $date . '-1 day'  )));
			}

		}else{
			if( $current_week % 2 == 0 ){
				$next_run = date('Y-m-d', strtotime('next ' . $day_full . ' +0 week', strtotime( $date . '-1 day'  )));
			}else{
				$next_run = date('Y-m-d', strtotime('next ' . $day_full . ' +1 week', strtotime( $date . '-1 day'  )));
			}
		}
*/
		/*
		echo date('Y-m-d', strtotime( 'monday this week'));
		if( strtotime( date('Y-m-d') ) === strtotime( $day_full . ' this week') ){
				$next_run = date('Y-m-d W');
		}else{

				if( $week == 1 ) {
					if( $current_week % 2 == 0 )
						$next_run = 'x' . date( 'Y-m-d W', strtotime( 'next ' . $day_full . ' +0 week' ) );
					else
						$next_run = 'y' . date( 'Y-m-d W', strtotime( 'next ' . $day_full . ' +1 week' ) );
				}else {
					if( $current_week % 2 == 0 )
						$next_run = 'z' . date( 'Y-m-d W', strtotime( 'next ' . $day_full . ' +1 week' ) );
					else
						$next_run = 'c' . date( 'Y-m-d W', strtotime( 'next ' . $day_full . ' +0 week' ) );
				}



		}


		$next_run = date( 'Y-m-d W', strtotime( 'next ' . $day_full . ' +0 week' ) );
		if( $week == 1 ){

		}
*/

		//echo date('Y-m-d', strtotime($day_full . ' this week'));

		//$current_week =  (int) date('W', strtotime( $date ));
		//echo $next = date('Y-m-d', strtotime('next '.$day_full, strtotime( $date . '-1 day' ) ) );
/*
		if( $current_week % 2 == 0 && $week %2 == 0 )
				$temp = date('Y-m-d', strtotime('next '.$day_full .' +0 week', strtotime( $date . '-1 day' ) ) );
			else
				$temp = date('Y-m-d', strtotime('next '.$day_full .' +1 week', strtotime( $date . '-1 day' ) ) );
*/





		$scheme['code'] = $run_code;
		$scheme['day'] = $day_full;
		$scheme['odd_or_even'] = $odd_or_even;
		$scheme['next_run'] = $next_run;


		//util::debug( $scheme );

	}


	private function create_db_table() {
		global $wpdb;
		$wpdb->garbage_scheme = $wpdb->prefix . 'garbage_scheme';

		// create table if not exists
		if ( $wpdb->get_var( "show tables like '$wpdb->garbage_scheme'" ) != $wpdb->garbage_scheme ) {

			$sql = "CREATE TABLE " . $wpdb->garbage_scheme . " (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`street_address` varchar(100) DEFAULT NULL,
			`extra` varchar(100) DEFAULT NULL,
			`real_estate_type` varchar(20) DEFAULT NULL,
			`run_list_code` varchar(20) DEFAULT NULL,
			`run_list_name` varchar(20) DEFAULT NULL,
			`last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY id (id)
			)";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

		}

	}

	/**
	 * Populate the table with data.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public static function populate_data() {
		global $wpdb;
		$items = self::get_data();

		//util::debug( $items );


		if ( ! empty( $items ) ) {
			$query = "TRUNCATE $wpdb->garbage_scheme";
			$wpdb->query( $query );
			echo "X";
			foreach ( $items as $key => $item ) {


				/* need to find a quicker query for this.
				if( $key < 10 ) {
					//echo $key.'<br>';
					$query = "INSERT INTO $wpdb->garbage_scheme (street_address, extra, real_estate_type, run_list_code, run_list_name, last_updated) 
VALUES ('{$item[0]}','{$item[1]}','{$item[2]}','{$item[3]}','{$item[4]}',  NOW() )";
					//echo $query .'<br>';
					$wpdb->query( $query );
				}
				*/
				if( substr( $item[4], 0, 2 ) === 'KP' ) {
					$wpdb->insert(
						$wpdb->garbage_scheme,
						array(
							'street_address'   => $item[0],
							'extra'            => $item[1],
							'real_estate_type' => $item[2],
							'run_list_code'    => $item[3],
							'run_list_name'    => $item[4],
							'last_updated'     => current_time( 'mysql', 1 )
						)
					);
				}

			}

		}

	}


	public static function get_data() {
		$file  = get_field( 'msva_garbage_scheme_file', 'options' );
		$lines = file( $file );
		foreach ( $lines as $line ) {
			$rows[] = explode( ';', $line );
		}

		$data = array();
		foreach ( $rows as $key => $row ) {

			$data[ $key ][] = utf8_encode( $row[0] . ' ' . $row[1] );
			$data[ $key ][] = $row[2];
			$data[ $key ][] = $row[3];
			$data[ $key ][] = $row[4];
			$data[ $key ][] = utf8_encode( trim( $row[5] ) );
		}

		return $data;

	}


}