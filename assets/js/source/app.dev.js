(function($) {
	"use strict";
	$(document).ready(function() {


		var options = {

			url: "resources/countries.json",

			getValue: "name",

			list: {
				match: {
					enabled: true
				}
			},

			theme: "square"
		};

		$("#countries").easyAutocomplete(options);





	});





})(jQuery);
