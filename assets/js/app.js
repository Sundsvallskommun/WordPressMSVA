!function e(a,t,n){function r(i,s){if(!t[i]){if(!a[i]){var c="function"==typeof require&&require;if(!s&&c)return c(i,!0);if(o)return o(i,!0);throw new Error("Cannot find module '"+i+"'")}var d=t[i]={exports:{}};a[i][0].call(d.exports,function(e){var t=a[i][1][e];return r(t?t:e)},d,d.exports,e,a,t,n)}return t[i].exports}for(var o="function"==typeof require&&require,i=0;i<n.length;i++)r(n[i]);return r}({1:[function(e,a,t){function n(e,a){var t=new Date;t.setTime(t.getTime()+24*a*60*60*1e3);var n="expires="+t.toUTCString();document.cookie="municipality_adaptation="+e+";"+n+";path=/"}!function(e){"use strict";e(document).ready(function(){function a(a){a&&(e(a).collapse("show"),e("html, body").animate({scrollTop:e(a).offset().top-100},"linear"))}if(e('a[href^="#"], a[href^="/#"]').on("click",function(e){e.preventDefault(),a(this.hash)}),e(window).load(function(e){window.location.hash&&a(window.location.hash)}),e(window).width()<768&&"Sundsvall"===ajax_object.area&&"1"===ajax_object.page&&e(".opening-hours-wrapper").length>0){var t=e(".opening-hours-wrapper").clone();e(".opening-hours-wrapper").remove(),e(".sections").prepend(t)}if(e(".dropdown-toggle").dropdown(),e(".widget-garbage-scheme").length>0){e("#garbage-scheme-address").keypress(function(a){if(13==a.which)return e("#garbage-search-btn").click(),!1}),e(".widget-garbage-scheme__response-close a").live("click",function(){return e(this).closest(".widget-garbage-scheme__response").hide(),e(this).closest(".widget-garbage-scheme").removeClass("active"),!1}),e("#garbage-search-btn").live("click",function(){var a=e("#garbage-scheme-address").val(),t=e(".widget-garbage-scheme");e("#garbage-search-btn span").addClass("ajax-loader").html("&nbsp;");var n={action:"garbage_run",address:a,nonce:ajax_object.ajax_nonce};e.post(ajax_object.ajaxurl,n,function(a){0!=a&&(t.addClass("active"),t.find(".widget-garbage-scheme__response").html(a),t.find(".widget-garbage-scheme__response").show()),e("#garbage-search-btn span").removeClass("ajax-loader").html("Sök")}).error(function(){alert("Problem calling: "+action+"\nCode: "+this.status+"\nException: "+this.statusText)})});var n=new Bloodhound({datumTokenizer:function(e){var a=!1;return e.hasOwnProperty("street_address")&&(a=e.street_address),a?a.split(/[\-\s+]/):[]},queryTokenizer:function(e){return e?e.split(/[\-\s+]/):[]},local:data});n.initialize(),e("#garbage-scheme-address").typeahead({minLength:3,highlight:!0},{name:"my-dataset",display:"complete",source:n,limit:99,templates:{empty:"<p>Hittar inte angiven adress.</p>",suggestion:function(e){return"undefined"!=typeof e.zip_code&&null!==e.zip_code?"<p>"+e.street_address+", "+e.zip_code+"</p>":"<p>"+e.street_address+"xx</p>"}}})}})}(jQuery),function(e){"use strict";e(document).ready(function(){e("body").hasClass("loading")||closeNav(),e(window).load(function(){e("body").removeClass("loading")}),e(".a-select-region").on("click",function(){openNav()}),e(".region-selected").on("click",function(){n(e(this).data("region"),30),e("body").addClass("loading"),e(this).closest(".actions").html("<i>Du skickas nu vidare till vald kommun ...</i>");var a=e(this).data("url");location.href=a})})}(jQuery)},{}]},{},[1]);
//# sourceMappingURL=app.js.map
