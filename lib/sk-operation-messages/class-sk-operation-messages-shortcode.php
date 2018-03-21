<?php

/**
 * This class handles shortcode output and logic.
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Operation_Message_Shortcode {

    const FIELD_EVENT_NAME = 'operation_disruption_event';

	/**
	 * The callback for the operation messages
	 * shortcode. Returns the html to output.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @params   array    the attributes. Currently have none.
	 *
	 */
	public function callback( $atts = array() ) {
		global $post;

		$author_id = get_current_user_id();

		if ( $author_id == 0 || ! current_user_can( 'edit_post', $post->ID ) ) {
			ob_start();
			?>

			<div class="panel panel-warning operation-message-warning">
				<h4><?php _e( 'Du saknar behörighet för att administrera driftmeddelanden.', 'msva' ); ?></h4>
			</div>

			<?php
			return ob_get_clean();
		}

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

		$meta_om_event = isset( $message->post->meta['om_event'] ) ? $message->post->meta['om_event'] : '';

        $operation_disruption_events = get_field('operation_disruption_events', 'option');

		?>

		<?php  ?>
		<div class="operation-message-wrapper">

            <?php printf("<script>var operation_disruption_events = %s</script>", str_replace('<\/p>', '\n', str_replace('<p>', '', json_encode($operation_disruption_events)))); ?>

			<form id="operation-message-form" method="post" action="">

				<input type="hidden" value="<?php echo wp_create_nonce( 'sk-operation-messages' ); ?>" name="nonce"/>
				<?php if ( isset( $message ) ) : ?>
					<input type="hidden" value="<?php echo $post->ID ?>" name="om_message_id"/>
				<?php endif; ?>


				<h3><?php _e( 'Händelse och område', 'msva' ); ?></h3>
				<div class="form-section">
					<div class="form-group">
						<select class="form-control" name="operation_message[om_event]" id="operation-message-event">
                            <option value="0" <?php selected( $meta_om_event, '0' ); ?>><?php _e( 'Välj händelse...', 'msva' ); ?></option>

                            <?php if (is_array($operation_disruption_events) && !empty($operation_disruption_events)) : ?>

                                   <?php foreach ($operation_disruption_events as $event) : ?>

                                        <?php if ($this->array_key_exists_have_value($event, self::FIELD_EVENT_NAME)) : ?>
                                            <option value="<?php echo $event_name = $event[self::FIELD_EVENT_NAME]; ?>" <?php selected( $meta_om_event, $event_name ); ?>><?php echo $event_name;?></option>
                                        <?php endif; ?>

                                    <?php endforeach; ?>

                            <?php endif; ?>

							<option
								value="1" <?php selected( $meta_om_event, '1' ); ?>><?php _e( 'Egen händelse', 'msva' ); ?></option>
						</select>
					</div>

					<div class="form-group">
						<label><?php _e( 'Egen händelse', 'msva' ); ?></label>
						<input type="text" name="operation_message[om_custom_event]" class="form-control"
						       id="operation-message-custom-event"
						       value="<?php echo isset( $message->post->meta['om_custom_event'] ) ? $message->post->meta['om_custom_event'] : null; ?>">
					</div>
					<div class="form-group">
						<label><?php _e( 'Område', 'msva' ); ?></label>
						<input type="text" name="operation_message[om_title]" class="form-control"
						       id="operation-message-title"
						       value="<?php echo isset( $message ) ? $message->title() : null; ?>">

					</div>
				</div><!-- .form-section -->

				<h3><?php _e( 'Kommun', 'msva' ); ?></h3>
				<div class="form-section">
					<div class="form-group">
						<label><?php _e( 'Välj kommun', 'msva' ); ?></label>
						<select name="operation_message[om_municipality]" class="form-control">

							<option
								value="Sundsvall" <?php selected( isset( $message->post->meta['om_municipality'] ) ? $message->post->meta['om_municipality'] : null , 'Sundsvall' ); ?>>
								Sundsvall
							</option>
							<option
								value="Timrå" <?php selected( isset( $message->post->meta['om_municipality'] ) ? $message->post->meta['om_municipality'] : null, 'Timrå' ); ?>>
								Timrå
							</option>
							<option
								value="Nordanstig" <?php selected( isset( $message->post->meta['om_municipality'] ) ? $message->post->meta['om_municipality'] : null, 'Nordanstig' ); ?>>
								Nordanstig
							</option>
						</select>
					</div>
					<div class="form-group">
						<label><?php _e( 'Alt. fri text', 'msva' ); ?></label>
						<input type="text" name="operation_message[om_custom_municipality]" class="form-control"
						       value="<?php echo isset( $message->post->meta['om_custom_municipality'] ) ? $message->post->meta['om_custom_municipality'] : null; ?>">

					</div>
				</div><!-- .form-section -->
				<!-- Information and location section -->
				<h3><?php _e( 'Information', 'msva' ); ?></h3>
				<div class="form-section">
					<div class="form-group">
						<label><?php _e( 'Information del 1', 'msva' ); ?></label>
						<textarea cols="" rows="5" name="operation_message[om_information_part_1]"
						          class="form-control operation-message-information-1"><?php echo isset( $message ) ? str_replace( '<br />', '', $message->info_1() ) : null; ?></textarea>
					</div>
					<div class="form-group">
						<label for="area_street"><?php _e( 'Område/Gata', 'msva' ); ?></label>
						<input type="text" id="area_street" name="operation_message[om_area_street]"
						       class="form-control"
						       value="<?php echo isset( $message ) ? $message->street() : ''; ?>">
					</div>
					<div class="form-group">
						<label><?php _e( 'Information del 2', 'msva' ); ?></label>
						<textarea cols="" rows="5" name="operation_message[om_information_part_2]"
						          class="form-control operation-message-information-2"><?php echo isset( $message ) ? str_replace( '<br />', '', $message->info_2() ) : null; ?></textarea>
					</div>
					<div class="form-group">
						<label><?php _e( 'Avslut', 'msva' ); ?></label>
						<?php
						$default_ending_text = __( 'För ytterligare information kontakta vår Felanmälan på telefon 060-192070.', 'msva' );
						if ( isset( $message ) ) {
							$default_ending_text = $message->ending();
						}
						?>
						<textarea name="operation_message[om_ending]" cols="" rows="5" id=""
						          class="form-control operation-message-om-ending"><?php echo isset( $default_ending_text ) ? str_replace( '<br />', '', $default_ending_text ) : ''; ?></textarea>


					</div>
				</div><!-- .form-section -->
				<!-- End information and location section -->


				<!-- Publishing Section -->
				<div class="operation-message-metabox-publishing-section">

					<h3><?php _e( 'Publicering', 'msva' ); ?></h3>
					<div class="form-section">
						<div class="form-group">
							<div class="row">

								<div class="col-sm-4">
									<label><?php _e( 'Datum', 'msva' ); ?></label>
									<input type="text" name="operation_message[om_publish_at_date]"
									       class="form-control datepicker"
									       data-date-format="yyyy-mm-dd"
									       placeholder="<?php _e( 'Publiceringsdatum' ); ?>"
									       value="<?php echo isset( $message->post->meta['om_publish_at_date'] ) ? $message->post->meta['om_publish_at_date'] : null; ?>">
								</div>

								<div class="col-sm-2 col-xs-6">
									<label><?php _e( 'Timme', 'msva' ); ?></label>
									<select name="operation_message[om_publish_at_hour]" class="form-control">
										<?php for ( $i = 0; $i <= 23; $i ++ ): ?>
											<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>
											<option value="<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>" <?php selected( isset( $message->post->meta['om_publish_at_hour'] ) ? $message->post->meta['om_publish_at_hour'] : null, str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?>><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?></option>
										<?php endfor; ?>
									</select>
								</div>

								<div class="col-sm-2 col-xs-6">
									<label><?php _e( 'Minuter', 'msva' ); ?></label>
									<select name="operation_message[om_publish_at_minute]" class="form-control">
										<?php for ( $i = 0; $i < 60; $i += 10 ): ?>
											<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>
											<option
												value="<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>" <?php selected( isset( $message->post->meta['om_publish_at_minute'] ) ? $message->post->meta['om_publish_at_minute'] : null, str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?>><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<h3><?php _e( 'Arkivering', 'msva' ); ?></h3>
					<div class="form-section">
						<div class="form-group">
							<div class="row">

								<div class="col-sm-4">
									<label><?php _e( 'Datum', 'msva' ); ?></label>
									<input type="text" name="operation_message[om_archive_at_date]"
									       class="form-control datepicker"
									       data-date-format="yyyy-mm-dd"
									       placeholder="<?php _e( 'Arkiveringsdatum' ); ?>"
									       value="<?php echo isset( $message->post->meta['om_archive_at_date'] ) ? $message->post->meta['om_archive_at_date'] : null; ?>">
								</div>

								<div class="col-sm-2 col-xs-6">
									<label><?php _e( 'Timme', 'msva' ); ?></label>
									<select name="operation_message[om_archive_at_hour]" class="form-control">
										<?php for ( $i = 0; $i <= 23; $i ++ ): ?>
											<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>
											<option value="<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>" <?php selected( isset( $message->post->meta['om_archive_at_hour'] ) ? $message->post->meta['om_archive_at_hour'] : null, str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?>><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?></option>
										<?php endfor; ?>
									</select>
								</div>

								<div class="col-sm-2 col-xs-6">
									<label><?php _e( 'Minuter', 'msva' ); ?></label>
									<select name="operation_message[om_archive_at_minute]" class="form-control">
										<?php for ( $i = 0; $i < 60; $i += 10 ): ?>
											<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>
											<option
												value="<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>" <?php selected( isset( $message->post->meta['om_archive_at_minute'] ) ? $message->post->meta['om_archive_at_minute'] : null, str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?>><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?></option>
										<?php endfor; ?>
									</select>
								</div>

							</div>
						</div>
					</div><!-- .form-section -->
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
						__( 'Sparade driftmeddelanden' ),
						$this->messages_list()
					)
				);

				?>
			</div>

		</div><!-- .operation-message-wrapper -->
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
					<th>Område</th>
					<th>Typ</th>
					<th>Skapad</th>
					<th>Åtgärdad?</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $messages as $post ) : ?>
					<?php $message = SK_Operation_Messages_Post::fromId( $post->ID ); ?>
					<tr>

						<td>
							<?php echo $message->title(); ?>
						</td>
						<td>
							<?php echo $message->om_event(); ?>
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


    /**
     *
     * Checks wether provided key exists and have a value. Empty string is interpreted as a no value
     * @param array $array
     * @param string $key
     *
     * @return true if key exists in array and have a value. or false if key could not be found or the value is empty
     */
	private function array_key_exists_have_value($array, $key) {

	    if ( array_key_exists($key, $array) && !empty($array[$key]) ) {
	        return true;
	    }

	    return false;

	}

}