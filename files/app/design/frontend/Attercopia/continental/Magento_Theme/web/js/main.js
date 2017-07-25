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
      $(featureBox).each(function() { 

        var detach = $(this).find(".btn").detach();

        if(width <= 620) {

          $(detach).insertAfter($(this).find("p"));

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


});

