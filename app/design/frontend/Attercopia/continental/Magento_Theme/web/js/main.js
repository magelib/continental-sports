define([
  "jquery",
], 
function($) {
  "use strict";

    // Convert blog cat nav to select dropdown
    $("<select class='blog-cat-sel' />").insertAfter("#block-collapsible-nav-blog");    

    // Create default option "Go to..."
    $("<option />", {
       "selected": "selected",
       "value"   : "",
       "text"    : "Categories"
    }).appendTo(".blog__sidebar-category-tree select");

    // Populate dropdown with menu items
    $("ul.nav a").each(function() {
     var el = $(this);
     $("<option />", {
         "value"   : el.attr("href"),
         "text"    : el.text()
     }).appendTo(".blog__sidebar-category-tree select");
    });  

    $(".blog__sidebar-category-tree select").change(function() {
      window.location = $(this).find("option:selected").val();
    });

  
   var $window = $(window);

   // Move top panel navigation into mobile navigation for mobile if its on page load.

    var topPanelNav = $('.header-top-links');
    var mobileNav = $('.navigation');

    if ($(window).width() <= 900) {

      $(mobileNav).addClass('mobile-nav');

      $(topPanelNav).detach().insertAfter('.mobile-nav ');

      // Add Direct Payment link into mobile navigation

      $('.footerNav .direct-payment').detach().appendTo('.nav-sections-item-content .header-top-links ul');
    }

    $window.resize(function resize(){

      // Move "explore our resources" button to below technical section text for mobile screens.

      var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
      var featureBox = $('.home-feature-box');

		  moveButton(width, featureBox);      

      // Move top panel navigation into mobile navigation for mobile.      

      moveLinks(width, mobileNav, topPanelNav);

    });

    function moveButton(width, featureBox) {
	alert("Ah so this is move button");
      $(featureBox).each(function() { 

        var detach = $(this).find(".btn").detach();

        if(width <= 620) {
	  if (typeof window.detach == "undefined") {
              $(detach).insertAfter($(this).find("p"));
	      window.detach = true;
	  }

        }
        else {
          $(detach).insertAfter($(this).find("h2"));
        }

      });
    }     

    function moveLinks(width, mobileNav, topPanelNav) {
      if (width <= 900) {

        $(mobileNav).addClass('mobile-nav');

        $(topPanelNav).detach().insertAfter('.mobile-nav ');

        // Add Direct Payment link into mobile navigation

        $('.footerNav .direct-payment').detach().appendTo('.nav-sections-item-content .header-top-links ul');

      }
      else if (width > 900){
        $(mobileNav).removeClass('mobile-nav');

        $(topPanelNav).appendTo('.panel.header');

        $('.header-top-links ul .direct-payment').detach().appendTo('.footerNav ul');
      }
    }

    // Add and subtract quantity for product page input 

    $('.add').click(function () {
        $(this).prev().val(+$(this).prev().val() + 1);
    });
    $('.subtract').click(function () {
        if ($(this).next().val() > 0) $(this).next().val(+$(this).next().val() - 1);
    });

    // Toggle spares / product  DOM 

    $('.product-info-main .btn-spares').click(function(){
        hideProductPage();
        showSpares();
/*      $('.product-info-wrap').fadeToggle(500, 'linear');
      $('.product-spares-wrap').fadeToggle(500, 'linear');

        window.setTimeout(function(){
            $('.product-spares-wrap').toggleClass('finished');
        }, 500); //<-- Delay in milliseconds

        window.setTimeout(function(){
            $('body').toggleClass('spares-view');
        }, 500); //<-- Delay in milliseconds
*/
	
    });
    $('.spares-nav-button .btn-spares').click(function(){
        ft(".spares-listing");
        fi('.product-info-wrapper');
        fi('.related_column');
        fi('#maincontent .columns');

    });


    function showSpares() {
        fi(".spares-listing");
    }

    function hideProductPage() {
        ft('.product-info-wrapper');
        ft('.related_column');
        ft('#maincontent .columns');
    }

    function fi(el) {
        $(el).show();
    }

    function ft(el) {
        $(el).hide();
    }

    var imageH =  $(".fotorama__img").outerHeight();
    $(".fotorama__stage").height(imageH);
});

