// Progress bar

			var progress_key = '';

			$(document).ready(function() {
				$("#pb1").progressBar({ barImage: 'images/progress.png', showText: true} );
			});
			
// Twitter
		
		  getTwitters('twitter', { 
		  id: tw_user, // your twitter account name
		  count: 1, 
		  enableLinks: true, 
		  ignoreReplies: true, 
		  clearContents: true,
		  template: '<div class="tweet"> &laquo;%text%&raquo; </div><div class="from"><div class="float_left">%time% </div><div class="float_right"><a href="https://twitter.com/#!/%user_screen_name%">Follow us</a> on twitter!</div></div>'
		});
		  
// Countdown

		$(document).ready( function(){				
	
				// Handle the countdown timer
				var theYear = parseInt( 2011 ); // year
				var theDay = parseInt( 31 );	// day
				var theMonth = parseInt( 7 );	// month	
				var theEnd = new Date(theYear, theMonth - 1, theDay); 
				
				$('#countdown').countdown({until: theEnd, format: 'dHMS', regional: 'en-EN' });

			});
		  
// Notify slider

	$(document).ready(function(){
    
    $(".notify_but a").click(function(){
        $(this).next("div").slideToggle("fast")
        .siblings("div:visible").slideUp("fast");
        $(this).toggleClass("active");
        $(this).siblings("a").removeClass("active");
    });

	});
	
// Notify button changer
	
	$(document).ready(function(){
        $('a#note').hover(
            function() {
                $('#img').attr('src', 'images/notify_but_2.png');
            },
            function() {
                $('#img').attr('src', 'images/notify_but_1.png');
            }
        );    
	});

// Image preloader

        jQuery.preloadImages = function()
	{
	  for(var i = 0; i<arguments.length; i++)
	  {
		jQuery("<img>").attr("src", arguments[i]);
	  }
	}
	$.preloadImages("../images/sub_input.png", "images/sub_button.png", "images/notify_but_2.png"); // The list of images to preload