<?php

/**
 * Ajax handler class for the frontend form.
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Operation_Messages_Ajax {


	/**
	 * The callback function when posting the form via ajax.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function callback() {

		$params = array();
		parse_str( $_POST['form_data'], $params );

		if ( ! wp_verify_nonce( $params['nonce'], 'sk-operation-messages' ) ) {
			die( 'Hey hey, stop messing around!!!' );
		}

		if ( isset( $params['om_message_id'] ) ) {

			if( $this->update_message( $params['om_message_id'], $params ) ) {

				wp_send_json_success(
					'Updated!!'
				);

			}

		}

		// Check OK. Save the message.
		if ( $this->save_message( $params ) ) {

			wp_send_json_success(
				'Created!'
			);

		}

		wp_send_json_error( 'Failed!' );

	}


	/**
	 * Setup data and save an new post of the
	 * post type operation_message. Also saves the meta data
	 * connected to the post.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param array     the form data
	 *
	 * @return boolean  the result
	 */
	private function update_message( $id, $params ) {

		// Setup the author, slug, and title for the post
		$author_id = get_current_user_id();
		if ( $author_id == 0 || ! current_user_can( 'edit_posts' ) ) {
			return false;
		} // Not logged in

		$title = $this->create_title( $params['operation_message'] );

		$args = array(
			'ID'             => $id,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_author'    => $author_id,
			'post_name'      => sanitize_title( $title ),
			'post_title'     => $title,
			'post_status'    => 'publish',
			'post_type'      => 'operation_message'
		);

		if ( ! empty( $params['operation_message']['om_publish_at_date'] ) ) {

			$string_time      = $params['operation_message']['om_publish_at_date'] . ' ' . $params['operation_message']['om_publish_at_hour'] . ':' . $params['operation_message']['om_publish_at_minute'];
			$publish_time     = strtotime( $string_time );
			$publish_time     = date_i18n( 'Y-m-d H:i:s', $publish_time );
			$publish_time_gmt = date_i18n( 'Y-m-d H:i:s', strtotime( $string_time . ' -1 hours' ) );

			$args['post_date']     = $publish_time;
			$args['post_date_gmt'] = $publish_time_gmt;

		}

		if ( empty( $params['operation_message']['om_custom_municipality'] ) ) {

			if ( in_array( 'operation_message', SK_Municipality_Adaptation_Settings::valid_post_types() ) ) {

				$municipality = sanitize_title( $params['operation_message']['om_municipality'] );
				$municipality = strtolower( $municipality );

			}

		}


		// Update the post
		$post_id = wp_update_post( $args );
		
		if ( ! is_wp_error( $post_id ) ) {

			if ( count( $params ) > 0 && is_array( $params ) ) {

				foreach ( $params['operation_message'] as $key => $value ) {

					update_post_meta( $post_id, $key, strip_tags( $value, '<p><b><strong>' ) );

					if ( isset( $municipality ) ) {

						update_post_meta( $post_id, 'municipality_adaptation', $municipality );

					}

				}

				return true;

			}

		}

		return false;

	}


	/**
	 * Setup data and save an new post of the
	 * post type operation_message. Also saves the meta data
	 * connected to the post.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param array     the form data
	 *
	 * @return boolean  the result
	 */
	private function save_message( $params ) {

		// Setup the author, slug, and title for the post
		$author_id = get_current_user_id();
		if ( $author_id == 0 || ! current_user_can( 'edit_posts' ) ) {
			return false;
		} // Not logged in

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

			$string_time      = $params['operation_message']['om_publish_at_date'] . ' ' . $params['operation_message']['om_publish_at_hour'] . ':' . $params['operation_message']['om_publish_at_minute'];
			$publish_time     = strtotime( $string_time );
			$publish_time     = date_i18n( 'Y-m-d H:i:s', $publish_time );
			$publish_time_gmt = date_i18n( 'Y-m-d H:i:s', strtotime( $string_time . ' -1 hours' ) );

			$args['post_date']     = $publish_time;
			$args['post_date_gmt'] = $publish_time_gmt;

		}

		if ( empty( $params['operation_message']['om_archive_at_date'] ) ) {

			$tomorrow = date_i18n( 'Y-m-d', strtotime( '+1 days' ) );

		}

		if ( empty( $params['operation_message']['om_custom_municipality'] ) ) {

			if ( in_array( 'operation_message', SK_Municipality_Adaptation_Settings::valid_post_types() ) ) {

				$municipality = sanitize_title( $params['operation_message']['om_municipality'] );
				$municipality = strtolower( $municipality );

			}

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


					update_post_meta( $post_id, $key, strip_tags( $value, '<p><b><strong>' ) );
					if ( isset( $tomorrow ) ) {

						update_post_meta( $post_id, 'om_archive_at_date', $tomorrow );
						update_post_meta( $post_id, 'om_archive_at_hour', '16' );
						update_post_meta( $post_id, 'om_archive_at_minute', '00' );

					}

					if ( isset( $municipality ) ) {

						update_post_meta( $post_id, 'municipality_adaptation', $municipality );

					}

				}

				return true;

			}

		}

		return false;

	}


	/**
	 * Creates the post title from different
	 * form field values.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param array     the form data
	 *
	 * @return string   the post title
	 */
	private function create_title( $params ) {

		$title = ! empty( $params['om_title'] ) ? $params['om_title'] : '';

		if ( empty( $title ) ) {

			$title = ! empty( $params['om_custom_event'] ) ? $params['om_custom_event'] : '';
			if ( empty( $title ) ) {
				$title = $params['om_event'];
			}

			$street = ! empty( $params['om_area_street'] ) ? $params['om_area_street'] : '';
			if ( ! empty( $street ) ) {
				$title .= ' - ' . $street;
			}

		}

		return sanitize_text_field( $title );

	}

}