<?php


class SK_Operation_Messages_Ajax {


	public function callback() {

		$params = array();
		parse_str( $_POST['form_data'], $params );

		$errand_text = $_POST['om_handelse'];

		if ( ! wp_verify_nonce( $params['nonce'], 'sk-operation-messages' ) ) {
			die( 'Hey hey, stop messing around!!!' );
		}

		if ( $this->save_message( $params, $errand_text ) ) {

			wp_send_json_success(
				'tjohoooo!!!!!'
			);

		}

		wp_send_json_error( 'Fel!' );

	}


	function save_message( $params, $errand_text ) {

		// Initialize the page ID to -1. This indicates no action has been taken.
		$post_id = - 1;

		// Setup the author, slug, and title for the post
		$author_id = 1;
		$slug      = 'example-post';

		// If the alternative errand is set, than use that.
		// Otherwise use the regular errand text.
		// Only use for the title. Not the actual ACF field.
		$title = $this->get_form_value( 'om_alt_handelse', $params['acf'] );
		if ( empty( $title ) ) {
			$title = $errand_text;
		}
		$street = $this->get_form_value( 'om_omradegata', $params['acf'] );
		if ( ! empty( $street ) ) {
			$title .=  ' - ' . $street;
		}


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

				foreach ( $params['acf'] as $key => $value ) {

					update_field( $key, $value, $post_id );

				}

				return true;

			}

		}

		return false;

	}


	private function get_form_value( $name, $form ) {

		return $form[ $this->acf_field_name_translations( $name ) ];

	}


	private function acf_field_name_translations( $key ) {

		$all = array(
			'om_handelse'           => 'field_584fe4b23b9cc',
			'om_alt_handelse'       => 'field_584fe50f3b9cd',
			'om_kommun'             => 'field_584fea99bc910',
			'om_alt_kommun_fritext' => 'field_584feafdbc911',
			'om_information'        => 'field_584fec9dcad6a',
			'om_omradegata'         => 'field_584fecf6cad6b',
			'om_avslut'             => 'field_584fefb2e2f5c',
			'om_publicering'        => 'field_584ff04be2f5e',
			'om_arkivering'         => 'field_584ff0cee2f5f'
		);

		return ! empty( $all[ $key ] ) ? $all[ $key ] : null;

	}

}