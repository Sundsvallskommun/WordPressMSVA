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
            'Vattenläcka': '<p>Tekniker är på plats för att laga läckan, men vi vet i nuläget inte hur lång tid arbetet kommer att ta.</p><p>&nbsp;</p><p>När vattnet kommer tillbaka kan missfärgat vatten och tryckförändringar förekomma. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten.</p>',
            'Vattenavstängning': '<p>Betrakta ledningarna som trycksatta under hela avstängningstiden, vilket innebär att du inte ska lämna kranar öppna eller utföra egna arbeten på ledningar.</p><p>&nbsp;</p><p>Tappa gärna upp vatten som täcker Ert behov för dryck, matlagning samt spolning i toalett under avbrottet.</p><p>&nbsp;</p><p>När vattnet kommer tillbaka kan missfärgat vatten och tryckförändringar förekomma. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten.</p>',
            'Spolning av avloppsledningar': '<p>Arbetet utförs bland annat för att förebygga avloppsstopp</p><p>&nbsp;</p><p>MittSverige Vatten rekommenderar att toalettlock är nedfällda under spolningen eftersom stänk kan förekomma. Arbetet kan även leda till olägenheter i form av buller och lukt. Om lukt uppstår, fyll på vatten i golvbrunnar, toaletter och vattenlås. Vi hoppas att Ni har förståelse för vårt arbete.</p>',
            'Spolning av vattenledningar': '<p>Betrakta ledningarna som trycksatta under hela avstängningstiden, vilket innebär att du inte ska lämna kranar öppna eller utföra egna arbeten på ledningar.</p><p>&nbsp;</p><p>Tappa gärna upp vatten som täcker Ert behov för dryck, matlagning och spolning i toalett under leveransavbrottet. Arbetet kan leda till tryckförändringar och missfärgat vatten. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten igen.</p><p>&nbsp;</p><p>Vi hoppas att Ni har förståelse för vårt arbete.</p>',
            'Vattenläcka åtgärdad': '<p>När vattnet kommer tillbaka kan missfärgat vatten och tryckförändringar förekomma. Missfärgningen är inte farlig, men var försiktig med tvätt av kläder. Spola kallvatten för att snabbare få rent vatten.</p>'
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

        //init_form();

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
                }
            ).success( function( response ) {

                console.log( response );

            });


        });


        $('#operation-message-event').on( 'change', function() {

            var value = $(this).find('option:selected').val();
            var info_1_value = get_default_information_1_value( value );
            var info_2_value = get_default_information_2_value( value );

            $('.operation-message-information-1').val( info_1_value );
            $('.operation-message-information-2').val( info_2_value );

        } );

    });

})(jQuery);
