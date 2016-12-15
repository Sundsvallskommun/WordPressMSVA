<?php


class SK_Operation_Message_Shortcode {

	public function callback( $atts = array() ) {

		wp_enqueue_script( 'sk-operation-messages-js' );
		wp_enqueue_style( 'sk-operation-messages-datepicker' );
		wp_enqueue_script( 'sk-operation-messages-datepicker' );
		wp_enqueue_script( 'sk-operation-messages-datepicker-locale' );
		wp_enqueue_style( 'sk-operation-messages-css' );

		ob_start();
		?>

            <form id="operation-message-form" method="post" action="">

                <input type="hidden" value="<?php echo wp_create_nonce( 'sk-operation-messages' ); ?>" name="nonce" />

                <!-- Event section -->
                <div class="form-group">

                    <h3><?php _e( 'Händelse', 'msva' ); ?></h3>

                    <select class="form-control" name="operation_message[om_event]" id="operation-message-event">
                        <option value="0"><?php _e( 'Välj händelse...', 'msva' ); ?></option>
                        <option value="Vattenläcka"><?php _e( 'Vattenläcka', 'msva' ); ?></option>
                        <option value="Vattenavstängning"><?php _e( 'Vattenavstängning', 'msva' ); ?></option>
                        <option value="Spolning av avloppsledningar"><?php _e( 'Spolning av avloppsledningar', 'msva' ); ?></option>
                        <option value="Spolning av vattenledningar"><?php _e( 'Spolning av vattenledningar', 'msva' ); ?></option>
                        <option value="Vattenläcka åtgärdad"><?php _e( 'Vattenläcka åtgärdad' ); ?></option>
                        <option value="1"><?php _e( 'Egen händelse', 'msva' ); ?></option>
                    </select>

                    <label><?php _e( 'Egen händelse', 'msva' ); ?></label>
                    <input type="text" name="operation_message[om_custom_event]" class="form-control" />

                </div>
                <!-- End Event section -->


                <!-- Information and location section -->
                <div class="form-group">

                    <h3><?php _e( 'Information', 'msva' ); ?></h3>

                    <label><?php _e( 'Information del 1', 'msva' ); ?></label>
                    <?php
                    $wp_editor_args = array (
                        'tinymce' => false,
                        'quicktags' => true,
                        'media_buttons' => false,
                        'textarea_rows' => 6,
                        'editor_class' => 'form-control operation-message-information-1'
                    );
                    wp_editor( '', 'operation_message[om_information_part_1]', $wp_editor_args );
                    ?>

                    <label for="area_street"><?php _e( 'Område/Gata', 'msva' ); ?></label>
                    <input type="text" id="area_street" name="operation_message[om_area_street]" class="form-control" />

                    <label><?php _e( 'Information del 2', 'msva' ); ?></label>
                    <?php
                    $wp_editor_args = array (
	                    'tinymce' => false,
	                    'quicktags' => true,
	                    'media_buttons' => false,
	                    'textarea_rows' => 6,
	                    'editor_class' => 'form-control operation-message-information-2'
                    );
                    ?>
                    <?php wp_editor( '', 'operation_message[om_information_part_2]', $wp_editor_args ); ?>

                    <label><?php _e( 'Avslut', 'msva' ); ?></label>
                    <?php $default_ending_text = __( 'För ytterligare information kontakta vår Felanmälan på telefon 060-192070.', 'msva' ); ?>
                    <?php
                    $wp_editor_args = array (
                        'tinymce' => false,
                        'quicktags' => true,
                        'media_buttons' => false,
                        'textarea_rows' => 6,
                        'editor_class' => 'form-control operation-message-ending'
                    );
                    ?>
                    <?php wp_editor( $default_ending_text, 'operation_message[om_ending]', $wp_editor_args ); ?>

                </div>
                <!-- End information and location section -->


                <!-- Publishing Section -->
                <div class="operation-message-metabox-publishing-section form-group" >

                    <h3><?php _e( 'Publicering och Arkivering', 'msva' ); ?></h3>

                    <div class="row">

                        <div class="col-xs-4">
                            <input type="text" name="operation_message[om_publish_at_date]" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="<?php _e( 'Publiceringsdatum' ); ?>" />
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[om_publish_at_hour]" class="form-control">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[om_publish_at_minute]" class="form-control">
                                <option value="00">00</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <input type="text" name="operation_message[om_archive_at_date]" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="<?php _e( 'Arkiveringsdatum' ); ?>"/>
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[om_archive_at_hour]" class="form-control">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[om_archive_at_minute]" class="form-control">
                                <option value="00">00</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                            </select>
                        </div>

                    </div>

                </div>
                <!-- End publishing section -->

                <button type="button" id="operation-message-form-submit"
                        class="btn btn-secondary float-xs-right"><?php _e( 'Spara driftmeddelande', 'msva' ); ?></button>

            </form>


		<?php
		return ob_get_clean();

	}

}