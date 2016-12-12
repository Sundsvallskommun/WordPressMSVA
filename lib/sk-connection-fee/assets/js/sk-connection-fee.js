(function($) {

    $(document).ready( function() {


        $('#sk-connection-fee-area-input').keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        $('#sk-connection-fee-area-input').on( 'blur' , function() {

            if( $(this).val() < 0 ) {
                $(this).val( '0' );
                $(this).parent().removeClass('has-danger');
                $(this).removeClass('form-control-danger');
                $(this).parent().find('.form-control-feedback').hide();
            } else if( $(this).val() > 1500 ) {
                $(this).parent().addClass('has-danger');
                $(this).addClass('form-control-danger');
                $(this).parent().find('.form-control-feedback').show();
            } else {
                $(this).parent().removeClass('has-danger');
                $(this).removeClass('form-control-danger');
                $(this).parent().find('.form-control-feedback').hide();
            }

        });


        $('#sk-connection-fee-apartment-input').on( 'blur' , function() {

            if( $(this).val() < 0 ) {
                $(this).val( '0' );
                $(this).parent().removeClass('has-danger');
                $(this).removeClass('form-control-danger');
                $(this).parent().find('.form-control-feedback').hide();
            } else if( $(this).val() > 2 ) {
                $(this).parent().addClass('has-danger');
                $(this).addClass('form-control-danger');
                $(this).parent().find('.form-control-feedback').show();
            } else {
                $(this).parent().removeClass('has-danger');
                $(this).removeClass('form-control-danger');
                $(this).parent().find('.form-control-feedback').hide();
            }

        });


        $('#sk-connection-fee-municipality').on('change', function() {

            $('#sk-connection-fee-calculation-response').empty();

            if( $(this).val() == 'nordanstig' ) {

                $('#rain-water-check').hide();
                $('#sk-connection-fee-area-group').hide();

            } else {

                $('#rain-water-check').show();
                $('#sk-connection-fee-area-group').show();

            }

        });

        $('#sk-connection-fee-submit').on('click', function() {

            if( sk_connection_fee_form_validated() ) {

                $('#sk-connection-fee-submit').prop('disabled', true);
                $('.sk-connection-fee-calculation-response').hide();
                $('#sk-connection-fee-calculation-response').empty();
                $('.connection-fee-loader').show();

                $.post(ajaxurl, {

                    'action': 'calculate_connection_fee',
                    'data': $('#sk-connection-fee').serialize(),
                    'nonce': $('#connection-fee-nonce').val()

                }).success( function ( response ) {

                    $('.connection-fee-loader').hide();
                    $('#sk-connection-fee-calculation-response').append( response.data );
                    $('#sk-connection-fee-calculation-response').show();
                    $('#sk-connection-fee-submit').prop('disabled', false);

                });

            }

        });


        $('#sk-connection-fee-municipality').trigger('change', function() {
            $(this).val(  $(this).val() );
        });

    } );



    function sk_connection_fee_form_validated() {

        if( $('#sk-connection-fee-area-group').find('.form-control-feedback').is(':visible') ) return false;
        if( $('#sk-connection-fee-apartment-group').find('.form-control-feedback').is(':visible') ) return false;

        return true;

    }

})(jQuery);


