(function($) {

    console.log('Ok - ready!')


    $(document).ready( function() {

        $('#operation-message-form-submit').on('click', function () {

            $.post(
                ajax_object.ajaxurl,
                {
                    'action': 'new_message',
                    'form_data': $('#operation-message-form').serialize(),
                    'om_handelse': $('#operation-message-errand .acf-input').find( 'select option:selected' ).text()
                }
            ).success( function( response ) {

                console.log( response );

            });


        });

    });

})(jQuery);
