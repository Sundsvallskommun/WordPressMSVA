(function ($) {
    'use strict';

    $(function () {
console.log('test');
        if ($('.cookie-informer').length > 0) {
            //console.log(this);
            $('body').prepend($('.cookie-informer'));

            $('#btn-accept-cookies').on('click', function () {
                setCookie('accepted', 30);
                $('.cookie-informer').remove();
            });
        }

    });

})(jQuery);


/*
 function removeCookie() {
 document.cookie = 'sk_cookie_informer' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
 }
 */

function setCookie(cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = 'sk_cookie_informer' + "=" + cvalue + ";" + expires + ";path=/";
}

