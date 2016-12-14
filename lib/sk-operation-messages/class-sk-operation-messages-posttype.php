<?php

/**
 * Wrapper class for the Operation Messages post type
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Operation_Messages_Posttype {

	/**
	 * Register the post type
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_posttype() {

		register_post_type( 'operation_message', $this->posttype_arguments() );

	}


	/**
	 * The posttype arguments
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return array    the arguments
	 */
	private function posttype_arguments() {

		return array(
			'labels'             => $this->posttype_labels(),
			'description'        => __( 'Description.', 'msva' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'operation_message' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' )
		);

	}


	/**
	 * The labels for the custom post type
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @return array    the labels
	 *
	 */
	private function posttype_labels() {

		return array(
			'name'               => _x( 'Driftmeddelanden', 'post type general name', 'msva' ),
			'singular_name'      => _x( 'Driftmeddelande', 'post type singular name', 'msva' ),
			'menu_name'          => _x( 'Driftmeddelanden', 'admin menu', 'msva' ),
			'name_admin_bar'     => _x( 'Driftmeddelanden', 'add new on admin bar', 'msva' ),
			'add_new'            => _x( 'Nytt meddelande', 'operation_message', 'msva' ),
			'add_new_item'       => __( 'Lägg till nytt Driftmeddelande', 'msva' ),
			'new_item'           => __( 'Nytt meddelande', 'msva' ),
			'edit_item'          => __( 'Ändra Driftmeddelande', 'msva' ),
			'view_item'          => __( 'Visa Driftmeddelande', 'msva' ),
			'all_items'          => __( 'Alla meddelanden', 'msva' ),
			'search_items'       => __( 'Sök Driftmeddelanden', 'msva' ),
			'parent_item_colon'  => __( 'Nuvarande Driftmeddelande:', 'msva' ),
			'not_found'          => __( 'Inga Driftmeddelanden funna.', 'msva' ),
			'not_found_in_trash' => __( 'Inga Driftmeddelanden funna i papperskorgen.', 'msva' )
		);


	}


	public function add_meta_boxes() {

		add_meta_box( 'operation_messages_meta', __( 'Sparad information', 'msva' ), array( $this, 'metabox_callback' ), 'operation_message' );

	}


	public function metabox_callback() {
		?>
		<div class="operation-message-metabox-wrapper">

			<div class="operation-message-metabox-section">

				<h3><?php _e( 'Händelse' ); ?></h3>
				<hr />

				<select name="operation_message[event]">
					<option value="0"><?php _e( 'Välj händelse...', 'msva' ); ?></option>
					<option value="Vattenläcka"><?php _e( 'Vattenläcka', 'msva' ); ?></option>
					<option value="Vattenavstängning"><?php _e( 'Vattenavstängning', 'msva' ); ?></option>
					<option value="Spolning av avloppsledningar"><?php _e( 'Spolning av avloppsledningar', 'msva' ); ?></option>
					<option value="Spolning av vattenledningar"><?php _e( 'Spolning av vattenledningar', 'msva' ); ?></option>
					<option value="Vattenläcka åtgärdad"><?php _e( 'Vattenläcka åtgärdad' ); ?></option>
					<option value="1"><?php _e( 'Egen händelse', 'msva' ); ?></option>
				</select>

				<label><?php _e( 'Egen händelse', 'msva' ); ?>
					<input type="text" name="operation_message[custom_event]" />
				</label>

			</div>

			<div class="operation-message-metabox-information-section">

				<h3><?php _e( 'Information och område', 'msva' ); ?></h3>
				<hr />

				<label><?php _e( 'Information del 1', 'msva' ); ?></label>
				<?php
					$wp_editor_args = array (
						'tinymce' => false,
						'quicktags' => true,
						'media_buttons' => false,
						'textarea_rows' => 6,

					);
					wp_editor( '', 'operation_message[information_part_1]', $wp_editor_args );
				?>

				<label for="area_street"><?php _e( 'Område/Gata', 'msva' ); ?></label><br />
				<input type="text" id="area_street" name="operation_message[area_street]" />
				<br />

				<label><?php _e( 'Information del 2', 'msva' ); ?></label>
				<?php wp_editor( '', 'operation_message[information_part_2]', $wp_editor_args ); ?>

				<label><?php _e( 'Avslut', 'msva' ); ?></label>
				<?php wp_editor( '', 'operation_message[ending]', $wp_editor_args ); ?>

			</div>

			<div class="operation-message-metabox-publishing-section">

				<h3><?php _e( 'Publicering och Arkivering', 'msva' ); ?></h3>
				<hr />

				<label><?php _e( 'Publicering', 'msva' ); ?></label>
				<input type="text" name="operation_message[publish_at_date]" />

                <select name="operation_message[publish_at_hour]">
                    <option value="00">00</option>
                    <option value="00">01</option>
                    <option value="00">02</option>
                    <option value="00">03</option>
                    <option value="00">04</option>
                    <option value="00">05</option>
                    <option value="00">06</option>
                    <option value="00">07</option>
                    <option value="00">08</option>
                    <option value="00">09</option>
                    <option value="00">10</option>
                    <option value="00">11</option>
                    <option value="00">12</option>
                    <option value="00">13</option>
                    <option value="00">14</option>
                    <option value="00">15</option>
                    <option value="00">16</option>
                    <option value="00">17</option>
                    <option value="00">18</option>
                    <option value="00">19</option>
                    <option value="00">20</option>
                    <option value="00">21</option>
                    <option value="00">22</option>
                    <option value="00">23</option>
                </select>

                <select name="operation_message[publish_at_minute]">
                    <option value="00">00</option>
                    <option value="00">10</option>
                    <option value="00">20</option>
                    <option value="00">30</option>
                    <option value="00">40</option>
                    <option value="00">50</option>
                </select>
				<br />

				<label><?php _e( 'Arkivering', 'msva' ); ?></label>
                <input type="text" name="operation_message[archive_at_date]" />

                <select name="operation_message[archive_at_hour]">
                    <option value="00">00</option>
                    <option value="00">01</option>
                    <option value="00">02</option>
                    <option value="00">03</option>
                    <option value="00">04</option>
                    <option value="00">05</option>
                    <option value="00">06</option>
                    <option value="00">07</option>
                    <option value="00">08</option>
                    <option value="00">09</option>
                    <option value="00">10</option>
                    <option value="00">11</option>
                    <option value="00">12</option>
                    <option value="00">13</option>
                    <option value="00">14</option>
                    <option value="00">15</option>
                    <option value="00">16</option>
                    <option value="00">17</option>
                    <option value="00">18</option>
                    <option value="00">19</option>
                    <option value="00">20</option>
                    <option value="00">21</option>
                    <option value="00">22</option>
                    <option value="00">23</option>
                </select>

                <select name="operation_message[archive_at_minute]">
                    <option value="00">00</option>
                    <option value="00">10</option>
                    <option value="00">20</option>
                    <option value="00">30</option>
                    <option value="00">40</option>
                    <option value="00">50</option>
                </select>
				<br />


			</div>



		</div>
		<?php
	}

}