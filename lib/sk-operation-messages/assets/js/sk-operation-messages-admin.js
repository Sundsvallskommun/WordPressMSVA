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


    $(document).ready( function() {

        $('#operation-message-event').on( 'change', function() {

            var value = $(this).find('option:selected').val();
            var info_1_value = get_default_information_1_value( value );
            var info_2_value = get_default_information_2_value( value );

            $('.operation-message-information-1').val( info_1_value );
            $('.operation-message-information-2').val( info_2_value );

        });

        $( '.operation-message-datepicker').datepicker( {dateFormat: 'yy-mm-dd'});
    });

})(jQuery);
