(function($) {
	"use strict";
	$(document).ready(function() {
		$('.dropdown-toggle').dropdown()
		if( $('.widget-garbage-scheme').length > 0 ) {

			$('#garbage-scheme-address').keypress(function (e) {
				if (e.which == 13) {
					$('#garbage-search-btn').click();
					return false;
				}
			});


			$('.widget-garbage-scheme__response-close a').live('click', function () {
				$(this).closest('.widget-garbage-scheme__response').hide();
				$(this).closest('.widget-garbage-scheme').removeClass('active');
				return false;
			});




			$('#garbage-search-btn').live('click', function () {
				var value = $('#garbage-scheme-address').val();
				var wrapper = $('.widget-garbage-scheme');

				$('#garbage-search-btn span').addClass('ajax-loader').html('&nbsp;');


				var data = {
					action: 'garbage_run',
					address: value,
					nonce: ajax_object.ajax_nonce
				};

				$.post(ajax_object.ajaxurl, data, function (response) {

					//console.log( response );

					if (response != 0) {
						wrapper.addClass('active');
						wrapper.find('.widget-garbage-scheme__response').html(response);
						wrapper.find('.widget-garbage-scheme__response').show();
					}
					$('#garbage-search-btn span').removeClass('ajax-loader').html('Sök');

				}).error(function () {
					alert("Problem calling: " + action + "\nCode: " + this.status + "\nException: " + this.statusText);
				});


			});

			var engine = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('street_address'),
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
					display: 'street_address',
					source: engine,
					limit: 99,

					templates: {
						empty: '<p>Hittar inte angiven adress.</p>',
						suggestion: function (data) {
							return '<p>' + data.street_address + '</p>';
						}
					}

				});

		}
	});



})(jQuery);
