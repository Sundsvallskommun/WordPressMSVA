<?php

/**
 * Class wrapping municipality adaption cookie functionality
 *
 * @package    SK_Municipality_Adaptation
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Municipality_Adaptation_Cookie {

	/**
	 * Check if cookie exists
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return boolean
	 */
	public static function exists() {

		if ( isset( $_COOKIE['municipality_adaptation'] ) ) return true;

		return false;

	}


	/**
	 * Get the cookie value
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string
	 */
	public static function value() {

		if( self::exists() ) return $_COOKIE['municipality_adaptation'];

	}

	/**
	 * Nice name print for cookie value
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return string
	 */
	public static function print_value() {

		$nice_name = '';

		if( self::exists() ){

			switch ( strtolower( $_COOKIE['municipality_adaptation'] ) ) {
				case 'sundsvall':
					$nice_name = 'Sundsvall';
					break;
				case 'nordanstig':
					$nice_name = 'Nordanstig';
					break;
				case 'timra':
					$nice_name = 'Timrå';
					break;
			}

		}else{
			$nice_name = 'N/A';
		}

		return $nice_name;


	}


	/**
	 * Check if cookie value matches value on posts's
	 * community connections.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $values   string|array
	 *
	 * @return boolean
	 */
	public static function match( $values ) {

		if ( ! self::exists() ) return false;
		if ( ! is_array( $values ) || count( $values ) == 0 ) return true;
		if ( in_array( self::value(), $values ) ) return true;

		return false;

	}

}