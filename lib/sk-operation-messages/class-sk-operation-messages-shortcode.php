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
        <div class="row">

            <form id="operation-message-form" method="post" action="">

                <input type="hidden" value="<?php echo wp_create_nonce( 'sk-operation-messages' ); ?>" name="nonce" />

                <!-- Event section -->
                <div class="form-group">

                    <h3><?php _e( 'Händelse', 'msva' ); ?></h3>

                    <select class="form-control" name="operation_message[event]">
                        <option value="0"><?php _e( 'Välj händelse...', 'msva' ); ?></option>
                        <option value="Vattenläcka"><?php _e( 'Vattenläcka', 'msva' ); ?></option>
                        <option value="Vattenavstängning"><?php _e( 'Vattenavstängning', 'msva' ); ?></option>
                        <option value="Spolning av avloppsledningar"><?php _e( 'Spolning av avloppsledningar', 'msva' ); ?></option>
                        <option value="Spolning av vattenledningar"><?php _e( 'Spolning av vattenledningar', 'msva' ); ?></option>
                        <option value="Vattenläcka åtgärdad"><?php _e( 'Vattenläcka åtgärdad' ); ?></option>
                        <option value="1"><?php _e( 'Egen händelse', 'msva' ); ?></option>
                    </select>

                    <label><?php _e( 'Egen händelse', 'msva' ); ?></label>
                    <input type="text" name="operation_message[custom_event]" class="form-control" />

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

                    );
                    wp_editor( '', 'operation_message[information_part_1]', $wp_editor_args );
                    ?>

                    <label for="area_street"><?php _e( 'Område/Gata', 'msva' ); ?></label>
                    <input type="text" id="area_street" name="operation_message[area_street]" class="form-control" />

                    <label><?php _e( 'Information del 2', 'msva' ); ?></label>
                    <?php wp_editor( '', 'operation_message[information_part_2]', $wp_editor_args ); ?>

                    <label><?php _e( 'Avslut', 'msva' ); ?></label>
                    <?php wp_editor( '', 'operation_message[ending]', $wp_editor_args ); ?>

                </div>
                <!-- End information and location section -->


                <!-- Publishing Section -->
                <div class="operation-message-metabox-publishing-section form-group" >

                    <h3><?php _e( 'Publicering och Arkivering', 'msva' ); ?></h3>

                    <div class="row">

                        <div class="col-xs-4">
                            <input type="text" name="operation_message[publish_at_date]" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="<?php _e( 'Publiceringsdatum' ); ?>" />
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[publish_at_hour]" class="form-control">
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
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[publish_at_minute]" class="form-control">
                                <option value="00">00</option>
                                <option value="00">10</option>
                                <option value="00">20</option>
                                <option value="00">30</option>
                                <option value="00">40</option>
                                <option value="00">50</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <input type="text" name="operation_message[archive_at_date]" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="<?php _e( 'Arkiveringsdatum' ); ?>"/>
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[archive_at_hour]" class="form-control">
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
                        </div>

                        <div class="col-xs-2">
                            <select name="operation_message[archive_at_minute]" class="form-control">
                                <option value="00">00</option>
                                <option value="00">10</option>
                                <option value="00">20</option>
                                <option value="00">30</option>
                                <option value="00">40</option>
                                <option value="00">50</option>
                            </select>
                        </div>

                    </div>

                </div>
                <!-- End publishing section -->

                <button type="button" id="operation-message-form-submit"
                        class="btn btn-secondary float-xs-right"><?php _e( 'Spara driftmeddelande', 'msva' ); ?></button>

            </form>

        </div>

		<?php
		return ob_get_clean();

	}

}