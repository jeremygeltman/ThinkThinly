$(function(){
	
	$window = $(window);
	if( $window .width() > 800){
		$('.parallax-scroll1').parallax("50%", 0.5);
		$('.parallax-scroll2').parallax("50%", 0.5);
		$('.parallax-scroll3').parallax("50%", 0.5);
	}
	
	$('.main-navigation').onePageNav({
		
		filter: ':not(.external)',
	    currentClass: 'current',
		scrollOffset: 85,
	    scrollSpeed: 1000,
	    scrollThreshold: 0.5 ,
	    easing: 'easeInOutExpo'
	   
	});
	
	//--- fixed header on scroll
	var test = 0;
	
	var $navbar = $('.navbar');
	var $white_logo_img = $('.white-logo-img');
	var $dark_logo_img = $('.dark-logo-img');
	
	function scrolled(test){
		
		if(test === 0){
			$navbar.stop().addClass("sticky-navbar");
			$white_logo_img.fadeOut();
			$dark_logo_img.fadeIn();
		}else{
			$('.navbar').stop().removeClass("sticky-navbar");
			$dark_logo_img.fadeOut();
			$white_logo_img.fadeIn();
		}
	}
	
	if($(window).scrollTop() > 50){
		scrolled(test);
	}
	
	$(window).scroll(function() {
		if ($(document).scrollTop() > 50) {
	    	if(test===0){
	    		scrolled(test);
	    	}
	    	test=1;
	    } else {
	    	if(test===1){
	        	scrolled(test);        	
	        }
	        test = 0;
	    }
	}); 
	//--- end scroll
	
	
	$('.contact-link').magnificPopup({
		type: 'inline',
		preloader: false,
		modal: true
	});
	
	$(document).on('click', '.close-btn', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
	
	
	$(".iphone-slider").owlCarousel({
		navigation : true,
		pagination: false,
		slideSpeed : 300,
		paginationSpeed : 400,
		navigationText:	["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		singleItem: true
	});
	
});

