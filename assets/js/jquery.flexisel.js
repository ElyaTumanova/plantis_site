/*
* File: jquery.flexisel.js
* Version: 1.0.0
* Description: Responsive carousel jQuery plugin
* Author: 9bit Studios
* Copyright 2012, 9bit Studios
* http://www.9bitstudios.com
* Free to use and abuse under the MIT license.
* http://www.opensource.org/licenses/mit-license.php
*/

(function ($) {

  $.fn.flexisel = function (options) {

      var defaults = $.extend({
      visibleItems: 4,
      columnGaps: 0,
      animationSpeed: 200,
      autoPlay: false,
      autoPlaySpeed: 3000,    		
      pauseOnHover: true,
      setMaxWidthAndHeight: false,
      enableResponsiveBreakpoints: false,
      responsiveBreakpoints: { 
        portrait: { 
          changePoint:480,
          visibleItems: 1
        }, 
        landscape: { 
          changePoint:640,
          visibleItems: 2
        },
        tablet: { 
          changePoint:768,
          visibleItems: 3
        }
        }
      }, options);
      
  /******************************
  Private Variables
  *******************************/         
      
      var object = $(this);
      var settings = $.extend(defaults, options);        
      var itemsWidth; // Declare the global width of each item in carousel
      var canNavigate = true; 
      var itemsVisible = settings.visibleItems; 
      var columnGaps = settings.columnGaps;
      
  /******************************
  Public Methods
  *******************************/        
      
      var methods = {
          
    init: function() {
      
          return this.each(function () {
            methods.appendHTML();
            methods.setEventHandlers();      			
            methods.initializeItems();
      });
    },

    /******************************
    Initialize Items
    *******************************/			
    
    initializeItems: function() {
      
      var listParent = object.parent();
      var innerHeight = listParent.height(); 
      var childSet = object.children();
      
        var innerWidth = listParent.width() - columnGaps; // Set widths
        itemsWidth = (innerWidth)/itemsVisible;
        childSet.width(itemsWidth);
        childSet.last().insertBefore(childSet.first());
        childSet.last().insertBefore(childSet.first());
        object.css({'left' : -itemsWidth}); 

        object.fadeIn();
      $(window).trigger("resize"); // needed to position arrows correctly

    },
    
    
    /******************************
    Append HTML
    *******************************/			
    
    appendHTML: function() {
      
          object.addClass("nbs-flexisel-ul");
          object.wrap("<div class='nbs-flexisel-container'><div class='nbs-flexisel-inner'></div></div>");
          object.find("li").addClass("nbs-flexisel-item");

          if(settings.setMaxWidthAndHeight) {
            var baseWidth = $(".nbs-flexisel-item > img").width();
            var baseHeight = $(".nbs-flexisel-item > img").height();
            $(".nbs-flexisel-item > img").css("max-width", baseWidth);
            $(".nbs-flexisel-item > img").css("max-height", baseHeight);
          }

          $("<div class='nbs-flexisel-nav-left'>&#10094;</div><div class='nbs-flexisel-nav-right'>&#10095;</div>").insertAfter(object);
          //var cloneContent = object.children().clone();
          //object.append(cloneContent);
    },
        
    
    /******************************
    Set Event Handlers
    *******************************/
    setEventHandlers: function() {
      
      var listParent = object.parent();
      var childSet = object.children();
      var leftArrow = listParent.find($(".nbs-flexisel-nav-left"));
      var rightArrow = listParent.find($(".nbs-flexisel-nav-right"));
      
      $(window).on("resize", function(event){
        
        methods.setResponsiveEvents();
        
        var innerWidth = $(listParent).width() - columnGaps;
        var innerHeight = $(listParent).height(); 
        
        itemsWidth = (innerWidth)/itemsVisible;
        
        childSet.width(itemsWidth);
        object.css({'left' : -itemsWidth});
        
        var halfArrowHeight = (leftArrow.height())/2;
        var arrowMargin = (innerHeight/2) - halfArrowHeight;
        leftArrow.css("top", arrowMargin + "px");
        rightArrow.css("top", arrowMargin + "px");
        
      });					
      

      
    // new code

      $(object).on("touchstart",function (event) {
        pointStart = event.changedTouches[0].clientX;
        console.log('Произошло событие', event.changedTouches[0].clientX)
      });

      // $(object).on("mousedown",function (event) {
      //   methods.simulateClick();
      //   console.log('Произошло событие', event.clientX)
      // });

      $(object).on("touchmove",function (event) {
        pointEnd = event.changedTouches[0].clientX;
        console.log('Произошло событие', event.changedTouches[0].clientX);
        let move = pointEnd - pointStart;
        if (move <-100) {
          console.log('Сдвиг', move);
          methods.scrollRight();
        };
        if (move >100) {
          console.log('Сдвиг', move);
          methods.scrollLeft();
        };

      });

      // end new code

      $(leftArrow).on("click", function (event) {
        methods.scrollLeft();
      });
      
      $(rightArrow).on("click", function (event) {
        methods.scrollRight();
      });
      
      if(settings.pauseOnHover == true) {
        $(".nbs-flexisel-item").on({
          mouseenter: function () {
            canNavigate = false;
          }, 
          mouseleave: function () {
            canNavigate = true;
          }
         });
      }

      if(settings.autoPlay == true) {
        
        setInterval(function () {
          if(canNavigate == true)
            methods.scrollRight();
        }, settings.autoPlaySpeed);
      }
      
    },
    
    /******************************
    Set Responsive Events
    *******************************/			
    
    setResponsiveEvents: function() {
      var contentWidth = $('html').width();
      
      if(settings.enableResponsiveBreakpoints == true) {
        if(contentWidth < settings.responsiveBreakpoints.portrait.changePoint) {
          itemsVisible = settings.responsiveBreakpoints.portrait.visibleItems;
          columnGaps = settings.responsiveBreakpoints.portrait.columnGaps;
        }
        else if(contentWidth > settings.responsiveBreakpoints.portrait.changePoint && contentWidth < settings.responsiveBreakpoints.landscape.changePoint) {
          itemsVisible = settings.responsiveBreakpoints.landscape.visibleItems;
          columnGaps = settings.responsiveBreakpoints.landscape.columnGaps;
        }
        else if(contentWidth > settings.responsiveBreakpoints.landscape.changePoint && contentWidth < settings.responsiveBreakpoints.tablet.changePoint) {
          itemsVisible = settings.responsiveBreakpoints.tablet.visibleItems;
          columnGaps = settings.responsiveBreakpoints.tablet.columnGaps;
        }
        else {
          itemsVisible = settings.visibleItems;
          columnGaps = settings.columnGaps;
        }
      }
    },			
    
    /******************************
    Scroll Left
    *******************************/				
    
    scrollLeft:function() {

      console.log('hi');
      if(canNavigate == true) {
        canNavigate = false;
        
        var listParent = object.parent();
        var innerWidth = listParent.width() - columnGaps;
        
        itemsWidth = (innerWidth)/itemsVisible;
        
        var childSet = object.children();
        
        object.animate({
            'left' : "+=" + itemsWidth
          },
          {
            queue:false, 
            duration:settings.animationSpeed,
            easing: "linear",
            complete: function() {  
              childSet.last().insertBefore(childSet.first()); // Get the first list item and put it after the last list item (that's how the infinite effects is made)   								
              methods.adjustScroll();
              canNavigate = true; 
            }
          }
        );
      }
    },
    
    /******************************
    Scroll Right
    *******************************/				
    
    scrollRight:function() {
      
      if(canNavigate == true) {
        canNavigate = false;
        
        var listParent = object.parent();
        var innerWidth = listParent.width() - columnGaps;
        
        itemsWidth = (innerWidth)/itemsVisible;
        
        var childSet = object.children();
        
        object.animate({
            'left' : "-=" + itemsWidth
          },
          {
            queue:false, 
            duration:settings.animationSpeed,
            easing: "linear",
            complete: function() {  
              childSet.first().insertAfter(childSet.last()); // Get the first list item and put it after the last list item (that's how the infinite effects is made)   
              methods.adjustScroll();
              canNavigate = true; 
            }
          }
        );
      }
    },
    
    /******************************
    Adjust Scroll 
    *******************************/
    
    adjustScroll: function() {
      
      var listParent = object.parent();
      var childSet = object.children();				
      
      var innerWidth = listParent.width() - columnGaps; 
      itemsWidth = (innerWidth)/itemsVisible;
      childSet.width(itemsWidth);
      object.css({'left' : -itemsWidth});		
    },		
    
    // /******************************
    // simulateClick 
    // *******************************/
    
    // simulateClick: function() {
    //   // Get the element to send a click event
    //   var listParent = object.parent();
    //   var leftArrow = listParent.find($(".nbs-flexisel-nav-left"));
    
    //   // Send the event to the checkbox element
    //   leftArrow.trigger('click');
    // }
      
      };
      
      if (methods[options]) { 	// $("#element").pluginName('methodName', 'arg1', 'arg2');
          return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
      } else if (typeof options === 'object' || !options) { 	// $("#element").pluginName({ option: 1, option:2 });
          return methods.init.apply(this);  
      } else {
          $.error( 'Method "' +  method + '" does not exist in flexisel plugin!');
      }        
};

})(jQuery);