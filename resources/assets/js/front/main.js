(function ($) {
    "use strict";
    jQuery(document).on('ready', function() {
        /*---------------------------------*/
        /*---------- Scroll to top ----------*/
        /*---------------------------------*/
        var scroll_top = $('.scroll-top');
        scroll_top.on('click', function() {      // When arrow is clicked
            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 2000);
        });

        /*================================
        ============ Top Bar =============
        =================================*/

        /*--show and hide scroll to top Active --*/
        var scroll_top_active =  $('.scroll-top.active');
        var scroll_top = $('.scroll-top')
        $(window).on('scroll', function() {
            if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
                scroll_top_active.removeClass('active');
                scroll_top.addClass('active');    // Fade in the arrow
            } else {
                scroll_top.removeClass('active');   // Else fade out the arrow
            }
        });


    });

    $(window).on('scroll', function() {
        /*================================
        ============= Main menu Fixed  =============
        =================================*/
        var fixed_top = $(".header-wrapper");
        var menufixed = $(".menu-fixed");

        if( $(this).scrollTop() > 200 ) {
            fixed_top.addClass("menu-fixed animated fadeInDown");
            menufixed.addClass("slick-position");
        }
        else{
            fixed_top.removeClass("menu-fixed animated fadeInDown");
            menufixed.removeClass("slick-position");
        }
        var back_top = $('#back-to-top');

        if ($(this).scrollTop() > 200) {
            back_top.fadeIn();
        } else {
            back_top.fadeOut();
        }

        /*================================
        ============= Scroll Fixed  =============
        =================================*/
        var back_top = $('#back-to-top');

        if ($(this).scrollTop() > 200) {
            back_top.fadeIn();
        } else {
            back_top.fadeOut();
        }


    });


})(jQuery);