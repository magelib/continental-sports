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
    		
    		if(width <= 770) {
	        $("featureBox").each(function() {

	        var detach = $(this).find(".btn").detach();

	        $(detach).insertAfter($(this).find("p"));
        })
    	}
    });
});