(function($) {

    $(document).ready( function() {


        $('#sk-use-fee-area-input').keydown(function (e) {
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


        $('#sk-use-fee-clear-form').on('click', function() {
            clear_form();
        });
        

        $('#sk-use-fee-submit').on('click', function() {

            if( sk_use_fee_form_validated() ) {
            
                $('#sk-use-fee-submit').prop('disabled', true);
                $('.sk-use-fee-calculation-response').hide();
                $('#sk-use-fee-calculation-response').empty();
                $('.use-fee-loader').show();

                $.post(ajax_object.ajaxurl, {

                    'action': 'calculate_use_fee',
                    'data': $('#sk-use-fee').serialize(),
                    'nonce': $('#use-fee-nonce').val()

                }).success( function ( response ) {

                    $('.use-fee-loader').hide();s
                    $('#sk-use-fee-calculation-response').append( response.data );
                    $('#sk-use-fee-calculation-response').show();
                    $('#sk-use-fee-submit').prop('disabled', false);

                });

            }

        });


        $('.type-of-estate').on('change', function() {
            $('.form-field-toggle').hide().find('input').val('');

            if( $(this).val() === 'private-estate'){
                $(this).closest('.form-check').find('.form-field-toggle').show().find('input').focus();
            } 

            if( $(this).val() === 'business-estate'){
                $(this).closest('.form-check').find('.form-field-toggle').show().find('input').focus();
            }             

        });


        $('#sk-use-fee-municipality').trigger('change', function() {
            $(this).val(  $(this).val() );
        });

    } );


   

    function clear_form(){

        $('#service-v').prop('checked', false);
        $('#service-s').prop('checked', false);
        $('#service-df').prop('checked', false);
        $('#service-dg').prop('checked', false);
        $('#user-use').val('');
        $('#user-apartments').val('');
        $('#user-business-area').val('');
        validate_area('#sk-use-fee-area-input');
    }


    function validate_area(){
        $('#user-use').parent().find('.form-control-feedback').hide();
        
        if( $('#user-use').val().length <= 0 || $('#user-use').val() <= 0 ) {   
            $('#user-use').parent().find('.form-control-feedback').show();
            return false;
        }

    }


    function sk_use_fee_form_validated() {

        if( $('#sk-use-fee-area-group').find('.form-control-feedback').is(':visible') ) return false;
        if( $('#sk-use-fee-apartment-group').find('.form-control-feedback').is(':visible') ) return false;

        return true;

    }

})(jQuery);


