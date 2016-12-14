(function($) {

    function get_default_information_value( selected_value ) {

        var values = {
            'handelse_1': 'Detta är händelse 1',
            'handelse_2': 'Detta är händlese 2',
            'handelse_3': 'Detta är händelse 3'
        }

        return values[selected_value];

    }

    function get_default_closing_value( selected_value ) {

        var values = {
            'handelse_1': 'Detta är händelse 1',
            'handelse_2': 'Detta är händlese 2',
            'handelse_3': 'Detta är händelse 3'
        }

        return values[selected_value];

    }

    function init_form() {

        var i = setInterval( function () {
            if ( $('.quicktags-toolbar').length ) {
                clearInterval(i);

                var default_value = get_default_information_value( 'handelse_1' );
                $('#operation-message-information').find('textarea').val( default_value );

            }
        }, 100 );

    }


    $(document).ready( function() {

        init_form();

        $('.datepicker').datepicker({
            'weekStart': 1,
            'autoclose': true,
            'language': 'sv'
        });

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


        $('#operation-message-errand .acf-input select').on( 'change', function() {

            var value = $(this).find('option:selected').val();
            var default_value = get_default_information_value( value );

            $('#operation-message-information').find('textarea').val( default_value );

        } );

    });

})(jQuery);
