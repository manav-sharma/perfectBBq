///*--- flex slider used in about us -----*/

$(document).ready(function(e) {
	  var previousScroll = 0;
	//hasScrolled();  
	  
function hasScrolled() {

    var st = $(this).scrollTop();
    console.log(st);
console.log(lastScrollTop);   

    if(Math.abs(lastScrollTop - st) <= delta)
        return;

    if (st > lastScrollTop && st > (navbarHeight - 109)){
        // Scroll Down
        $("#top").removeClass("is-visible").addClass("is-hidden");
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $("#top").removeClass("is-hidden").addClass("is-visible");
        }
    }
    
    lastScrollTop = st;
}
  

	/*$(window).scroll(function() {    
    var scrollUp = $(window).scrollTop();

     //>=, not <=
    if (scrollUp >= 110) {
		$("#top").removeClass("is-visible");
        $("#top").addClass("is-hidden");
        //clearHeader, not clearheader - caps H
		
    }
	
	var scrollDown = $(window).scrollDown();

     //>=, not <=
    if (scrollDown >= 110) {
        //clearHeader, not clearheader - caps H
		$("#top").removeClass("is-hidden");
		$("#top").addClass("is-visible");
        
    }
	});*/ //missing );
	
	
	
$(".toggleBtn").click(function(e) {
	
	if($(this).parent('#carousel li').hasClass('currentactive')){
	 $('#carousel li').removeClass('currentactive');
	 $('.aboutContent, .flex-direction-nav').slideUp(400);
	}
	else{
	$('.toggleBtn').parent('#carousel li').removeClass('currentactive');
	$('.aboutContent, .flex-direction-nav').slideUp(400)
	$(this).parent('#carousel li').addClass('currentactive');
	$('.aboutContent, .flexsliderTop .flex-direction-nav').slideDown(400);
	}
});

});

 
    /*$(window).load(function(){
      $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth:  100,
        itemMargin: 0,
        asNavFor: '#slider'
      });

      $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false, 
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    }); */

///*---End  flex slider used in about us -----*/




$(document).ready(function(e) {
	
	$("#toggler").click(function(e) { 
	$("body").addClass("overflowHiddenX");
	$(".responsiveSidebar").animate({right:0},800);
	$("#outer").animate({right:320},800);
	$("#toggler").fadeOut(400);
	
	setTimeout(function () {
                $("#togglerClose").addClass("toggleractive");
            }, 900);
	
	 
    });
	
	$("#togglerClose, .navbar-default .navbar-nav > li > a").click(function(e) { 
	setTimeout(function () {
	$(".responsiveSidebar").animate({right:-320},700);
	$("#outer").animate({right:0},700)
	$("#toggler").fadeIn(1000);
	}, 90);
	$("#togglerClose").removeClass("toggleractive");
		

    });
	
});



/*---about us tabs  ---*/

$(document).ready(function(e) {
	 var headerTopHeight = $(".header").height();
	var headerHeight = $("header").height() - headerTopHeight/2 ;
	jQuery('#actualHeight').val(headerHeight);
	
    $(".close").click(function(e) {
        $(this).parent(".tab-pane").removeClass("active");
		$("#myTab li").removeClass("active")
		 
    });
});

 
	
	
/* smoothe scroll  --


 // handle links with @href started with '#' only
$(document).on('click', '.navbar-nav a[href^="#"]', function(e) {
	headerHeight = $("#actualHeight").val();
    // target element id
    var id = $(this).attr('href');
    
    // target element
    var $id = $(id);
	
    if ($id.length === 0) { 
        return; 
		}
    
    // prevent standard hash navigation (avoid blinking in IE)
    e.preventDefault();
	
//	alert(headerHeight);
    // top position relative to the document
    var pos = $(id).offset().top - headerHeight - 2;
	 
    
    // animated top scrolling
    $('body, html').animate({scrollTop: pos},3000);
});
/*--- animation -----*/


$(window).load(function(){
	if($(window).width() > 767)
	{ 
	   new WOW().init();
	}  
});



  
  
  
 
(function($) {
 
    $.fn.parallax = function(options) {
 
        var windowHeight = $(window).height();
 
        // Establish default settings
        var settings = $.extend({
            speed        : 0.15
        }, options);
 
        // Iterate over each object in collection
        return this.each( function() {
 
        	// Save a reference to the element
        	var $this = $(this);
 
        	// Set up Scroll Handler
        	$(document).scroll(function(){
 
    		        var scrollTop = $(window).scrollTop();
					alert(Test);
            	        var offset = $this.offset().top;
            	        var height = $this.outerHeight();
 
    		// Check if above or below viewport
			if (offset + height <= scrollTop || offset >= scrollTop + windowHeight) {
				return;
			}
 
			var yBgPosition = Math.round((offset - scrollTop) * settings.speed);
 
                 // Apply the Y Background Position to Set the Parallax Effect
    			//$this.css('background-position', 'center ' + yBgPosition + 'px');
                
        	});
        });
    }
}(jQuery)); 

$('.bg-1').parallax({
	speed :	0.15
});

$('.bg-2').parallax({
	speed :	0.25
});


$(document).ready(function(){

/* Print Emails */
$('.infoEmail').html('<a href="mailto:' + 'info' + '@' + 'webworldexperts.com">' + 'info' + '@' + 'webworldexperts.com</a>');
$('.salesEmail').html('<a href="mailto:' + 'sales' + '@' + 'webworldexperts.com">' + 'sales' + '@' + 'webworldexperts.com</a>');
$('.partnerEmail').html('<a href="mailto:' + 'partnership' + '@' + 'webworldexperts.com">' + 'partnership' + '@' + 'webworldexperts.com</a>');
$('.careerEmail').html('<a href="mailto:' + 'career' + '@' + 'webworldexperts.com">' + 'career' + '@' + 'webworldexperts.com</a>');
$('.hrEmail').html('<a href="mailto:' + 'hr' + '@' + 'webworldexperts.com">' + 'hr' + '@' + 'webworldexperts.com</a>');



$('#tab').tabify();	

});	






/*--- header animation -----*/
/* $(document).scroll(function () {
	 var headerTopHeight = $(".header").height();
	var headerHeight = $("header").height() - headerTopHeight/2;   
	
    if (window.scrollY > headerHeight) {
        $("header").addClass('headerTop').animate(300); 
	  
		 
    } else {
		
        $("header").removeClass('headerTop').animate(300);
		 
    }
	

}); */
  
  
  

/*$(document).ready(function(){


  var previousScroll = 0;
 
  $(window).scroll(function(){

    var currentScroll = $(this).scrollTop();
	

    if (currentScroll > 0 && currentScroll < $(document).height() - $(window).height()){
      if (currentScroll > previousScroll){
        window.setTimeout(hideNav, 200);
		console.log('currentScroll');
		  
      } else {
        window.setTimeout(showNav, 300);
      }

      previousScroll = currentScroll;
    }

  });

  function hideNav() {
    $("#top").removeClass("is-visible").addClass("is-hidden");
  }
  function showNav() {
    $("#top").removeClass("is-hidden").addClass("is-visible");
  } 

}); */


 
$(document).ready(function(e) {
 /*   var sections = $('.section')
  , nav = $('header nav')
  , navH = $('.header .headerRightBottom') 
  , nav_height = navH.outerHeight();
 
/*$(window).on('scroll', function () {
  var cur_pos = $(this).scrollTop();
 
  sections.each(function() {
    var top = $(this).offset().top - nav_height,
        bottom = top + $(this).outerHeight();
 
    if (cur_pos >= top && cur_pos <= bottom) {
      nav.find('a').removeClass('active');
      sections.removeClass('active');
 
      $(this).addClass('active');
      nav.find('a[href="#'+$(this).attr('id')+'"]').addClass('active');
    }
  });
}); 
nav.find('a').on('click', function () {
  var $el = $(this)
    , id = $el.attr('href');
 
  $('html, body').animate({
    scrollTop: $(id).offset().top - nav_height
  }, 1000);
 
 
 
  return false;
  
  
});
	

*/
  
  
});  
  
  
  
  
  
  
//<![CDATA[
$(window).load(function(){
$(function() {
	
    var lengthDiv = $('.nav').find('li').length;
    var current = 0;
	
$('.nav li a').bind('click',function(event){
    
    var $anchor = $(this);
    current = $anchor.parent().index();
   
    event.preventDefault();
});
    $(document).keydown(function(e){e.preventDefault()})
    $(document).keyup(function(e){
        var key = e.keyCode;

       if(key ==  33 || key ==  37 || key ==  38 && current >= 0){
			var prevLink = $($('.nav').children('li').eq(current - 1).children('a')).attr('href');
			if($(prevLink).length == 0) {
           		 $('.nav').children('li').eq(current - 2).children('a').trigger('click') 
				//var tablink = 
			} else {
				$('.nav').children('li').eq(current - 1).children('a').trigger('click') 
			}
        }else if(key ==  34 || key == 39 || key == 40 && current < lengthDiv){
			var nextLink = $($('.nav').children('li').eq(current + 1).children('a')).attr('href');
			if($(nextLink).length == 0) {
				$('.nav').children('li').eq(current + 2).children('a').trigger('click')	
			} else {
				$('.nav').children('li').eq(current + 1).children('a').trigger('click')	
			}
             
        }
		 /*-- home and end key press -*/
		if(key == 36 && current > 0){
            $('.nav').children('li:first-child').children('a').trigger('click') 
        }else if(key == 35 && current < lengthDiv){
            $('.nav').children('li:last-child').children('a').trigger('click') 
        }
		/*-- home and end key press -*/
    })
});
});//]]> 
 
 





