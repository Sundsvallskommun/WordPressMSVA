(function($) {

    function is_faq_match( value, text, text2, text3 ) {

        if( text.indexOf( value ) >= 0 || text2.indexOf( value ) >= 0 || text3.indexOf( value ) >= 0 ) {
            return true;
        }

        return false;

    }

    $('.search-filter').keyup(function(){

        var val_this = $(this).val().toLowerCase();

        $('.sk-collapse').each(function() {
            var text = $(this).find('h2 a').text().toLowerCase();
            var text2 = $(this).find('.faq-external-answer').text().toLowerCase();
            var text3 = $(this).find('.faq-internal-answer').text().toLowerCase();

            if( is_faq_match( val_this, text, text2, text3 ) ) {
                $(this).fadeIn();
            } else {
                $(this).fadeOut();
            }

        });

    });

})(jQuery);
