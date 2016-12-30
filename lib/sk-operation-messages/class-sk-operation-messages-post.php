<?php

/**
 * This class wraps the regular
 * post object with some specifi operation message
 * logic.
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Operation_Messages_Post {

	public $post;

	/**
	 * Set the private variables.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct( $post ) {

		$this->post = $post;

	}


	public function title() {

		return $this->post->post_title;

	}

	public function om_event() {

		return $this->post->meta['om_event'];

	}


	public function created_at() {

		return $this->post->post_date;

	}



	/**
	 * Create an instance of the class from
	 * a given post ID.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param integer   the post id
	 *
	 * @return object   the message object
	 */
	public static function fromId( $post_id ) {

		$post       = get_post( $post_id );
		$post->meta = array();
		$meta       = get_post_custom( $post_id );

		if ( is_array( $meta ) && count( $meta ) > 0 ) {

			foreach ( $meta as $key => $value ) {

				$post->meta[ $key ] = $value[0];

			}

		}

		$message       = new SK_Operation_Messages_Post( $post );
		$message->post = $post;

		return $message;

	}


	/**
	 * Get the title of the event
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string   the title
	 */
	public function event_title() {

		if ( ! empty( $this->post->meta['om_custom_event'] ) ) {
			return $this->post->meta['om_custom_event'];
		}

		return $this->post->meta['om_event'];

	}


	/**
	 * Get the information part one.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string   the information
	 */
	public function info_1() {

		if ( ! empty( $this->post->meta['om_information_part_1'] ) ) {
			return nl2br( $this->post->meta['om_information_part_1'] );
		}

		return '';

	}


	/**
	 * Get the information part two.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string   the information
	 */
	public function info_2() {

		if ( ! empty( $this->post->meta['om_information_part_2'] ) ) {
			return nl2br( $this->post->meta['om_information_part_2'] );
		}

		return '';

	}


	/**
	 * Get the ending.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string   the ending
	 */
	public function ending() {

		if ( ! empty( $this->post->meta['om_ending'] ) ) {
			return nl2br( $this->post->meta['om_ending'] );
		}

		return '';

	}


	/**
	 * Get the street or area
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string   the street or area
	 */
	public function street() {

		if ( ! empty( $this->post->meta['om_area_street'] ) ) {
			return $this->post->meta['om_area_street'];
		}

		return '';

	}

}