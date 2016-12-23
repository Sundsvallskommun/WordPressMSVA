(function($) {
	"use strict";
	$(document).ready(function() {


        $('.widget-garbage-scheme__response-close a').live('click', function(){
            $(this).closest('.widget-garbage-scheme__response').hide();
            return false;
        });


		$('#garbage-search-btn').live('click', function(){
			var value = $('#garbage-scheme-address').val();
            var wrapper = $('.widget-garbage-scheme');

            var data = {
                action: 'garbage_run',
                address: value,
                nonce: ajax_object.ajax_nonce
            };

            $.post( ajax_object.ajaxurl, data, function( response ) {

                //console.log( response );

                wrapper.find('.widget-garbage-scheme__response').html( response );
                wrapper.find('.widget-garbage-scheme__response').show();

                /*
                $('.cc-survey').empty();
                $('.cc-survey').append( $(response).find('.cc-survey-inner') );
                $('.cc-survey .cc-survey-closed').remove();
                */
            }).error(function(){
                alert ("Problem calling: " + action + "\nCode: " + this.status + "\nException: " + this.statusText);
            });


		});

		var engine = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('street_address' ),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: data

		});
		engine.initialize();

		$('#garbage-scheme-address').typeahead({
				minLength: 3,
				highlight: true,

			},
			{
				name: 'my-dataset',
				display:'street_address',
				source: engine,
				limit: 12,

				templates: {
					empty: '<p>Hittar inte angiven adress.</p>',
					suggestion: function (data) {
						return '<p>' +  data.street_address  + '</p>';
					}
				}

			});

		//console.log(engine);

	});



})(jQuery);
