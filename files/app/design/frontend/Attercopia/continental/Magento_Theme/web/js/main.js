define([
  "jquery",
], 
function($) {
  "use strict";

  // Move "explore our resources" button to below technical section text for mobile screens.

   var $window = $(window);

    $window.resize(function resize(){

       var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
       var featureBox = $('.home-feature-box');
      
  		$(featureBox).each(function() {	

				var detach = $(this).find(".btn").detach();

				if(width <= 620) {

					$(detach).insertAfter($(this).find("p"));

				}
				else {
					$(detach).insertAfter($(this).find("h2"));
				}

  		});
    });
});