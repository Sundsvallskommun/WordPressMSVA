(function( $ ) {
    'use strict';

    $(document).ready( function() {

        if( !$('body').hasClass('loading') )
            closeNav();

        $(window).load(function(){
            $('body').removeClass('loading');
        });

        $('.a-select-region').on('click', function () {
            openNav();
        });

        $('.region-selected').on('click', function () {

            setMunicipalityCookie( $(this).data('region'), 30 );
            $('body').addClass('loading');

            $(this).closest('.actions').html('<i>Du skickas nu vidare till vald kommun ...</i>');

            var url = $(this).data('url');
            location.href = url;

        });

    });

})( jQuery );


function removeMunicipalityCookie() {
    document.cookie = 'municipality_adaptation' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
}


function setMunicipalityCookie( cvalue, exdays ) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = 'municipality_adaptation' + "=" + cvalue + ";" + expires + ";path=/";
}


function checkMunicipalityCookie() {
    if ( getMunicipalityCookie("municipality_adaptation") != "" )
        return true;

    return false;
}


function getMunicipalityCookie( cname ) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}