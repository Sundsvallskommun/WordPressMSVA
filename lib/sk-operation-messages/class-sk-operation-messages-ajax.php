<?php


class SK_Operation_Messages_Ajax {


	public function callback() {

		$params = array();
		parse_str( $_POST['form_data'], $params );

		if ( ! wp_verify_nonce( $params['nonce'], 'sk-operation-messages' ) ) {
			die( 'Hey hey, stop messing around!!!' );
		}

		// Check OK. Save the message.
		if ( $this->save_message( $params ) ) {

			wp_send_json_success(
				'Worked!'
			);

		}

		wp_send_json_error( 'Failed!' );

	}


	private function save_message( $params ) {

		// Setup the author, slug, and title for the post
		$author_id = get_current_user_id();
		if( $author_id == 0 ) return false; // Not logged in

		$title = $this->create_title( $params['operation_message'] );

		$args = array(
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_author'    => $author_id,
			'post_name'      => sanitize_title( $title ),
			'post_title'     => $title,
			'post_status'    => 'publish',
			'post_type'      => 'operation_message'
		);

		if ( ! empty( $params['operation_message']['om_publish_at_date'] ) ) {

			$string_time = $params['operation_message']['om_publish_at_date'] . ' ' . $params['operation_message']['om_publish_at_hour'] . ':' . $params['operation_message']['om_publish_at_minute'];
			$publish_time = strtotime( $string_time );
			$publish_time = date_i18n( 'Y-m-d H:i:s', $publish_time );
			$publish_time_gmt = date_i18n( 'Y-m-d H:i:s', strtotime( $string_time . ' -1 hours' ) );

			$args['post_date'] = $publish_time;
			$args['post_date_gmt'] = $publish_time_gmt;

		}

		// If the page doesn't already exist, then create it
		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			$args,
			true // return error on failure
		);


		if ( ! is_wp_error( $post_id ) ) {

			if ( count( $params ) > 0 && is_array( $params ) ) {

				foreach ( $params['operation_message'] as $key => $value ) {

					update_post_meta( $post_id, $key, sanitize_text_field( $value ) );

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

		return sanitize_text_field( $title );

	}

}