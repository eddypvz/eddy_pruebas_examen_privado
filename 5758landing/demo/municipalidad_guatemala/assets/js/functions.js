/*global jQuery */
/* Contents
// ------------------------------------------------>
	1.  Background INSERT
	2.  NAVBAR FIXED
	3.	NAVBAR TOGGLE
	4.  NAVBAR SCROLL TO
	5.  NAVBAR SCROLLING SECTION
    6.  AJAX MAILCHIMP
    7.  AJAX CAMPAIGN MONITOR
	8.  COUNTER UP
    9.  OWL CAROUSEL
    10. MAGNIFIC POPUP VIDEO
    11. AJAX CONTACT FORM
    12. SCROLL TO
*/
(function($) {
    "use strict";

    /* ------------------  Background INSERT ------------------ */

    var $bgSection = $(".bg-section");

    $bgSection.each(function() {
        var bgSrc = $(this).children("img").attr("src");
        var bgUrl = 'url(' + bgSrc + ')';
        $(this).parent().css("backgroundImage", bgUrl);
        $(this).parent().addClass("bg-section");
        $(this).remove();
    });

    var $bgSection = $(".bg-pattern");

    $bgSection.each(function() {
        var bgSrc = $(this).children("img").attr("src");
        var bgUrl = 'url(' + bgSrc + ')';
        $(this).parent().css("backgroundImage", bgUrl);
        $(this).parent().addClass("bg-pattern");
        $(this).remove();
    });

    /* ------------------ NAVBAR FIXED ------------------ */

    $(window).scroll(function() {
        /* affix after scrolling 100px */
        if (
            $(document).scrollTop() > $(window).height() ||
            $(document).scrollTop() > 105
        ) {
            $(".navbar-sticky").addClass("navbar-fixed");
        } else {
            $(".navbar-sticky").removeClass("navbar-fixed");
        }
    });

    /* ------------------  NAVBAR TOGGLE ------------------ */

    $('.navbar-toggler').on('click', function() {
        $('.navbar-toggler-icon').toggleClass('active');
        $('.navbar-collapse').toggleClass('show')
    });

    /* ------------------  NAVBAR SCROLL TO ------------------ */

    var aScroll = $('.nav-item .nav-link'),
        $navbarCollapse = $('.navbar-collapse');
    aScroll.on('click', function(event) {
        var target = $($(this).attr('href'));
        $(this).parent(".nav-item").siblings().removeClass('active');
        $(this).parent('.nav-item').addClass('active');

        if (target.length > 0) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            });
        }

        // If click link and navabr is show
        if ($('.navbar-collapse').hasClass('show')) {
            $('.navbar-collapse').toggleClass('show');
            $('.navbar-toggler-icon').removeClass('active');
            $('.navbar-toggler').toggleClass('collapsed');
        }
    });

    /* ------------------ NAVBAR SCROLLING SECTION ------------------ */

    var $section = $('section'),
        $bodyScroll = $('.body-scroll');
    if ($bodyScroll.length > 0) {
        $(window).on("scroll", function() {
            $section.each(function() {
                var sectionID = $(this).attr("id"),
                    sectionTop = $(this).offset().top - 80,
                    sectionHight = $(this).outerHeight(),
                    wScroll = $(window).scrollTop(),
                    $navHref = $("a[href='#" + sectionID + "']"),
                    $nav = $('.navbar-nav').find($navHref).parent();
                if (wScroll > sectionTop - 1 && wScroll < sectionTop + sectionHight - 1) {
                    $nav.addClass('active');
                    $nav.siblings().removeClass('active');
                }
            });
        });
    }

    /* ------------------  COUNTER UP ------------------ */

    $(".counting").counterUp({
        delay: 10,
        time: 1000
    });

    /* ------------------ OWL CAROUSEL ------------------ */

    $(".owl-carousel").each(function() {
        var $Carousel = $(this);
        $Carousel.owlCarousel({
            loop: $Carousel.data('loop'),
            autoplay: $Carousel.data("autoplay"),
            margin: $Carousel.data('space'),
            nav: $Carousel.data('nav'),
            dots: $Carousel.data('dots'),
            dotsSpeed: $Carousel.data('speed'),
            center: $Carousel.data('center'),
            thumbs: true,
            thumbsPrerendered: true,
            thumbContainerClass: 'owl-thumbs',
            thumbItemClass: 'owl-thumb-item',
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: $Carousel.data('slide-res')
                },
                1000: {
                    items: $Carousel.data('slide'),
                }
            }
        });
    });


    /* ------------------  SCROLL TO ------------------ */

    var aScroll = $('.scroll-to');
    aScroll.on('click', function(event) {
        var target = $($(this).attr('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
}(jQuery));
