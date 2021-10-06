        var $slider = $(".slider"),
                $bullets = $(".bullets");
            function calculateHeight() {
                var height = $(".slide.active").outerHeight();
                $slider.height(height);
            }

            $(window).resize(function () {
                calculateHeight();
                clearTimeout($.data(this, "resizeTimer"));
            });

            function resetSlides() {
                $(".slide.inactive").removeClass("inactiveRight").removeClass("inactiveLeft");
            }

            function gotoSlide($activeSlide, $slide, className) {
                $activeSlide.removeClass("active").addClass("inactive " + className);
                $slide.removeClass("inactive").addClass("active");
                calculateHeight();
                resetBullets();
                setTimeout(resetSlides, 300);
            }

            $(".next").on("click", function () {
                var $activeSlide = $(".slide.active"),
                    $nextSlide = $activeSlide.next(".slide").length != 0 ? $activeSlide.next(".slide") : $(".slide:first-child");
                console.log($nextSlide);
                gotoSlide($activeSlide, $nextSlide, "inactiveLeft");
            });
            $(".previous").on("click", function () {
                var $activeSlide = $(".slide.active"),
                    $prevSlide = $activeSlide.prev(".slide").length != 0 ? $activeSlide.prev(".slide") : $(".slide:last-child");

                gotoSlide($activeSlide, $prevSlide, "inactiveRight");
            });
            $(document).on("click", ".bullet", function () {
                if ($(this).hasClass("active")) {
                    return;
                }
                var $activeSlide = $(".slide.active");
                var currentIndex = $activeSlide.index();
                var targetIndex = $(this).index();
                console.log(currentIndex, targetIndex);
                var $theSlide = $(".slide:nth-child(" + (targetIndex + 1) + ")");
                gotoSlide($activeSlide, $theSlide, currentIndex > targetIndex ? "inactiveRight" : "inactiveLeft");
            });
            function addBullets() {
                var total = $(".slide").length,
                    index = $(".slide.active").index();
                for (var i = 0; i < total; i++) {
                    var $bullet = $("<div>").addClass("bullet");
                    if (i == index) {
                        $bullet.addClass("active");
                    }
                    $bullets.append($bullet);
                }
            }
            function resetBullets() {
                $(".bullet.active").removeClass("active");
                var index = $(".slide.active").index() + 1;
                console.log(index);
                $(".bullet:nth-child(" + index + ")").addClass("active");
            }
            addBullets();
            calculateHeight();


            // Get element
$('.video').parent().click(function () {
  if($(this).children(".video").get(0).paused){        $(this).children(".video").get(0).play();   $(this).children(".playpause").fadeOut();
    }else{       $(this).children(".video").get(0).pause();
  $(this).children(".playpause").fadeIn();
    }
});
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();


/*include Js html*/

function includeHTML() {
  var z, i, elmnt, file, xhttp;
  /* Loop through a collection of all HTML elements: */
  z = document.getElementsByTagName("*");
  for (i = 0; i < z.length; i++) {
    elmnt = z[i];
    /*search for elements with a certain atrribute:*/
    file = elmnt.getAttribute("w3-include-html");
    if (file) {
      /* Make an HTTP request using the attribute value as the file name: */
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200) {elmnt.innerHTML = this.responseText;}
          if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
          /* Remove the attribute, and call this function once more: */
          elmnt.removeAttribute("w3-include-html");
          includeHTML();
        }
      }
      xhttp.open("GET", file, true);
      xhttp.send();
      /* Exit the function: */
      return;
    }
  }
}

/*===================================*
    04. MENU JS
    *===================================*/
    //Main navigation scroll spy for shadow
    $(window).on('scroll', function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 150) {
            $('header.fixed-top').addClass('nav-fixed');
        } else {
            $('header.fixed-top').removeClass('nav-fixed');
        }

    });
    
    //Show Hide dropdown-menu Main navigation 
    $( document ).on('ready', function () {
        $( '.dropdown-menu a.dropdown-toggler' ).on( 'click', function () {
            //var $el = $( this );
            //var $parent = $( this ).offsetParent( ".dropdown-menu" );
            if ( !$( this ).next().hasClass( 'show' ) ) {
                $( this ).parents( '.dropdown-menu' ).first().find( '.show' ).removeClass( "show" );
            }
            var $subMenu = $( this ).next( ".dropdown-menu" );
            $subMenu.toggleClass( 'show' );
            
            $( this ).parent( "li" ).toggleClass( 'show' );
    
            $( this ).parents( 'li.nav-item.dropdown.show' ).on( 'hidden.bs.dropdown', function () {
                $( '.dropdown-menu .show' ).removeClass( "show" );
            } );
            
            return false;
        });
    });
    
    //Hide Navbar Dropdown After Click On Links
    var navBar = $(".header_wrap");
    var navbarLinks = navBar.find(".navbar-collapse ul li a.page-scroll");

    $.each( navbarLinks, function() {

      var navbarLink = $(this);

        navbarLink.on('click', function () {
          navBar.find(".navbar-collapse").collapse('hide');
          $("header").removeClass("active");
        });

    });
    
    //Main navigation Active Class Add Remove
    $('.navbar-toggler').on('click', function() {
        $("header").toggleClass("active");
        if($('.search-overlay').hasClass('open'))
        {
            $(".search-overlay").removeClass('open');
            $(".search_trigger").removeClass('open');
        }
    });
    
    $( document ).on('ready', function() {
        if ($('.header_wrap').hasClass("fixed-top") && !$('.header_wrap').hasClass("transparent_header") && !$('.header_wrap').hasClass("no-sticky")) {
            $(".header_wrap").before('<div class="header_sticky_bar d-none"></div>');
        }
    });
    
    $(window).on('scroll', function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 150) {
            $('.header_sticky_bar').removeClass('d-none');
            $('header.no-sticky').removeClass('nav-fixed');
            
        } else {
            $('.header_sticky_bar').addClass('d-none');
        }

    });
    
    var setHeight = function() {
        var height_header = $(".header_wrap").height();
        $('.header_sticky_bar').css({'height':height_header});
    };
    
    $(window).on('load', function() {
      setHeight();
    });
    
    $(window).on('resize', function() {
      setHeight();
    });
    
    $('.sidetoggle').on('click', function () {
        $(this).addClass('open');
        $('body').addClass('sidetoggle_active');
        $('.sidebar_menu').addClass('active');
        $("body").append('<div id="header-overlay" class="header-overlay"></div>');
    });
    
    $(document).on('click', '#header-overlay, .sidemenu_close',function() {
        $('.sidetoggle').removeClass('open');
        $('body').removeClass('sidetoggle_active');
        $('.sidebar_menu').removeClass('active');
        $('#header-overlay').fadeOut('3000',function(){
            $('#header-overlay').remove();
        });  
         return false;
    });
    
    $(".categories_btn").on('click', function() {
        $('.side_navbar_toggler').attr('aria-expanded', 'false');
        $('#navbarSidetoggle').removeClass('show');
    });
    
    $(".side_navbar_toggler").on('click', function() {
        $('.categories_btn').attr('aria-expanded', 'false');
        $('#navCatContent').removeClass('show');
    });
    
    $(".pr_search_trigger").on('click', function() {
        $(this).toggleClass('show');
        $('.product_search_form').toggleClass('show');
    });
    
    var rclass = true;
    
    $("html").on('click', function () {
        if (rclass) {
            $('.categories_btn').addClass('collapsed');
            $('.categories_btn,.side_navbar_toggler').attr('aria-expanded', 'false');
            $('#navCatContent,#navbarSidetoggle').removeClass('show');
        }
        rclass = true;
    });
    
    $(".categories_btn,#navCatContent,#navbarSidetoggle .navbar-nav,.side_navbar_toggler").on('click', function() {
        rclass = false;
    });
    
