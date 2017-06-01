(function($) {

    function get_default_information_1_value( selected_value ) {

        var values = {
            'Vattenläcka': 'En vattenläcka har inträffat och våra kunder i följande områden/gator är för närvarande utan vatten:',
            'Vattenavstängning': 'Måndag 21 juni från klockan 22.00 till och med tisdag 22 juni klockan 05.00 kommer vattnet att stängas av för ledningsarbeten och följande område/gator berörs:',
            'Spolning av avloppsledningar': 'Måndag 21 juni från klockan 22.00 till och med tisdag 22 juni klockan 05.00 kommer spolning av avloppsledningsnätet att utföras och följande områden/gator berörs:',
            'Spolning av vattenledningar': 'Måndag 21 juni från klockan 22.00 till och med tisdag 22 juni klockan 05.00 kommer vattnet stängas av för spolning av vattenledningar och följande områden/gator berörs:',
            'Vattenläcka åtgärdad': 'Vattenläckan är nu åtgärdad och våra kunder i följande områden/gator har fått tillbaka vattnet:'
        }

        return values[selected_value];

    }

    function get_default_information_2_value( selected_value ) {

        var values = {
            'Vattenläcka': 'Tekniker är på plats för att laga läckan, men vi vet i nuläget inte hur lång tid arbetet kommer att ta.\r\n\r\nNär vattnet kommer tillbaka kan missfärgat vatten och tryckförändringar förekomma. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten.',
            'Vattenavstängning': 'Betrakta ledningarna som trycksatta under hela avstängningstiden, vilket innebär att du inte ska lämna kranar öppna eller utföra egna arbeten på ledningar\r\n\r\nTappa gärna upp vatten som täcker Ert behov för dryck, matlagning samt spolning i toalett under avbrottet.\r\n\r\nNär vattnet kommer tillbaka kan missfärgat vatten och tryckförändringar förekomma. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten.',
            'Spolning av avloppsledningar': 'Arbetet utförs bland annat för att förebygga avloppsstopp\r\n\r\nMittSverige Vatten rekommenderar att toalettlock är nedfällda under spolningen eftersom stänk kan förekomma. Arbetet kan även leda till olägenheter i form av buller och lukt. Om lukt uppstår, fyll på vatten i golvbrunnar, toaletter och vattenlås. Vi hoppas att Ni har förståelse för vårt arbete.',
            'Spolning av vattenledningar': 'Betrakta ledningarna som trycksatta under hela avstängningstiden, vilket innebär att du inte ska lämna kranar öppna eller utföra egna arbeten på ledningar.\r\n\r\nTappa gärna upp vatten som täcker Ert behov för dryck, matlagning och spolning i toalett under leveransavbrottet. Arbetet kan leda till tryckförändringar och missfärgat vatten. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten igen.\r\n\r\nVi hoppas att Ni har förståelse för vårt arbete.',
            'Vattenläcka åtgärdad': 'När vattnet kommer tillbaka kan missfärgat vatten och tryckförändringar förekomma. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten.'
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


    $(document).ready( function() {


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
            var info_1_value = get_default_information_1_value( value );
            var info_2_value = get_default_information_2_value( value );

            $('.operation-message-information-1').val( info_1_value );
            $('.operation-message-information-2').val( info_2_value );

        });


        $('.operation-message-new-message').on('click', function() {
            var new_location = location.protocol + '//' + location.host + location.pathname;
            document.location.href = new_location;
        });

    });

})(jQuery);
