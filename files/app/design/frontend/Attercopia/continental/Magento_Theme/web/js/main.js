define([
  "jquery",
], 
function($) {
  "use strict";

   var $window = $(window);
   
   // var $moveBtn = $(this).featureBox.find('.btn');

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