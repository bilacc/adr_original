/* functions1509.js - Clean, Upgraded, Malware-Free */
$(document).ready(function () {

    // Magnific Popup - Updated to v1.2.0
    $('body').magnificPopup({
        delegate: 'a.pop',
        removalDelay: 500,
        callbacks: { beforeOpen: function () { this.st.mainClass = this.st.el.attr('data-effect'); } },
        midClick: true
    });

    $('.tlocrt, .gal, .txt-img').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: { enabled: true, navigateByImgClick: true, preload: [0, 1] },
        image: { tError: '<a href="%url%">The image #%curr%</a> could not be loaded.' }
    });

    // Swiper Replaces bxSlider - Modern, Fast, Touch-Optimized
    new Swiper('.slider-bg', {
        loop: true,
        autoplay: { delay: 10000 },
        speed: 1000,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        pagination: false,
        navigation: false
    });

    new Swiper('.details-slider', {
        pagination: { el: '#bx-pager', type: 'bullets' },
        effect: 'fade'
    });

    new Swiper('.client-comments ul', {
        loop: true,
        autoplay: { delay: 5000 },
        effect: 'fade',
        pagination: false,
        navigation: false
    });

    new Swiper('.offers', {
        loop: true,
        autoplay: { delay: 15000 },
        speed: 1000,
        effect: 'fade',
        pagination: { el: '.swiper-pagination', clickable: true }
    });

    new Swiper('#bx-pager', {
        slidesPerView: 7,
        spaceBetween: 6,
        watchSlidesProgress: true,
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
    });

    // Navigation Controls (for old bxSlider compatibility)
    $('#slider-next').on('click', function () {
        $('.slider-bg').get(0).swiper.slideNext();
        return false;
    });
    $('#slider-prev').on('click', function () {
        $('.slider-bg').get(0).swiper.slidePrev();
        return false;
    });

    // === All Your Original Interactive Logic (Cleaned & Modernized) ===
    $('.select-trigger, .select-trigger2, .select-trigger3').on('click', function () {
        var $frame = $(this).closest('.select-frame');
        $('.select-frame').not($frame).addClass('inactive');
        $frame.toggleClass('inactive');
    });

    $('.select-ul li a').on('click', function () {
        var $this = $(this),
            id = $this.closest('.select-frame').find('select').attr('id'),
            rel = $this.attr('rel'),
            title = $this.attr('data-title');

        $('#' + id + ' option').prop('selected', false);
        $('#' + id + ' option#' + rel).prop('selected', true);
        $this.closest('ul').find('li').removeClass('active');
        $this.parent().addClass('active');
        $this.closest('.select-frame').prev().find('.dropdown-title').text(title);
        $('.select-frame').addClass('inactive');
        $('#' + id).trigger('change');
    });

    $('.dropdown-title, .dropdown2-title, .label-link').on('click', function () {
        var $frame = $(this).closest('.search-frame');
        $('.search-frame').not($frame).addClass('inactive');
        $frame.toggleClass('inactive');
    });

    $('.comment').on('click', function () {
        $('#comment').slideDown();
        $('html, body').animate({ scrollTop: $('#comment').offset().top - 200 }, 'slow');
    });

    $('.nav-toggle').on('click', function () { $(this).next().slideToggle(); });
    $('.c-more').on('click', function () {
        var id = $(this).attr('id');
        $('#' + id + '-container').slideDown();
        $('html, body').animate({ scrollTop: $('#' + id + '-container').offset().top - 200 }, 'slow');
    });
    $('.sub-service-title').on('click', function () {
        $(this).next().slideToggle();
        $(this).toggleClass('coll');
    });
    $('.scroll-details').on('click', function () {
        var id = $(this).attr('id');
        $('html, body').animate({ scrollTop: $('#' + id + '-container').offset().top - 120 }, 500);
    });
    $('.search-toggle').on('click', function () {
        $(this).toggleClass('x');
        $('.hidden-search, .search, .site-search').toggleClass('ma expanded');
    });
    $('.drop').on('click', function () {
        $(this).toggleClass('expanded').next().slideToggle();
    });
    $('.close-comment, .close-c').on('click', function () { $(this).parent().slideToggle(); });
    $('.s-btn').on('click', function () {
        $(this).toggleClass('expanded');
        $('.search-row').toggleClass('s-expanded').toggle();
    });
    $('.s-more').on('click', function () {
        $(this).toggleClass('expanded');
        $('.search-more').toggleClass('s-expanded').toggle();
    });
    $('.btn.d').on('click', function () {
        $(this).toggleClass('expanded');
        $('.hidden-form').slideToggle();
        $('html, body').animate({ scrollTop: $('.hidden-form').offset().top - 180 }, 'slow');
    });
    $('.group-title').on('click', function () {
        $(this).toggleClass('expanded').next().toggleClass('expanded').toggle();
    });

    // Checkbox Group Logic
    $(":checkbox").on('click', function () {
        var cls = $(this).attr('class'), fields = '';
        $(':checkbox.' + cls).each(function () {
            if (this.checked) fields += ', ' + $(this).attr('data-title');
        });
        var txt = fields.substring(2);
        $('#' + cls + ' input').val($.trim(txt));
        $('#' + cls + ' span').addClass('inside');
    });

    $('.group2-title').on('click', function () {
        $(this).parent().toggleClass('ac');
        $('.chck-group li').not($(this).parent()).removeClass('ac');
    });

    // Form Input Formatting
    $('input').on('focus', function () { $(this).closest('.hidden-select').addClass('key-in'); });
    $('input').on('focusout', function () { $(this).closest('.hidden-select').removeClass('key-in'); });
    $('input.price-input').on('keyup', function (e) {
        if (e.which >= 37 && e.which <= 40) return;
        $(this).val(function (i, v) {
            return v.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });
    });

    // Click Outside to Close
    $('.search-frame').on('click', function (e) { e.stopPropagation(); });
    $(document).on('click', function () { $('.search-frame').addClass('inactive'); });

    // Equal Heights (Modern)
    function equalize(selector) {
        var max = 0;
        $(selector).css({ 'min-height': 0, height: 'auto' }).each(function () {
            max = Math.max(max, $(this).height());
        }).css('height', max);
    }
    $(window).on('load resize', function () {
        setTimeout(function () {
            equalize('.ul-box'); equalize('.h'); equalize('.h-mob');
            equalize('.eq-h .w5'); $('.services .box .title').css('height', 'auto').setAllToMaxHeight();
        }, 50);
    });

    // Slider Height
    function resizeDiv() {
        var vph = $(window).height();
        $('.slider-bg-frame, .slider-bg-frame li, .description-frame .center, .slider-bg-frame .bx-viewport').css('height', vph + 'px');
    }
    $(document).ready(resizeDiv);
    $(window).on('resize', resizeDiv);

    // Dynamic Animation Load
    (function () {
        var isDesktop = $(window).width() > 1160;
        $('#animation').load('include/' + (isDesktop ? 'desktop' : 'mobile') + '.php', { lang_data: siteLang });
    })();

    // Header Scroll Class
    $(window).on('scroll', function () {
        if ($(window).width() >= 940) {
            $('.header').toggleClass('scrolled', $(document).scrollTop() >= 50);
        }
    });

});