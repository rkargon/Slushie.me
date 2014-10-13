//Copyright 2013 Slushie.me
//Programmers:
//	- Will Povell (wpovell@slushie.me)
//	- Raphael Kargon (rkargon@slushie.me)

			$(function () {
  
 			 	var msie6 = $.browser == 'msie' && $.browser.version < 7;
  				$('.sidebar').ready(function(){$('.sidebar').css('width', "100%");});
  
 				 if (!msie6) {
 					   var sidebar_top = ($('.sidebar_title').outerHeight()+$('.menubar').outerHeight()+16)+"px";  
 					  //var navbar_height=($('.menubar').height()+8)+"px"; //used for adjusting float height of sidebar. Not used at the moment
   					
   						//var sidebar_size = ($('body').height() - $('.sidebar').offset().top + $(window).scrollTop()) + "px"; 
   						//$('.sidebar').css('height', sidebar_size);
   						
   					 $(window).scroll(function (event) {
   					 
      					if (($(window).scrollTop())>=$('.logo').outerHeight(true)) { //scrolled pass banner image?
      	 	 				// if so, add the fixed class
       						 $('.sidebar').addClass('fixed');
       						 $('.sidebar.fixed').css('top', sidebar_top);
       						 $('.sidebar.fixed').css('width', "28%");
       						 $('.sidebar_title').addClass('fixed');
       						 $('.sidebar_title').css('width', "28%");
       						 $('.menubar').addClass('fixed');
							 $('.navbar_logo').addClass('visible');
       						 
       						// sidebar_size = ($('body').height() - $('.sidebar').offset().top + $(window).scrollTop()) + "px"; 
       						 $('.sidebar').css('height', '80%');
    				    } else {
     					   // otherwise remove it
       				   		$('.sidebar.fixed').css('top', 'auto');
       						$('.sidebar.fixed').css('width', "100%");
     					   $('.sidebar').removeClass('fixed');
       				  	 	
							$('.sidebar_title').css('width', "100%");
							$('.sidebar_title').removeClass('fixed');
							
     					   $('.menubar').removeClass('fixed');
					   $('.navbar_logo').removeClass('visible');
     					   
     					  // sidebar_size = ($('body').height() - $('.sidebar').offset().top + $(window).scrollTop()) + "px";
     					   $('.sidebar').css('height', '80%');
     					}
   				 });
   				 
   				  $(window).resize(function (event) {
   					 
                    	//sidebar_size = $('body').height() - $('.sidebar').offset().top+$(window).scrollTop()+"px";
      					//$('.sidebar').css('height', sidebar_size);
      			});
   				 
 			}  
		});