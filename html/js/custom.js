// Section scrolling js
// [KN] SimplyFade
//
// Based on http://trulycode.com/bytes/simple-appear-on-scroll/
// Rewritten by Kevin Nunn
// KevinNunn.com

jQuery(function(){
var $win = $(window);
var $img = $('.bg_change'); // Change this to affect your desired element.

$win.on('scroll', function () {
    var scrollTop = $win.scrollTop(); 					// This is 0 at the top, and tracks your scrolling progress
	var startFadePos = 100; 							// The point to start fading in from the bottom in pixels. 
	var startFadeBot = $win.height() - startFadePos;	// Creates the point based on window height.
	var endFadePos = 100;								// The point to end the fade
	var usePercent = false;
	//var endFadeTop = 0;
	var startFadeNumber = 0.2; 							// Fade starts at 20$ opacity. This number must be between 0-1. 0.2 = 20%
	var endFadeNumber = 1;
	var scrollArea = $win.height() - (startFadePos+endFadePos);		// In case it is needed.
	
    $img.each(function () {
			
        var $self = $(this);
        var prev=$self.offset(); // Grabs Top and Left values and writes as properties. These are static values
		var scrollPos = prev.top - scrollTop;
		var pt = 0;
		if(scrollPos < endFadePos){						// Changes opacity to endFadeNumber when scrolled above endFadePos.
			$self.css({
				opacity: endFadeNumber
			});
		} else if (scrollPos > startFadeBot){			// Changes opacity to startFadeNumber opacity before scrolled to startFadePos.
			$self.css({
				opacity: startFadeNumber
			});
		} else if (scrollPos < startFadeBot && scrollPos > endFadePos){								// Here's where the magic happens
			var percentComp = 1-(scrollPos-endFadePos)/(startFadeBot-endFadePos); 
			if(startFadeNumber != 0 || endFadeNumber != 1){
				var theDiff = endFadeNumber - startFadeNumber;										// Start opacity change at startFadeNumber if not 0.
				var newNumber = startFadeNumber+percentComp-(startFadeNumber*percentComp);
			}
			$self.css({
				opacity: newNumber
			});
		}
    });

}).scroll();
})