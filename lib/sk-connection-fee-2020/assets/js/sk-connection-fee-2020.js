(function($) {

    $(document).ready( function() {


        $('#sk-connection-fee-area-input-2020').keydown(function (e) {
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

        $('#sk-connection-fee-area-input-2020').on( 'blur' , function() {
            validate_area( $(this) );
        });

        $('#water-fee').change(function() {
            validate_area( '#sk-connection-fee-area-input-2020' );
        });
        $('#spillwater-fee').change(function() {
            validate_area( '#sk-connection-fee-area-input-2020' );
        });
        $('#rainwater-fee').change(function() {
            validate_area( '#sk-connection-fee-area-input-2020' );
        });

        $('#sk-connection-fee-clear-form').on('click', function() {
            clear_form();
        });
        

        $('#sk-connection-fee-submit-2020').on('click', function() {
            if( sk_connection_fee_form_validated() ) {
            
                $('#sk-connection-fee-submit-2020').prop('disabled', true);
                $('.sk-connection-fee-calculation-response-2020').hide();
                $('#sk-connection-fee-calculation-response-2020').empty();
                $('.connection-fee-loader-2020').show();

                $.post(ajax_object.ajaxurl, {

                    'action': 'calculate_connection_fee_2020',
                    'data': $('#sk-connection-fee-2020').serialize(),
                    'nonce': $('#connection-fee-nonce-2020').val()

                }).success( function ( response ) {

                    $('.connection-fee-loader-2020').hide();s
                    $('#sk-connection-fee-calculation-response-2020').append( response.data );
                    $('#sk-connection-fee-calculation-response-2020').show();
                    $('#sk-connection-fee-submit-2020').prop('disabled', false);

                });

            }

        });


        $('#sk-connection-fee-municipality-2020').trigger('change', function() {
            $(this).val(  $(this).val() );
        });

    } );


   

    function clear_form(){

        $('#water-fee').prop('checked', false);
        $('#spillwater-fee').prop('checked', false);
        $('#rainwater-fee').prop('checked', false);
        $('#sk-connection-fee-area-input-2020').val('');
        $('#sk-connection-fee-apartment-input-2020').val('');
        validate_area('#sk-connection-fee-area-input-2020');
    }


    function validate_area( elm ){
        mav_value = sk_connection_max_area();

        if( $(elm).val() < 0 ) {
            $(elm).val( '0' );
            $(elm).parent().removeClass('has-danger');
            $(elm).removeClass('form-control-danger');
            $(elm).parent().find('.form-control-feedback').hide();
            $('#sk-connection-fee-submit-2020').prop('disabled', false);
        } else if( max_value !== 0 && $(elm).val() > max_value ) {
            $(elm).parent().addClass('has-danger');
            $(elm).addClass('form-control-danger');
            $(elm).parent().find('.form-control-feedback').show();
            $('#sk-connection-fee-submit-2020').prop('disabled', true);
        } else {
            $(elm).parent().removeClass('has-danger');
            $(elm).removeClass('form-control-danger');
            $(elm).parent().find('.form-control-feedback').hide();
            $('#sk-connection-fee-submit-2020').prop('disabled', false);
        }
    }


    function sk_connection_max_area(){

        connection_type = '';
            
        if( $('#water-fee').is( ':checked' ) ){
            connection_type += 'v';
        }

        if( $('#spillwater-fee').is( ':checked' ) ){
            connection_type += 's';
        }

        if( $('#rainwater-fee').is( ':checked' ) ){
            connection_type += 'd';
        }

        max_value = 0;
        switch( connection_type ) {
            case 'vsd':
                max_value = 5500;
              break;
            case 'vs':
                max_value = 5700;
              break;
              case 'v':
                max_value = 10000;
              break;
              case 's':
                max_value = 6800;
              break;
              case 'd':
                max_value = 14000;
              break;                                                    
            default:
                mav_value = 0;
          } 

        return max_value;
    }


    function sk_connection_fee_form_validated() {

        if( $('#sk-connection-fee-area-group-2020').find('.form-control-feedback').is(':visible') ) return false;
        if( $('#sk-connection-fee-apartment-group-2020').find('.form-control-feedback').is(':visible') ) return false;

        return true;

    }

})(jQuery);


