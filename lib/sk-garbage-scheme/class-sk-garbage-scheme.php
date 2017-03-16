<?php

require_once 'class-sk-garbage-scheme-public.php';

//require_once 'class-sk-garbage-scheme-admin.php';

class SK_Garbage_Scheme {

	private $hash = '8fb6898754b3b8c9ab734c979d4e1095';

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
		$sk_garbage_scheme_public = new SK_Garbage_Scheme_Public();

		add_action( 'init', array( $this, 'importer' ) );
		add_action( 'wp_ajax_garbage_run', array( $this, 'ajax_garbage_run' ) );
		add_action( 'wp_ajax_nopriv_garbage_run', array( $this, 'ajax_garbage_run' ) );

		add_action( 'msva_scheduled_import', array( $this, 'msva_scheduled_import' ) );

		// check if already scheduled
		$timestamp = wp_next_scheduled( 'msva_scheduled_import' );
		if ( $timestamp == false ) {
			wp_schedule_event( time(), 'hourly', 'msva_scheduled_import' );
		}


	}

	/**
	 * Method that runs on scheduled event.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function msva_scheduled_import() {
		self::populate_data();
	}

	/**
	 * Method to check if theres a request for import.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function importer() {
		if ( isset( $_GET['importer'] ) && $_GET['importer'] === $this->hash ) {
			self::populate_data();
		}
	}

	/**
	 * Get addresses to typeahead form.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param bool $print
	 *
	 * @return array|bool
	 */
	static function get_addresses( $print = false ) {
		global $wpdb;

		$sql     = "SELECT DISTINCT street_address, zip_code FROM $wpdb->garbage_scheme ORDER BY street_address ASC";
		$results = $wpdb->get_results( $sql );


		if ( empty( $results ) ) {
			return false;
		}

		if ( $print ) {
			echo json_encode( $results );
		} else {
			return $results;
		}


	}

	/**
	 * Print output from ajax call.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return bool
	 */
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
					<?php material_icon( 'local shipping', array( 'size' => '1.5em' ) ); ?><?php _e( sprintf( 'Dina sopor hämtas på %s. Nu är det vecka %d.', self::scheme_output( $result->run_list_name ), (int) date( 'W' ) ), 'msva' ); ?>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div><?php material_icon( 'local shipping', array( 'size' => '1.5em' ) ); ?><?php _e( 'Hittar ingen tid i ditt område', 'msva' ); ?></div>
		<?php endif; ?>

		<?php


		die();
	}

	/**
	 * Get data for a single address
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param string $address
	 *
	 * @return array
	 */
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
	 * Get printer friendly run scheme by run code.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $run_code
	 *
	 * @return string
	 */
	static function scheme_output( $run_code ) {
		$run_code = trim( $run_code );
		$week     = mb_substr( $run_code, - 1 );
		$day      = mb_substr( $run_code, - 3, 2 );

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
	static function get_local_day( $day ) {

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

		if ( ! empty( $items ) ) {
			$query = "TRUNCATE $wpdb->garbage_scheme";
			$wpdb->query( $query );
			foreach ( $items as $key => $item ) {

				if ( substr( $item[4], 0, 2 ) === 'KP' ) {
					$wpdb->insert(
						$wpdb->garbage_scheme,
						array(
							'street_address'   => $item[0],
							'extra'            => $item[1],
							'real_estate_type' => $item[2],
							'run_list_code'    => $item[3],
							'run_list_name'    => $item[4],
							'last_updated'     => current_time( 'mysql', 1 ),
                            'zip_code'         => $item[5]
						)
					);
				}

			}

		}

	}

	/**
	 * Get data from the csv file.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return array|bool
	 */
	public static function get_data() {
		$file = get_field( 'msva_garbage_scheme_file', 'options' );

		if ( ! file_exists( $file ) ) {
			update_option( 'msva_garbage_scheme_log', 'ERROR: importfil saknas. ' . $file );

			return false;
		}

		$last_updated = get_option( 'msva_garbage_scheme_log' );
		if ( date( 'Y-m-d H:i:s', filemtime( $file ) ) === $last_updated ) {
			return false;
		}


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
			$data[ $key ][] = $row[6];
		}

		// update timestamp for file.
		update_option( 'msva_garbage_scheme_log', date( 'Y-m-d H:i:s', filemtime( $file ) ) );

		return $data;

	}


}