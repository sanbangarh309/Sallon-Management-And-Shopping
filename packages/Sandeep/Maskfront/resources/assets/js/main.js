/* TABS-THROUGH-BUTTON */
$( document.body ).on( 'click', '.language-drop li', function( event ) {

      var $target = $( event.currentTarget );

      $target.closest( '.btn-group' )
         .find( '[data-bind="label"]' ).text( $target.text() )
            .end()
         .children( '.dropdown-toggle' ).dropdown( 'toggle' );

      return false;

   });
/* FIXED==THEME */
		//Scroll Menu
		$(window).scroll(function() {    
			var scroll = $(window).scrollTop();

			if (scroll >= 400) {
				$(".main-menu").addClass("navbar-fixed-top");
			} else {
				$(".main-menu").removeClass("navbar-fixed-top");
			}
		});
		
//Preloader
	
	$(window).load(function(){
		$('.preloader').fadeOut(5000,function(){$(this).remove();});
	});

/* TABS-THROUGH-BUTTON */
$( document.body ).on( 'click', '.cst-drop2 li', function( event ) {

      var $target = $( event.currentTarget );

      $target.closest( '.btn-group' )
         .find( '[data-bind="label"]' ).text( $target.text() )
            .end()
         .children( '.dropdown-toggle' ).dropdown( 'toggle' );

      return false;

});
$(".cst-drop2 a").click(function(){
		$(this).tab('show');
		 $(".cst-drop2").find( 'li.active' ).removeClass( 'active' );
		
});

jQuery(function($) {
	//Initiat WOW JS
	new WOW().init();
	//smoothScroll
	if ($.isFunction('smoothScroll')) {
		smoothScroll.init();
	}
	
});

//FANCY-CUSTOM-SCROLL
(function($){
	$(window).on("load",function(){
		if ($.isFunction('mCustomScrollbar')) {
			$("#content-1").mCustomScrollbar({
				autoHideScrollbar:true,
				theme:"rounded"
			});
			$("#content-2").mCustomScrollbar({
				autoHideScrollbar:true,
				theme:"rounded"
			});
			$("#content-3").mCustomScrollbar({
				autoHideScrollbar:true,
				theme:"rounded"
			});
			$("#content-4").mCustomScrollbar({
				autoHideScrollbar:true,
				theme:"rounded"
			});
			$("#content-5").mCustomScrollbar({
				autoHideScrollbar:true,
				theme:"rounded"
			});
		}
		
	});
})(jQuery);

$(".reg-link").click(function(){
	$('#lost-modal').modal('hide');
	$('#customer_register').modal('hide');
    $("#login-modal").modal('show');
 });
   $(".myloginpage1").click(function(){
	$('#lost-modal').modal('hide');
	$('#login-modal').modal('hide');
    $("#customer_register").modal('show');
 });

   $(".lostform").click(function(){
	$('#customer_register').modal('hide');
	$('#login-modal').modal('hide');
    $("#lost-modal").modal('show');
 });
 

 

	
jQuery('#next-column').click(function(event) {
	event.preventDefault();
	jQuery('.table-container').animate({scrollLeft:'+=90'}, 'slow',function(){
		jQuery(this).animate({scrollLeft:0},0)
		jQuery('.sliding-window>:first',this).appendTo(jQuery('.sliding-window',this))
	});
});

jQuery('#previous-column').click(function(event) {
	event.preventDefault();
	jQuery('.sliding-window>:last').prependTo(jQuery('.sliding-window'))
	jQuery('.table-container').animate({scrollLeft:90},0).animate({scrollLeft:0},'slow');
});


/**BACK-TO-TOP-JS**/
// fade in #back-top//
$(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-top').fadeIn();
        } else {
            $('.back-top').fadeOut();
        }
    });

    // scroll body to 0px on click
    $('.back-top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 1600);
        return false;
    });
});      

//PARRALAX-EFFECT

(function ($) {

	$.fn.parallax = function () {

		var varWidthWindow = $(window).width();

		if (varWidthWindow < 768)
		{
			$(this).css('background-position', "");
			return;
		}

		$(this).each(function () {

			var $obj = $(this);

			console.log(this);

			$(window).scroll(function () {

				var varTopScroll = $(window).scrollTop();
				var varTopElement = $obj.offset().top;
				var varHeightWindow = $(window).height();

				var varElementVisibilityStartPoint = varTopElement - varHeightWindow;
				varElementVisibilityStartPoint = (varElementVisibilityStartPoint < 0) ? 0 : varElementVisibilityStartPoint;

				if (varTopElement + varHeightWindow < varTopScroll || varTopElement > varTopScroll + varHeightWindow) {
					/* console.log("Out of view"); */
					return;
				}

				var yPos = -((varTopScroll - varElementVisibilityStartPoint) * $obj.data('speed'));
				var bgpos = '50% ' + yPos + 'px';

				$obj.css('background-position', bgpos);

			});
		});

		return this;

	};


}(jQuery));

$(".myParallax").parallax();