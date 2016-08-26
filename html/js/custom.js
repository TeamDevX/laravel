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

$(document).ready(function(){
    var fromTop = $(this).scrollTop();
    if (fromTop === 0) {
        $("body").addClass("scroll_normal");
    }
    
    $(window).scroll(function(){
        var fromTop = $(this).scrollTop();
        if (fromTop === 0) {
            $("body").addClass("scroll_normal");
            $("body").removeClass("scrolling");
            $(".scroll_normal .dot_navigation li:first-child()").addClass("active");
        }else{
            $("body").removeClass("scroll_normal");
            $("body").addClass("scrolling");
        }
    });
    
    // Acordion arrow js starts
    $('.panel a').click(function(e){
        if($(this).find('i').hasClass('fa-caret-down')){
            $(this).find('i').removeClass('fa-caret-down');
            $(this).find('i').addClass('fa-caret-up');
        }
        else{
            $(this).find('i').addClass('fa-caret-down');
            $(this).find('i').removeClass('fa-caret-up');
        }
        e.preventDefault();
    });
    
    // Min-height for sections
    $("section").css("min-height", $(window).innerHeight());
    $(window).resize(function(){
        $("section").css("min-height", $(window).innerHeight());    
    });
    
    // scroll spy js        
    // Cache selectors
    var lastId,
        topMenu = $(".dot_navigation"),
        topMenuHeight = topMenu.outerHeight()-180,
        lastClickedItem,
        // All list items
        menuItems = topMenu.find("a"),
        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function(){
            var item = $($(this).attr("href"));
            if (item.length) { return item; }
        });
    
    // Next section
    var cutoff = $(window).scrollTop();
    var currentsection=0;
    var str=window.location.hash.substr(1);
    if(str.split("scrollto")[1]!='_top'&&str.split("scrollto")[1]!=undefined)
    {
        //console.log(str.split("scrollto")[1]);
        currentsection=parseInt(str.split("scrollto")[1]);
    }
    var downArrow=$(".scroll_down > a");
    downArrow.click(function(e){
        if(currentsection<menuItems.length)
        {
            currentsection++;
        }
        if(currentsection>=menuItems.length-1)
        {
            $(this).hide();
        }
        
        var href =$(menuItems[currentsection]).attr("href") ,
            offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
        $('html, body').stop().animate({ 
            scrollTop: offsetTop
        }, 300);
        if(lastClickedItem)
            $(lastClickedItem).removeClass("animate");
        $(href).addClass("animate");
        lastClickedItem = href;
        e.preventDefault();
    });
    
    // Bind click handler to menu items
    // so we can get a fancy scroll animation
    menuItems.click(function(e){
        currentsection=$(this).attr('data-a');
        if(currentsection<menuItems.length-1)
        {
            $(downArrow).show();
        }
        else
        {
            $(downArrow).hide();
        }
        var href = $(this).attr("href"),
            offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
        $('html, body').stop().animate({ 
            scrollTop: offsetTop
        }, 300);
        if(lastClickedItem)
            $(lastClickedItem).removeClass("animate");
        $(href).addClass("animate");
        lastClickedItem = href;
        //e.preventDefault();
    });
    
    // Bind to scroll
    $(window).scroll(function(){
        // Get container scroll position
        var fromTop = $(this).scrollTop()+topMenuHeight;
        // Get id of current scroll item
        var cur = scrollItems.map(function(){
            if ($(this).offset().top < fromTop)
                return this;
        });
        // Get the id of the current element
        cur = cur[cur.length-1];
        var id = cur && cur.length ? cur[0].id : "";
        if (lastId !== id) {
            lastId = id;          
            // Set/remove active class
            menuItems
                .parent().removeClass("active")
                .end().filter("[href='#"+id+"']").parent().addClass("active");
            if(lastClickedItem)
                $(lastClickedItem).removeClass("animate");
            $('#'+id).addClass("animate");
            lastClickedItem = '#'+id;
        }                   
    });

    var header_height = $('.header').height();
    var scroll_offset = header_height;
    var speed = $('body').attr('data-speed');
    $(window).bind('scroll');
    //for smooth scrolling to hash when page loaded, but this is not working as the browser default behaviour could not be stopped from here.
    if (window.location.hash) {
        $("section").css({"padding-top":"40px"});
        $(window).scrollTop();
        setTimeout(function() {
            $.scrollTo(document.getElementById(window.location.hash.replace('#', '')), Number(speed));
        }, 1);
        $(".dot_navigation, .scroll_down").click(function(){
            $("section.animate").css({"padding-top":"0px"});
        });
    }       
});