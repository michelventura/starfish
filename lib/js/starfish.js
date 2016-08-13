jQuery(function ($) { "use strict";
	
	$(document).ready(function() {
		
		// Mobile Stuff
		if ($("body").hasClass('mobile-enabled')) {
			$("body").addClass( "mobile-on" );
			
			// Build out our mobile menu
			var $primaryMenu = '';
			var $secondaryMenu = '';
			
			if ($('.menu-primary').length) { 
				$primaryMenu = '<ul>'+$('.menu-primary').html()+'</ul>'; 
			} 
			if ($('.menu-secondary').length) { 
				$secondaryMenu = '<ul>'+$('.menu-secondary').html()+'</ul>'; 
			} 
			
			var $mobileMenu = $('<nav id="mobile-menu"><div>'+$primaryMenu+$secondaryMenu+'</div></nav>');
			
			$($mobileMenu).mmenu();
			
			var api = $("#mobile-menu").data( "mmenu" );
			
			// Toggle Button
			$(".toggle-menu").click(function(){
				$(api).trigger("open.mm");
			});	
			
			// On Open
			api.bind( "open", function() {
				$(".toggle-menu").addClass('active');
			});
			
			// On Close
			api.bind( "close", function() {
				$(".toggle-menu").removeClass('active');
			});
			
		}
		
		// Smooth Scroll
		smoothScroll.init({
			speed: 600, // Integer. How fast to complete the scroll in milliseconds
			easing: 'easeInOutCubic', // Easing pattern to use
			updateURL: true, // Boolean. Whether or not to update the URL with the anchor hash on scroll
			offset: 0, // Integer. How far to offset the scrolling anchor location in pixels
			selector: '.continue', // Selector for links (must be a valid CSS selector)
		});
		
	});
	
	// Start animate (WOW)
	if (jQuery("body").hasClass('animate-enabled')) {
		wow.init();
	}

});

// Animate WOW reveal
if (jQuery("body").hasClass('animate-enabled')) {
	wow = new WOW(
		{
		  boxClass:     'animate',     
		  animateClass: 'animated',
		  offset:       20,
		  mobile:       false,
		  live:         false
		}
	);
}