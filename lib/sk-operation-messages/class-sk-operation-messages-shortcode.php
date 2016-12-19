<?php

/**
 * This class handles shortcode output and logic.
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Operation_Message_Shortcode {

	/**
	 * The callback for the operation messages
	 * shortcode. Returns the html to output.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @params array    the attributes. Currently have none.
	 *
	 */
	public function callback( $atts = array() ) {

		wp_enqueue_script( 'sk-operation-messages-js' );
		wp_enqueue_style( 'sk-operation-messages-datepicker' );
		wp_enqueue_script( 'sk-operation-messages-datepicker' );
		wp_enqueue_script( 'sk-operation-messages-datepicker-locale' );
		wp_enqueue_style( 'sk-operation-messages-css' );

		$message = null;
		if ( isset( $_GET['edit'] ) ) {

			$post    = get_post( absint( $_GET['edit'] ) );
			$message = SK_Operation_Messages_Post::fromId( $post->ID );

		}

		ob_start();
		?>

        <form id="operation-message-form" method="post" action="">

            <input type="hidden" value="<?php echo wp_create_nonce( 'sk-operation-messages' ); ?>" name="nonce"/>
            <?php if ( isset( $message ) ) : ?>
                <input type="hidden" value="<?php echo $post->ID ?>" name="om_message_id" />
            <?php endif; ?>

            <!-- Event section -->
            <div class="form-group">

                <h3><?php _e( 'Händelse', 'msva' ); ?></h3>

                <label><?php _e( 'Titel', 'msva' ); ?></label>
                <input type="text" name="operation_message[om_title]" class="form-control"
                       id="operation-message-title" value="<?php echo isset( $message ) ? $message->title() : ''; ?>" />


                <select class="form-control" name="operation_message[om_event]" id="operation-message-event">
                    <option value="0" <?php selected( $message->post->meta['om_event'], '0' ); ?>><?php _e( 'Välj händelse...', 'msva' ); ?></option>
                    <option value="Vattenläcka" <?php selected( $message->post->meta['om_event'], 'Vattenläcka' ); ?>><?php _e( 'Vattenläcka', 'msva' ); ?></option>
                    <option value="Vattenavstängning" <?php selected( $message->post->meta['om_event'], 'Vattenavstängning' ); ?>><?php _e( 'Vattenavstängning', 'msva' ); ?></option>
                    <option value="Spolning av avloppsledningar" <?php selected( $message->post->meta['om_event'], 'Spolning av avloppsledningar' ); ?>><?php _e( 'Spolning av avloppsledningar', 'msva' ); ?></option>
                    <option value="Spolning av vattenledningar" <?php selected( $message->post->meta['om_event'], 'Spolning av vattenledningar' ); ?>><?php _e( 'Spolning av vattenledningar', 'msva' ); ?></option>
                    <option value="Vattenläcka åtgärdad" <?php selected( $message->post->meta['om_event'], 'Vattenläcka åtgärdad' ); ?>><?php _e( 'Vattenläcka åtgärdad' ); ?></option>
                    <option value="1" <?php selected( $message->post->meta['om_event'], '1' ); ?>><?php _e( 'Egen händelse', 'msva' ); ?></option>
                </select>

                <label><?php _e( 'Egen händelse', 'msva' ); ?></label>
                <input type="text" name="operation_message[om_custom_event]" class="form-control"
                       id="operation-message-custom-event" value="<?php echo $message->post->meta['om_custom_event']; ?>" />

            </div>
            <!-- End Event section -->

            <div class="form-group">

                <h3><?php _e( 'Kommun', 'msva' ); ?></h3>

                <select name="operation_message[om_municipality]" class="form-control">
                    <option value="Sundsvall" <?php selected( $message->post->meta['om_municipality'], 'Sundsvall' ); ?>>Sundsvall</option>
                    <option value="Timrå" <?php selected( $message->post->meta['om_municipality'], 'Timrå' ); ?>>Timrå</option>
                    <option value="Nordanstig" <?php selected( $message->post->meta['om_municipality'], 'Timrå' ); ?>>Nordanstig</option>
                </select>

                <label><?php _e( 'Alt. fri text', 'msva' ); ?></label>
                <input type="text" name="operation_message[om_custom_municipality]" class="form-control" value="<?php echo $message->post->meta['om_custom_municipality']; ?>" />

            </div>

            <!-- Information and location section -->
            <div class="form-group">

                <h3><?php _e( 'Information', 'msva' ); ?></h3>

                <label><?php _e( 'Information del 1', 'msva' ); ?></label>
				<?php
				$wp_editor_args = array(
					'tinymce'       => false,
					'quicktags'     => true,
					'media_buttons' => false,
					'textarea_rows' => 6,
					'editor_class'  => 'form-control operation-message-information-1'
				);
				wp_editor( isset( $message ) ? $message->info_1() : '', 'operation_message[om_information_part_1]', $wp_editor_args );
				?>

                <label for="area_street"><?php _e( 'Område/Gata', 'msva' ); ?></label>
                <input type="text" id="area_street" name="operation_message[om_area_street]" class="form-control" value="<?php echo isset( $message ) ? $message->street() : ''; ?>" />

                <label><?php _e( 'Information del 2', 'msva' ); ?></label>
				<?php
				$wp_editor_args = array(
					'tinymce'       => false,
					'quicktags'     => true,
					'media_buttons' => false,
					'textarea_rows' => 6,
					'editor_class'  => 'form-control operation-message-information-2'
				);
				?>
				<?php wp_editor( isset( $message ) ? $message->info_2() : '', 'operation_message[om_information_part_2]', $wp_editor_args ); ?>

                <label><?php _e( 'Avslut', 'msva' ); ?></label>
				<?php

                $default_ending_text = __( 'För ytterligare information kontakta vår Felanmälan på telefon 060-192070.', 'msva' );
                if ( isset( $message ) ) {
                    $default_ending_text = $message->ending();
                }
				$wp_editor_args = array(
					'tinymce'       => false,
					'quicktags'     => true,
					'media_buttons' => false,
					'textarea_rows' => 6,
					'editor_class'  => 'form-control operation-message-ending'
				);
				?>
				<?php wp_editor( $default_ending_text, 'operation_message[om_ending]', $wp_editor_args ); ?>

            </div>
            <!-- End information and location section -->


            <!-- Publishing Section -->
            <div class="operation-message-metabox-publishing-section form-group">

                <h3><?php _e( 'Publicering och Arkivering', 'msva' ); ?></h3>

                <div class="row">

                    <div class="col-xs-4">
                        <input type="text" name="operation_message[om_publish_at_date]" class="form-control datepicker"
                               data-date-format="yyyy-mm-dd" placeholder="<?php _e( 'Publiceringsdatum' ); ?>" value="<?php echo $message->post->meta['om_publish_at_date']?>" />
                    </div>

                    <div class="col-xs-2">
                        <select name="operation_message[om_publish_at_hour]" class="form-control">
                            <option value="00" <?php selected($message->post->meta['om_publish_at_hour'], '00'); ?>>00</option>
                            <option value="01" <?php selected($message->post->meta['om_publish_at_hour'], '01'); ?>>01</option>
                            <option value="02" <?php selected($message->post->meta['om_publish_at_hour'], '02'); ?>>02</option>
                            <option value="03" <?php selected($message->post->meta['om_publish_at_hour'], '03'); ?>>03</option>
                            <option value="04" <?php selected($message->post->meta['om_publish_at_hour'], '04'); ?>>04</option>
                            <option value="05" <?php selected($message->post->meta['om_publish_at_hour'], '05'); ?>>05</option>
                            <option value="06" <?php selected($message->post->meta['om_publish_at_hour'], '06'); ?>>06</option>
                            <option value="07" <?php selected($message->post->meta['om_publish_at_hour'], '07'); ?>>07</option>
                            <option value="08" <?php selected($message->post->meta['om_publish_at_hour'], '08'); ?>>08</option>
                            <option value="09" <?php selected($message->post->meta['om_publish_at_hour'], '09'); ?>>09</option>
                            <option value="10" <?php selected($message->post->meta['om_publish_at_hour'], '10'); ?>>10</option>
                            <option value="11" <?php selected($message->post->meta['om_publish_at_hour'], '11'); ?>>11</option>
                            <option value="12" <?php selected($message->post->meta['om_publish_at_hour'], '12'); ?>>12</option>
                            <option value="13" <?php selected($message->post->meta['om_publish_at_hour'], '13'); ?>>13</option>
                            <option value="14" <?php selected($message->post->meta['om_publish_at_hour'], '14'); ?>>14</option>
                            <option value="15" <?php selected($message->post->meta['om_publish_at_hour'], '15'); ?>>15</option>
                            <option value="16" <?php selected($message->post->meta['om_publish_at_hour'], '16'); ?>>16</option>
                            <option value="17" <?php selected($message->post->meta['om_publish_at_hour'], '17'); ?>>17</option>
                            <option value="18" <?php selected($message->post->meta['om_publish_at_hour'], '18'); ?>>18</option>
                            <option value="19" <?php selected($message->post->meta['om_publish_at_hour'], '19'); ?>>19</option>
                            <option value="20" <?php selected($message->post->meta['om_publish_at_hour'], '20'); ?>>20</option>
                            <option value="21" <?php selected($message->post->meta['om_publish_at_hour'], '21'); ?>>21</option>
                            <option value="22" <?php selected($message->post->meta['om_publish_at_hour'], '22'); ?>>22</option>
                            <option value="23" <?php selected($message->post->meta['om_publish_at_hour'], '23'); ?>>23</option>
                        </select>
                    </div>

                    <div class="col-xs-2">
                        <select name="operation_message[om_publish_at_minute]" class="form-control">
                            <option value="00" <?php selected($message->post->meta['om_publish_at_minute'], '00'); ?>>00</option>
                            <option value="10" <?php selected($message->post->meta['om_publish_at_minute'], '10'); ?>>10</option>
                            <option value="20" <?php selected($message->post->meta['om_publish_at_minute'], '20'); ?>>20</option>
                            <option value="30" <?php selected($message->post->meta['om_publish_at_minute'], '30'); ?>>30</option>
                            <option value="40" <?php selected($message->post->meta['om_publish_at_minute'], '40'); ?>>40</option>
                            <option value="50" <?php selected($message->post->meta['om_publish_at_minute'], '50'); ?>>50</option>
                        </select>
                    </div>
                </div>

                <div class="row">

                    <div class="col-xs-4">
                        <input type="text" name="operation_message[om_archive_at_date]" class="form-control datepicker"
                               data-date-format="yyyy-mm-dd" placeholder="<?php _e( 'Arkiveringsdatum' ); ?>" value="<?php echo $message->post->meta['om_archive_at_date']?>" />
                    </div>

                    <div class="col-xs-2">
                        <select name="operation_message[om_archive_at_hour]" class="form-control">
                            <option value="00" <?php selected($message->post->meta['om_archive_at_hour'], '00'); ?>>00</option>
                            <option value="01" <?php selected($message->post->meta['om_archive_at_hour'], '01'); ?>>01</option>
                            <option value="02" <?php selected($message->post->meta['om_archive_at_hour'], '02'); ?>>02</option>
                            <option value="03" <?php selected($message->post->meta['om_archive_at_hour'], '03'); ?>>03</option>
                            <option value="04" <?php selected($message->post->meta['om_archive_at_hour'], '04'); ?>>04</option>
                            <option value="05" <?php selected($message->post->meta['om_archive_at_hour'], '05'); ?>>05</option>
                            <option value="06" <?php selected($message->post->meta['om_archive_at_hour'], '06'); ?>>06</option>
                            <option value="07" <?php selected($message->post->meta['om_archive_at_hour'], '07'); ?>>07</option>
                            <option value="08" <?php selected($message->post->meta['om_archive_at_hour'], '08'); ?>>08</option>
                            <option value="09" <?php selected($message->post->meta['om_archive_at_hour'], '09'); ?>>09</option>
                            <option value="10" <?php selected($message->post->meta['om_archive_at_hour'], '10'); ?>>10</option>
                            <option value="11" <?php selected($message->post->meta['om_archive_at_hour'], '11'); ?>>11</option>
                            <option value="12" <?php selected($message->post->meta['om_archive_at_hour'], '12'); ?>>12</option>
                            <option value="13" <?php selected($message->post->meta['om_archive_at_hour'], '13'); ?>>13</option>
                            <option value="14" <?php selected($message->post->meta['om_archive_at_hour'], '14'); ?>>14</option>
                            <option value="15" <?php selected($message->post->meta['om_archive_at_hour'], '15'); ?>>15</option>
                            <option value="16" <?php selected($message->post->meta['om_archive_at_hour'], '16'); ?>>16</option>
                            <option value="17" <?php selected($message->post->meta['om_archive_at_hour'], '17'); ?>>17</option>
                            <option value="18" <?php selected($message->post->meta['om_archive_at_hour'], '18'); ?>>18</option>
                            <option value="19" <?php selected($message->post->meta['om_archive_at_hour'], '19'); ?>>19</option>
                            <option value="20" <?php selected($message->post->meta['om_archive_at_hour'], '20'); ?>>20</option>
                            <option value="21" <?php selected($message->post->meta['om_archive_at_hour'], '21'); ?>>21</option>
                            <option value="22" <?php selected($message->post->meta['om_archive_at_hour'], '22'); ?>>22</option>
                            <option value="23" <?php selected($message->post->meta['om_archive_at_hour'], '23'); ?>>23</option>
                        </select>
                    </div>

                    <div class="col-xs-2">
                        <select name="operation_message[om_archive_at_minute]" class="form-control">
                            <option value="00" <?php selected($message->post->meta['om_archive_at_minute'], '00'); ?>>00</option>
                            <option value="10" <?php selected($message->post->meta['om_archive_at_minute'], '10'); ?>>10</option>
                            <option value="20" <?php selected($message->post->meta['om_archive_at_minute'], '20'); ?>>20</option>
                            <option value="30" <?php selected($message->post->meta['om_archive_at_minute'], '30'); ?>>30</option>
                            <option value="40" <?php selected($message->post->meta['om_archive_at_minute'], '40'); ?>>40</option>
                            <option value="50" <?php selected($message->post->meta['om_archive_at_minute'], '50'); ?>>50</option>
                        </select>
                    </div>

                </div>

            </div>
            <!-- End publishing section -->

            <button type="button" id="operation-message-form-submit"
                    class="btn btn-secondary float-xs-right"><?php _e( 'Spara driftmeddelande', 'msva' ); ?></button>

        </form>

        <div class="row operation-message-loader">
            <div class="card card-block">
                <div class="operation-message-loader-header">
                    <h4 class="card-title"><?php _e( 'Sparar driftmeddelande', 'msva' ); ?></h4>
                </div>
                <div class="sk-circle">
                    <div class="sk-circle1 sk-child"></div>
                    <div class="sk-circle2 sk-child"></div>
                    <div class="sk-circle3 sk-child"></div>
                    <div class="sk-circle4 sk-child"></div>
                    <div class="sk-circle5 sk-child"></div>
                    <div class="sk-circle6 sk-child"></div>
                    <div class="sk-circle7 sk-child"></div>
                    <div class="sk-circle8 sk-child"></div>
                    <div class="sk-circle9 sk-child"></div>
                    <div class="sk-circle10 sk-child"></div>
                    <div class="sk-circle11 sk-child"></div>
                    <div class="sk-circle12 sk-child"></div>
                </div>
            </div>
        </div>


        <div class="row operation-message-response-success">
            <div class="card card-block">
                <div class="operation-message-loader-header">
                    <h4 class="card-title"><?php _e( 'Driftmeddelande sparat!', 'msva' ); ?></h4>
                </div>
                <div class="card-text">
                    <button type="button"
                            class="btn btn-secondary operation-message-new-message"><?php _e( 'Nytt driftmeddelande', 'msva' ); ?></button>
                </div>
            </div>
        </div>


        <div class="row operation-message-response-failure">
            <div class="card card-block">
                <div class="operation-message-loader-header">
                    <h4 class="card-title"><?php _e( 'Ett fel inträffade. Driftmeddelande kunde ej sparas!', 'msva' ); ?></h4>
                </div>
                <div class="card-text">
                    <button type="button"
                            class="btn btn-secondary operation-message-new-message"><?php _e( 'Nytt driftmeddelande', 'msva' ); ?></button>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="operation-messages-list">
        <?php

            echo do_shortcode(
                sprintf(
                '[utfallbar tag="h2" title="%s"]%s[/utfallbar]',
                __( 'Sparade Driftmeddelanden' ),
                $this->messages_list()
                )
            );

        ?>
        </div>
		<?php
		return ob_get_clean();

	}


	public function messages_list( $atts = array() ) {
	    $messages = SK_Operation_Messages::messages( array( 'publish', 'future' ) );
        ob_start();
	    ?>
        <div class="operation-messages-list">

            <table class="table">
                <thead>
                <tr>
                    <th>Titel</th>
                    <th>Typ</th>
                    <th>Skapad</th>
                    <th>Åtgärdad?</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ( $messages as $post ) : ?>
	                <?php $message = SK_Operation_Messages_Post::fromId( $post->ID ) ?>
                    <tr>

                        <td>
	                        <?php echo $message->event_title(); ?>
                        </td>
                        <td>
                            <?php echo $message->title(); ?>
                        </td>
                        <td>
                            <?php echo $message->created_at() ?>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $post->ID; ?>"><?php _e( 'Redigera', 'msva' ); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>
        <?php
        return ob_get_clean();
    }

}