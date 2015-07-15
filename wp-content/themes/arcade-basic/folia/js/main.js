$(function(){
	
	$window = $(window);
	if( $window .width() > 800){
		if ($('.parallax-scroll1').size() > 0){
			$('.parallax-scroll1').parallax("50%", 0.5);
		}
		if ($('.parallax-scroll2').size() > 0){
			$('.parallax-scroll2').parallax("50%", 0.5);
		}
		if ($('.parallax-scroll3').size() > 0){
			$('.parallax-scroll3').parallax("50%", 0.5);
		}
	}
	
	if ($('.main-navigation').size() > 0 && (typeof $('.main-navigation').onePageNav !== "undefined")) {
		$('.main-navigation').onePageNav({

			filter: ':not(.external)',
			currentClass: 'current',
			scrollOffset: 85,
			scrollSpeed: 1000,
			scrollThreshold: 0.5 ,
			easing: 'easeInOutExpo'

		});
	}
	
	if($('.contact-link').size() > 0) {
		$('.contact-link').magnificPopup({
			type: 'inline',
			preloader: false,
			modal: true
		});
	}
	
	$(document).on('click', '.close-btn', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
	
	
	if ($("#testi-slider").size() > 0){
		$("#testi-slider").owlCarousel({
			navigation : true,
			pagination: false,
			slideSpeed : 300,
			paginationSpeed : 400,
			navigationText:	["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
			singleItem: true
		});
	}
	
});

