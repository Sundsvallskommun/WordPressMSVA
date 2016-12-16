<?php


class SK_Operation_Messages_Post {

	private $post;

	public function __construct( $post ) {

		$this->post = $post;

	}

	public static function fromId( $post_id ) {

		$post = get_post( $post_id );
		$post->meta = array();
		$meta = get_post_custom( $post_id );

		if( is_array( $meta ) && count( $meta ) > 0 ) {

			foreach( $meta as $key => $value ) {

				$post->meta[$key] = $value[0];

			}

		}

		$message = new SK_Operation_Messages_Post( $post );
		$message->post = $post;

		return $message;

	}

	
	public function event_title() {
		
		if( ! empty( $this->post->meta['om_custom_event'] ) ) {
			return $this->post->meta['om_custom_event'];
		}

		return $this->post->meta['om_event'];
		
	}


	public function info_1() {

		if( ! empty( $this->post->meta['om_information_part_1'] ) ) {
			return nl2br( $this->post->meta['om_information_part_1'] );
		}

		return '';

	}


	public function info_2() {

		if( ! empty( $this->post->meta['om_information_part_2'] ) ) {
			return nl2br( $this->post->meta['om_information_part_2'] );
		}

		return '';

	}


	public function ending() {

		if( ! empty( $this->post->meta['om_ending'] ) ) {
			return nl2br( $this->post->meta['om_ending'] );
		}

		return '';

	}

	public function street() {

		if( ! empty( $this->post->meta['om_area_street'] ) ) {
			return $this->post->meta['om_area_street'];
		}

		return '';

	}

}