define([
  "jquery",
], 
function($) {
  "use strict";

  
   var $window = $(window);

   // Move top panel navigation into mobile navigation for mobile if its on page load.

    var topPanelNav = $('.header-top-links');
    var mobileNav = $('.navigation');

    if ($(window).width() <= 900) {

      $(mobileNav).addClass('mobile-nav');

      $(topPanelNav).detach().insertAfter('.mobile-nav ');

      console.log("less than 900");
    }
    else {
      return;
    }

    $window.resize(function resize(){

      // Move "explore our resources" button to below technical section text for mobile screens.

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

      // Move top panel navigation into mobile navigation for mobile.      

      if (width <= 900) {

        $(mobileNav).addClass('mobile-nav');

        $(topPanelNav).detach().insertAfter('.mobile-nav ');
      }
      else {
        $(mobileNav).removeClass('mobile-nav');

        $(topPanelNav).appendTo('.panel.header');
      }

    });       

});