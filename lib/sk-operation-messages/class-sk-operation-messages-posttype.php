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
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'driftstorningar' ),
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


	/**
	 * Save meta data to message
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function save( $post_id ) {
		global $post;

		if ( $post->post_type != 'operation_message' ) {
			return;
		}

		// If this is a revision, get real post ID
		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$post_id = $parent_id;
		}

		if ( isset( $_POST['operation_message'] ) && is_array( $_POST['operation_message'] ) ) {

			foreach ( $_POST['operation_message'] as $key => $value ) {

				update_post_meta( $post_id, $key, sanitize_text_field( $value ) );

			}

		}

	}


	/**
	 * Add a new metabox to the post type admin page.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function add_meta_boxes() {

		add_meta_box( 'operation_messages_meta', __( 'Sparad information', 'msva' ), array(
			$this,
			'metabox_callback'
		), 'operation_message' );

	}


	/**
	 * Callback function to output the html
	 * for the metabox on the admin page for the
	 * custom post type.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return string    the html output
	 */
	public function metabox_callback( $post ) {

		// Load and setup meta data for the message
		$meta                = get_post_custom( $post->ID );
		$event               = $meta['om_event'][0];
		$custom_event        = $meta['om_custom_event'][0];
		$municipality        = $meta['om_municipality'][0];
		$custom_municipality = $meta['om_custom_municipality'][0];
		$info_1              = $meta['om_information_part_1'][0];
		$info_2              = $meta['om_information_part_2'][0];
		$street              = $meta['om_area_street'][0];
		$ending              = $meta['om_ending'][0];
		$publish_at_date     = $meta['om_publish_at_date'][0];
		$publish_at_hour     = $meta['om_publish_at_hour'][0];
		$publish_at_minute   = $meta['om_publish_at_minute'][0];
		$archive_at_date     = $meta['om_archive_at_date'][0];
		$archive_at_hour     = $meta['om_archive_at_hour'][0];
		$archive_at_minute   = $meta['om_archive_at_minute'][0];


		?>
        <div class="operation-message-metabox-wrapper">

            <div class="operation-message-metabox-section">

                <h3><?php _e( 'Händelse' ); ?></h3>
                <hr/>

                <select name="operation_message[om_event]">
                    <option value="0"><?php _e( 'Välj händelse...', 'msva' ); ?></option>
                    <option value="Vattenläcka" <?php selected( $event, 'Vattenläcka' ); ?>><?php _e( 'Vattenläcka', 'msva' ); ?></option>
                    <option value="Vattenavstängning" <?php selected( $event, 'Vattenavstängning' ); ?>><?php _e( 'Vattenavstängning', 'msva' ); ?></option>
                    <option value="Spolning av avloppsledningar" <?php selected( $event, 'Spolning av avloppsledningar' ); ?>><?php _e( 'Spolning av avloppsledningar', 'msva' ); ?></option>
                    <option value="Spolning av vattenledningar" <?php selected( $event, 'Spolning av vattenledningar' ); ?>><?php _e( 'Spolning av vattenledningar', 'msva' ); ?></option>
                    <option value="Vattenläcka åtgärdad" <?php selected( $event, 'Vattenläcka åtgärdad' ); ?>><?php _e( 'Vattenläcka åtgärdad' ); ?></option>
                    <option value="1" <?php selected( $event, 1 ); ?>><?php _e( 'Egen händelse', 'msva' ); ?></option>
                </select>

                <label><?php _e( 'Egen händelse', 'msva' ); ?>
                    <input type="text" name="operation_message[om_custom_event]" value="<?php echo $custom_event; ?>"/>
                </label>

            </div>

            <div class="operation-message-metabox-municipality-section">

                <h3><?php _e( 'Kommun' ); ?></h3>

                <select name="operation_message[om_municipality]">
                    <option value="Sundsvall" <?php selected( $municipality, 'Sundsvall' ); ?>>Sundsvall</option>
                    <option value="Timrå" <?php selected( $municipality, 'Timrå' ); ?>>Timrå</option>
                    <option value="Nordanstig" <?php selected( $municipality, 'Nordanstig' ); ?>>Nordanstig</option>
                </select>

                <label><?php _e( 'Alt. fri text', 'msva' ); ?></label>
                <input type="text" name="operation_message[om_custom_municipality]"
                       value="<?php echo $custom_municipality; ?>"/>

            </div>

            <div class="operation-message-metabox-information-section">

                <h3><?php _e( 'Information och område', 'msva' ); ?></h3>
                <hr/>

                <label><?php _e( 'Information del 1', 'msva' ); ?></label>
				<?php
				$wp_editor_args = array(
					'tinymce'       => false,
					'quicktags'     => true,
					'media_buttons' => false,
					'textarea_rows' => 6,

				);
				wp_editor( $info_1, 'operation_message[om_information_part_1]', $wp_editor_args );
				?>

                <label for="area_street"><?php _e( 'Område/Gata', 'msva' ); ?></label><br/>
                <input type="text" id="area_street" name="operation_message[om_area_street]"
                       value="<?php echo $street; ?>"/>
                <br/>

                <label><?php _e( 'Information del 2', 'msva' ); ?></label>
				<?php wp_editor( $info_2, 'operation_message[om_information_part_2]', $wp_editor_args ); ?>

                <label><?php _e( 'Avslut', 'msva' ); ?></label>
				<?php wp_editor( $ending, 'operation_message[om_ending]', $wp_editor_args ); ?>

            </div>

            <div class="operation-message-metabox-publishing-section">

                <h3><?php _e( 'Publicering och Arkivering', 'msva' ); ?></h3>
                <hr/>

                <label><?php _e( 'Publicering', 'msva' ); ?></label>
                <input type="text" name="operation_message[om_publish_at_date]"
                       value="<?php echo $publish_at_date; ?>"/>

                <select name="operation_message[om_publish_at_hour]">
                    <option value="00" <?php selected( $publish_at_hour, '00' ); ?>>00</option>
                    <option value="01" <?php selected( $publish_at_hour, '01' ); ?>>01</option>
                    <option value="02" <?php selected( $publish_at_hour, '02' ); ?>>02</option>
                    <option value="03" <?php selected( $publish_at_hour, '03' ); ?>>03</option>
                    <option value="04" <?php selected( $publish_at_hour, '04' ); ?>>04</option>
                    <option value="05" <?php selected( $publish_at_hour, '05' ); ?>>05</option>
                    <option value="06" <?php selected( $publish_at_hour, '06' ); ?>>06</option>
                    <option value="07" <?php selected( $publish_at_hour, '07' ); ?>>07</option>
                    <option value="08" <?php selected( $publish_at_hour, '08' ); ?>>08</option>
                    <option value="09" <?php selected( $publish_at_hour, '09' ); ?>>09</option>
                    <option value="10" <?php selected( $publish_at_hour, '10' ); ?>>10</option>
                    <option value="11" <?php selected( $publish_at_hour, '11' ); ?>>11</option>
                    <option value="12" <?php selected( $publish_at_hour, '12' ); ?>>12</option>
                    <option value="13" <?php selected( $publish_at_hour, '13' ); ?>>13</option>
                    <option value="14" <?php selected( $publish_at_hour, '14' ); ?>>14</option>
                    <option value="15" <?php selected( $publish_at_hour, '15' ); ?>>15</option>
                    <option value="16" <?php selected( $publish_at_hour, '16' ); ?>>16</option>
                    <option value="17" <?php selected( $publish_at_hour, '17' ); ?>>17</option>
                    <option value="18" <?php selected( $publish_at_hour, '18' ); ?>>18</option>
                    <option value="19" <?php selected( $publish_at_hour, '19' ); ?>>19</option>
                    <option value="20" <?php selected( $publish_at_hour, '20' ); ?>>20</option>
                    <option value="21" <?php selected( $publish_at_hour, '21' ); ?>>21</option>
                    <option value="22" <?php selected( $publish_at_hour, '22' ); ?>>22</option>
                    <option value="23" <?php selected( $publish_at_hour, '23' ); ?>>23</option>
                </select>

                <select name="operation_message[om_publish_at_minute]">
                    <option value="00" <?php selected( $publish_at_minute, '00' ); ?>>00</option>
                    <option value="10" <?php selected( $publish_at_minute, '10' ); ?>>10</option>
                    <option value="20" <?php selected( $publish_at_minute, '20' ); ?>>20</option>
                    <option value="30" <?php selected( $publish_at_minute, '30' ); ?>>30</option>
                    <option value="40" <?php selected( $publish_at_minute, '40' ); ?>>40</option>
                    <option value="50" <?php selected( $publish_at_minute, '50' ); ?>>50</option>
                </select>
                <br/>

                <label><?php _e( 'Arkivering', 'msva' ); ?></label>
                <input type="text" name="operation_message[om_archive_at_date]"
                       value="<?php echo $archive_at_date; ?>"/>

                <select name="operation_message[om_archive_at_hour]">
                    <option value="00" <?php selected( $archive_at_hour, '00' ); ?>>00</option>
                    <option value="01" <?php selected( $archive_at_hour, '01' ); ?>>01</option>
                    <option value="02" <?php selected( $archive_at_hour, '02' ); ?>>02</option>
                    <option value="03" <?php selected( $archive_at_hour, '03' ); ?>>03</option>
                    <option value="04" <?php selected( $archive_at_hour, '04' ); ?>>04</option>
                    <option value="05" <?php selected( $archive_at_hour, '05' ); ?>>05</option>
                    <option value="06" <?php selected( $archive_at_hour, '06' ); ?>>06</option>
                    <option value="07" <?php selected( $archive_at_hour, '07' ); ?>>07</option>
                    <option value="08" <?php selected( $archive_at_hour, '08' ); ?>>08</option>
                    <option value="09" <?php selected( $archive_at_hour, '09' ); ?>>09</option>
                    <option value="10" <?php selected( $archive_at_hour, '10' ); ?>>10</option>
                    <option value="11" <?php selected( $archive_at_hour, '11' ); ?>>11</option>
                    <option value="12" <?php selected( $archive_at_hour, '12' ); ?>>12</option>
                    <option value="13" <?php selected( $archive_at_hour, '13' ); ?>>13</option>
                    <option value="14" <?php selected( $archive_at_hour, '14' ); ?>>14</option>
                    <option value="15" <?php selected( $archive_at_hour, '15' ); ?>>15</option>
                    <option value="16" <?php selected( $archive_at_hour, '16' ); ?>>16</option>
                    <option value="17" <?php selected( $archive_at_hour, '17' ); ?>>17</option>
                    <option value="18" <?php selected( $archive_at_hour, '18' ); ?>>18</option>
                    <option value="19" <?php selected( $archive_at_hour, '19' ); ?>>19</option>
                    <option value="20" <?php selected( $archive_at_hour, '20' ); ?>>20</option>
                    <option value="21" <?php selected( $archive_at_hour, '21' ); ?>>21</option>
                    <option value="22" <?php selected( $archive_at_hour, '22' ); ?>>22</option>
                    <option value="23" <?php selected( $archive_at_hour, '23' ); ?>>23</option>
                </select>

                <select name="operation_message[om_archive_at_minute]">
                    <option value="00" <?php selected( $archive_at_minute, '00' ); ?>>00</option>
                    <option value="10" <?php selected( $archive_at_minute, '10' ); ?>>10</option>
                    <option value="20" <?php selected( $archive_at_minute, '20' ); ?>>20</option>
                    <option value="30" <?php selected( $archive_at_minute, '30' ); ?>>30</option>
                    <option value="40" <?php selected( $archive_at_minute, '40' ); ?>>40</option>
                    <option value="50" <?php selected( $archive_at_minute, '50' ); ?>>50</option>
                </select>
                <br/>

            </div>
        </div>
		<?php
	}

}