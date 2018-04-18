(function($) {


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

        $('#operation-message-event').on( 'change', function() {

            $('#operation-message-event').parent().removeClass('has-danger');
            $('#operation-message-event').removeClass('form-control-danger');
            $('#operation-message-custom-event').removeClass('form-control-danger');

            var value = $(this).find('option:selected').val();

            info = find_event_info(value.trim());

            if (info === null) {
                $('.operation-message-information-1').val( '' );
                $('.operation-message-information-2').val( '' );
                $('.operation-message-om-ending').val( '' );
            }
            else {
                $('.operation-message-information-1').val( info.operation_disruption_event_info_1.trim() );
                $('.operation-message-information-2').val( info.operation_disruption_event_info_2.trim() );
                $('.operation-message-om-ending').val( info.operation_disruption_event_end.trim() );
            }


        });

        $( '.operation-message-datepicker').datepicker( {dateFormat: 'yy-mm-dd'});

        if( pagenow == 'operation_message'){
            if( $('#title-prompt-text').length > 0 ){
                $('#title-prompt-text').html('Ange område här');
            }
        }

    });

})(jQuery);
