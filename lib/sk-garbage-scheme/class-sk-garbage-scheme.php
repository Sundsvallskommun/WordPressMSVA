<?php

require_once 'class-sk-garbage-scheme-public.php';
require_once 'class-sk-garbage-scheme-admin.php';

class SK_Garbage_Scheme {

	/**
	 * SK_Garbage_Scheme constructor.
	 */
	function __construct() {
		add_action('after_setup_theme', function () {
			$this->init();
		});

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init(){
		$this->create_db_table();
		//$this->populate_data();


		add_action('init', array($this, 'importer'));
		add_action( 'wp_ajax_garbage_run', array( $this, 'ajax_garbage_run') );
		add_action( 'wp_ajax_nopriv_garbage_run', array( $this, 'ajax_garbage_run') );
		//$this->get_data();
		//$this->get_scheme();
		$sk_garbage_scheme_public = new SK_Garbage_Scheme_Public();
		$sk_garbage_scheme_public = new SK_Garbage_Scheme_Public();


	}

	public function importer(){
		if(isset( $_GET['import_run']))
			self::populate_data();

	}



	static function get_addresses( $print = false ){
		global $wpdb;

		$sql = "SELECT DISTINCT street_address FROM $wpdb->garbage_scheme ORDER BY street_address ASC";
		$results = $wpdb->get_results( $sql );

		//util::Debug( json_encode( $results ));

		if( empty($results) )
			return false;

		if( $print ){
			echo json_encode( $results );
			//util::debug( json_encode( $results ));
			//foreach ($results as $result){
				//echo $result->street_address . ',';
			//}
		}else{
			return $results;
		}





	}

	public function ajax_garbage_run(){
		if(isset( $_POST['address']) ){
			$address = $_POST['address'];
		}


		if(empty( $address ))
			return 'Något har gått fel med ...';

		$results = self::get_item_by_address( $address );

		?>
			<div class="widget-garbage-scheme__response-close"><a href="#"><?php material_icon( 'cancel', array('size' => '1.5em') ); ?></a></div>
		<?php foreach ( $results as $result ) : ?>
			<div><?php material_icon( 'local shipping', array('size' => '1.5em') ); ?><?php _e('Nästa gång sopbilen kommer till din adress är ', 'msva');?> <span class="run-date"><?php echo $result->run_list_name; ?></span></div>
		<?php endforeach; ?>

		<?php


		die();
	}

	static function get_item_by_address( $address = '' ){
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



	private function create_db_table(){
		global $wpdb;
		$wpdb->garbage_scheme = $wpdb->prefix . 'garbage_scheme';

		// create table if not exists
		if( $wpdb->get_var("show tables like '$wpdb->garbage_scheme'") != $wpdb->garbage_scheme ){

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

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

		}

	}

	/**
	 * Populate the table with data.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public static function populate_data(){
		global $wpdb;
		$items = self::get_data();

		//util::debug( $items );



		if(! empty( $items )){
			$query = "TRUNCATE $wpdb->garbage_scheme";
			$wpdb->query( $query );
			echo "X";
			foreach( $items as $key => $item ){


				/* need to find a quicker query for this.
				if( $key < 10 ) {
					//echo $key.'<br>';
					$query = "INSERT INTO $wpdb->garbage_scheme (street_address, extra, real_estate_type, run_list_code, run_list_name, last_updated) 
VALUES ('{$item[0]}','{$item[1]}','{$item[2]}','{$item[3]}','{$item[4]}',  NOW() )";
					//echo $query .'<br>';
					$wpdb->query( $query );
				}
				*/

					$wpdb->insert(
						$wpdb->garbage_scheme,
						array(
							'street_address' => $item[0],
							'extra' => $item[1],
							'real_estate_type' => $item[2],
							'run_list_code' => $item[3],
							'run_list_name' => $item[4],
							'last_updated' => current_time('mysql', 1)
						)
					);

			}

		}

	}

/*

	public function get_scheme(){
		$rows = get_option( 'msva_garbage_scheme', 'options' );

		foreach ( $rows as $row ) {
			echo $row[0];
		}

		//util::debug( $rows );
	}
*/


	public static function get_data(){
		$file = get_field('msva_garbage_scheme_file', 'options');
		$lines = file( $file );
		foreach ( $lines as $line ) {
			$rows[] = explode(';', $line );
		}

		$data = array();
		foreach ( $rows as $key => $row ){

				$data[ $key ][] = utf8_encode( $row[0] . ' ' . $row[1] );
				$data[ $key ][] = $row[2];
				$data[ $key ][] = $row[3];
				$data[ $key ][] = $row[4];
				$data[ $key ][] = utf8_encode( trim( $row[5] ) );
		}

		return $data;

	}



}