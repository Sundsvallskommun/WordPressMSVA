(function($) {
	"use strict";


	$(document).ready(function() {

		if ( $(window).width() < 768 && ajax_object.area === 'Sundsvall' && ajax_object.page === '1' ) {
			if( $('.opening-hours-wrapper').length > 0 ){
				var opening_hours = $('.opening-hours-wrapper').clone();
				$('.opening-hours-wrapper').remove();
				$('.sections').prepend( opening_hours );
			}
		}



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

				/**
				 *
				 * Splits a string by whitespace and dash
				 *
				 * @param datum object to be analyzed
				 * @returns array containing the words or empty array if str eval to false
				 */
				datumTokenizer: function(datum) {
					var str = false;
					if (datum.hasOwnProperty('street_address')) {
						str = datum.street_address;
					}

					return str ? str.split(/[\-\s+]/) : [];
				},
				queryTokenizer: function(query) {
					return query ? query.split(/[\-\s+]/) : [];
				},

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


							if (typeof data.zip_code !== 'undefined' && data.zip_code !== null) {
								return '<p>' + data.street_address + ', ' + data.zip_code + '</p>';
							}

							return '<p>' + data.street_address + '</p>';
						}
					}

				});

		}
	});



})(jQuery);
