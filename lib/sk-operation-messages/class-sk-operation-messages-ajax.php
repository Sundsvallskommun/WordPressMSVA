<?php


class SK_Operation_Messages_Ajax {


	public function callback() {

		$params = array();
		parse_str( $_POST['form_data'], $params );

		if ( ! wp_verify_nonce( $params['nonce'], 'sk-operation-messages' ) ) {
			die( 'Hey hey, stop messing around!!!' );
		}

		// Check OK. Save the message.
		if ( $this->save_message( $params, $errand_text ) ) {

			wp_send_json_success(
				'Worked!'
			);

		}

		wp_send_json_error( 'Failed!' );

	}


	private function save_message( $params, $errand_text ) {

		// Initialize the page ID to -1. This indicates no action has been taken.
		$post_id = - 1;

		// Setup the author, slug, and title for the post
		$author_id = 1;
		$slug      = 'example-post';


		$title = $this->create_title( $params['operation_message'] );

		// If the page doesn't already exist, then create it
		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(

			array(
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_author'    => $author_id,
				'post_name'      => sanitize_title( $title ),
				'post_title'     => $title,
				'post_status'    => 'publish',
				'post_type'      => 'operation_message'
			),
			true // return error on failure
		);


		if ( ! is_wp_error( $post_id ) ) {

			if ( count( $params ) > 0 && is_array( $params ) ) {

				foreach ( $params['operation_message'] as $key => $value ) {

					update_post_meta( $post_id, $key, $value );

				}

				return true;

			}

		}

		return false;

	}


	private function create_title( $params ) {

		$title = ! empty( $params['om_custom_event'] ) ? $params['om_custom_event'] : '';
		if ( empty( $title ) ) {
			$title = $params['om_event'];
		}

		$street = ! empty( $params['om_area_street'] ) ? $params['om_area_street'] : '';
		if ( ! empty( $street ) ) {
			$title .=  ' - ' . $street;
		}

		return $title;

	}

}