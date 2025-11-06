loadScript(siteUrl + "js/magnific-popup/jquery.magnific-popup.min.js",
    function () {
        $("body").magnificPopup({ delegate: "a.pop", removalDelay: 500, callbacks: { beforeOpen: function () { this.st.mainClass = this.st.el.attr("data-effect") } }, midClick: !0 }), $(".tlocrt").magnificPopup({ delegate: "a", type: "image", tLoading: "Loading image #%curr%...", mainClass: "mfp-img-mobile", gallery: { enabled: !0, navigateByImgClick: !0, preload: [0, 1] }, image: { tError: '<a href="%url%">The image #%curr%</a> could not be loaded.' } }), $(".gal").magnificPopup({ delegate: "a", type: "image", tLoading: "Loading image #%curr%...", mainClass: "mfp-img-mobile", gallery: { enabled: !0, navigateByImgClick: !0, preload: [0, 1] }, image: { tError: '<a href="%url%">The image #%curr%</a> could not be loaded.' } }), $(".txt-img").magnificPopup({ delegate: "a", type: "image", tLoading: "Loading image #%curr%...", mainClass: "mfp-img-mobile", gallery: { enabled: !0, navigateByImgClick: !0, preload: [0, 1] }, image: { tError: '<a href="%url%">The image #%curr%</a> could not be loaded.' } });
    });
loadScript(siteUrl + "js/bxslider/jquery.bxslider.js",
    function () {
        var slider = $('.slider-bg').bxSlider({
            mode: 'fade',
            controls: false,
            pager: false,
            auto: true,
            pause: 10000,
            speed: 1000
        });
        $('.details-slider').bxSlider({
            pagerCustom: '#bx-pager',
            mode: 'fade'
        });

        $('.client-comments ul').bxSlider({
            mode: 'fade',
            pager: false,
            controls: false,
            auto: true
        });
        $('#slider-next').click(function () {
            slider.goToNextSlide();
            return false;
        });
        $('#slider-prev').click(function () {
            slider.goToPrevSlide();
            return false;
        });
        $('.offers').bxSlider({
            mode: 'fade',
            controls: false,
            pager: true,
            auto: true,
            pause: 15000,
            speed: 1000
        });
        $('#bx-pager').bxSlider({
            mode: 'horizontal',
            auto: false,
            pager: false,
            controls: true,
            speed: 1000,
            pause: 6000,
            minSlides: 7,
            maxSlides: 7,
            slideWidth: 123,
            slideMargin: 6
        });
    });

$(document).ready(function () {
    $('.select-trigger').click(function () {
        if ($(this).parent(".select-frame").hasClass('inactive')) {
            $(".select-frame").addClass('inactive');
            $(this).parent(".select-frame").removeClass('inactive');
        } else {
            $(this).parent(".select-frame").addClass('inactive');
        }
    });
    $(".select-trigger2").click(function () {
        if ($(this).parent(".select-frame").hasClass("inactive")) {
            $(this).parent(".select-frame").removeClass("inactive");
        } else {
            $(this).parent(".select-frame").addClass("inactive");
        }
    });
    $(".select-trigger3").click(function () {
        if ($(this).parent(".select-frame").hasClass("inactive")) {
            $(this).parent(".select-frame").removeClass("inactive");
        } else {
            $(this).parent(".select-frame").addClass("inactive");
        }
    });
    $(".select-ul li a").click(function () {
        var id_selectboxa = $(this).parent().parent().parent().prev().attr("id");
        $("#" + id_selectboxa).parent().addClass("active-select");

        var id_optiona = $(this).attr("rel");
        var title = $(this).attr("data-title");

        $("#" + id_selectboxa + " option").removeAttr("selected");
        $("#" + id_selectboxa + " option#" + id_optiona).attr("selected", true);

        $(".active-select li").removeClass("active");
        $(this).parent().addClass("active");
        $(this).parent().parent().parent().prev().prev().children().text(title);
        $(".active-select").addClass("inactive");
        $(".active-select").removeClass("active-select");
        $("#" + id_selectboxa).change();
    });

    $('.dropdown-title').click(function () {
        if ($(this).parent(".search-frame.s-open").hasClass('inactive')) {
            $(".search-frame.s-open").addClass('inactive');
            $(this).parent(".search-frame.s-open").removeClass('inactive');
        } else {
            $(this).parent(".search-frame.s-open").addClass('inactive');
        }
    });
    $('.dropdown2-title').click(function () {
        if ($(this).parent(".search-frame.s-open").hasClass('inactive')) {
            $(".search-frame.s-open").addClass('inactive');
            $(this).parent(".search-frame.s-open").removeClass('inactive');
        } else {
            $(this).parent(".search-frame.s-open").addClass('inactive');
        }
    });
    $('.comment').click(function () {
        $('#comment').slideDown();
        $("html, body").animate({ scrollTop: $("#comment").offset().top - 200 }, 'slow');
    });
    $('.f-toggle').click(function () {
        $('.filters').slideToggle();
        $("html, body").animate({ scrollTop: $('.f-toggle').offset().top - 40 }, 'slow');
    });
    $('.nav-toggle').click(function () {
        $(this).next().slideToggle();
    });
    $('.c-more').click(function () {
        var id = $(this).attr('id');
        $('#' + id + '-container').slideDown();
        $("html, body").animate({ scrollTop: $('#' + id + '-container').offset().top - 200 }, 'slow');
    });

    $('.sub-service-title').click(function () {
        $(this).next().slideToggle();
        $(this).toggleClass("coll");
    });

    $('.label-link').click(function () {
        if ($(this).parent(".search-frame").hasClass('inactive')) {
            $(".search-frame").addClass('inactive');
            $(this).parent(".search-frame").removeClass('inactive');
        } else {
            $(this).parent(".search-frame").addClass('inactive');
        }
    });
    $('.scroll-details').click(function () {
        var id = $(this).attr('id');
        $('html, body').animate({
            scrollTop: $('#' + id + '-container').offset().top - 120
        }, 500);
    });
    $('.search-toggle').click(function () {
        $(this).toggleClass("x");
        $('.hidden-search').toggleClass("ma");
        $('.search').toggleClass("expanded");
        $('.site-search').toggleClass("expanded");

    });
    $('.drop').click(function () {
        $(this).toggleClass('expanded');
        $(this).next().slideToggle();
    });
    $('.close-comment').click(function () {
        $('#comment').slideToggle();
    });
    var viewportWidth = $(window).width();
    $('.close-c').click(function () {
        $(this).parent().slideToggle();
    });
    $('.s-btn').click(function () {
        $(this).toggleClass('expanded');
        $('.search-row').toggle();
        $('.search-row').toggleClass('s-expanded');

        if (viewportWidth > 1079.99) {
            $("html, body").animate({ scrollTop: $('.s-btn').offset().top - 180 }, 'slow');
        } else {
            $("html, body").animate({ scrollTop: $('.s-btn').offset().top - 40 }, 'slow');
        }
    });

    $('.s-more').click(function () {
        $(this).toggleClass('expanded');
        $('.search-row').toggleClass('details-expanded');
        $('.search-more').toggle();
        $('.search-more').toggleClass('s-expanded');
    });

    $('.btn.d').click(function () {
        $(this).toggleClass('expanded');

        $('.hidden-form').slideToggle();
        $('html, body').animate({
            scrollTop: $(".hidden-form").offset().top - 180
        }, 'slow');
    });

    $('.group-title').click(function () {
        $(this).toggleClass('expanded');
        $(this).next().toggleClass('expanded');
        $(this).next().toggle();
    });

    $("input").focus(function () {
        $(this).parent(".hidden-select").addClass("key-in");
        $('input.price-input').keyup(function (event) {
            if (event.which >= 37 && event.which <= 40) {
                event.preventDefault();
            }
            $(this).val(function (index, value) {
                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
            });
        });
    });
    $("input").focusout(function () {
        $(this).parent(".hidden-select").removeClass("key-in");
    });
    $(".search-frame").click(function (e) {
        e.stopPropagation();
    });
    $(document).click(function () {
        $(".search-frame").addClass("inactive");
    });
    $(":checkbox").on('click', function () {
        var fields = '';
        var klasa = $(this).attr('class');

        $(":checkbox." + klasa).each(function () {
            if (this.checked) {
                fields += ', ' + $(this).attr('data-title');
            }
        });
        var txt = fields.substring(2);
        $('#' + klasa + ' input').val($.trim(txt));
        $('#' + klasa + ' span').addClass(' inside');
    });

    $('.group2-title').click(function () {
        if ($(this).parent().hasClass('ac')) {
            $('.chck-group li').removeClass('ac');

        } else {
            $('.chck-group li').removeClass('ac');
            $(this).parent().addClass('ac');
        }
    });
    $(window).resize(function () {
        $('.services .box .title, .services .box .title').setAllToMaxHeight();
        $('.h,.h').setAllToMaxHeight();
        $('.h-mob,.h-mob').setAllToMaxHeight();
        $('.eq-h .w5,.eq-h .w5').setAllToMaxHeight();
        $('.ul-box,.ul-box').setAllToMaxHeight();
    });
});





$.fn.setAllToMaxHeight = function () {
    return this.height(Math.max.apply(this, $.map(this, function (e) { return $(e).height() })));
};
$(window).load(function () {
    $('.services .box .title, .services .box .title').setAllToMaxHeight();
    $('.h,.h').setAllToMaxHeight();
    $('.h-mob,.h-mob').setAllToMaxHeight();
    $('.eq-h .w5,.eq-h .w5').setAllToMaxHeight();
    $('.ul-box,.ul-box').setAllToMaxHeight();
});




function onSubmit(response) {
    var r = response;
    sjx('checkCaptcha', r); return false;
}
function onSubmitComment(response) {
    var r = response;
    sjx('checkCaptchaComment', r);
}