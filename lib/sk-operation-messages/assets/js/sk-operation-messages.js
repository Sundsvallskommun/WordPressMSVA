(function($) {


    function init_form() {

        var i = setInterval( function () {
            if ( $('.quicktags-toolbar').length ) {
                clearInterval(i);

                var default_value = get_default_information_value( 'handelse_1' );
                $('#operation-message-information').find('textarea').val( default_value );

            }
        }, 100 );

    }


    function form_validated() {

        $('#operation-message-event').parent().removeClass('has-danger');
        $('#operation-message-event').removeClass('form-control-danger');
        $('#operation-message-custom-event').removeClass('form-control-danger');

        var selected_value = $('#operation-message-event').find( 'option:selected' ).val();

        if( selected_value === '0' ) {
            
            $('#operation-message-event').parent().addClass('has-danger');
            $('#operation-message-event').addClass('form-control-danger');
            return false;

        } else if( selected_value === '1' ) {

            if( $('#operation-message-custom-event').val() === '' ) {

                $('#operation-message-event').parent().addClass('has-danger');
                $('#operation-message-custom-event').addClass('form-control-danger');
                return false;

            }

            return true;

        }

        return true;

    }

    function find_event_info(key) {

        for (var i = 0; i < operation_disruption_events.length; i++) {
            var obj = operation_disruption_events[i];
            if (obj.operation_disruption_event === key) {
                return obj;
            }
        }

        return null;
    }

    $(document).ready( function() {


        $( "#target" ).select(function() {
          alert( "Handler for .select() called." );
        });


        $('.datepicker').datepicker({
            'weekStart': 1,
            'autoclose': true,
            'language': 'sv'
        });

        $('#operation-message-form-submit').on('click', function () {

            if( ! form_validated() ) {

                return false;


            }

            $('#operation-message-form').hide();
            $('.operation-message-loader').show();
            $.post(
                ajax_object.ajaxurl,
                {
                    'action': 'new_message',
                    'form_data': $('#operation-message-form').serialize(),
                }
            ).done( function( response ) {

                console.log( response );
                if ( response.success == true ) {
                    $('.operation-message-loader').hide();
                    $('.operation-message-response-success').show();
                } else {
                    $('.operation-message-loader').hide();
                    $('.operation-message-response-failure').show();
                }

            });

        });


        $('#operation-message-event').on( 'change', function() {

            $('#operation-message-event').parent().removeClass('has-danger');
            $('#operation-message-event').removeClass('form-control-danger');
            $('#operation-message-custom-event').removeClass('form-control-danger');

            var value = $(this).find('option:selected').val();

            info = find_event_info(value.trim());

            if (info === null) {
                $('.operation-message-information-1').val( '' );
                $('.operation-message-information-2').val( '' );
            }
            else {
                $('.operation-message-information-1').val( info.operation_disruption_event_info_1.trim() );
                $('.operation-message-information-2').val( info.operation_disruption_event_info_2.trim() );
            }


        });


        $('.operation-message-new-message').on('click', function() {
            var new_location = location.protocol + '//' + location.host + location.pathname;
            document.location.href = new_location;
        });

    });

})(jQuery);
