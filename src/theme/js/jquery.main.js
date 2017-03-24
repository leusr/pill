$( function () {

    external_links();
    spam_protected_emails();
    window_scroll();
    mobileNav_init();
    mainNav_icon();
    scf_forms();
    wedding_request();
    bxslider_init();
    gallery_init();

});

/**
 * Throttle down event triggering
 * Source: underscore.js
 *
 * @param {number} delay
 * @param {function} callback
 * @return {function}
 */
function _throttle(delay, callback) {
    var timeout, lastExecute = 0;
    return function (event, ignoreThrottle) {
        var elapsed = +new Date() - lastExecute;

        function run() {
            lastExecute = +new Date();
            callback.call(undefined, event);
        }

        timeout && clearTimeout(timeout);
        if (elapsed > delay || ignoreThrottle) run();
        else timeout = setTimeout(run, delay - elapsed);
    }
}


// Scroll top & fixed main nav
function window_scroll() {

    var scroll_move = false,
        container = $('div.site-container'),
        main_nav = $('nav.main-nav'),
        // site_header = $('header.site-header'),
        scroll_top = $('nav.scroll-top'),
        scroll_top_a = scroll_top.find('a'),
        main_nav_height = main_nav.outerHeight(),
        wpadminbar = $('#wpadminbar'),
        wpadminbar_height = 0;


    if (wpadminbar.length > 0) {
        wpadminbar_height = wpadminbar.outerHeight();
    }

    var main_nav_offset = main_nav.offset().top - wpadminbar_height;

    // Stop scroll to top anim on user interactions
    var stopAnim = function () {
        if (scroll_move !== false) {
            scroll_move = false;
            $('html, body').stop();
        }
    };

    $(document).on('mousewheel DOMMouseScroll MozMousePixelScroll', stopAnim);
    if (document.addEventListener) {
        document.addEventListener('touchmove', stopAnim, false);
    }

    var setClasses = function () {
        var wTop = $(window).scrollTop(),
            isNavOn = $(main_nav).css('display') !== 'none';

        if (isNavOn) {
            if (wTop > main_nav_offset) {
                main_nav.addClass('fixed').css('margin-top', wpadminbar_height);
                container.css('margin-top', main_nav_height);
            } else {
                main_nav.removeClass('fixed').css('margin-top', 0);
                container.css('margin-top', 0);
            }
        }

        if (scroll_move) {
            return;
        }

        if (wTop > 400) {
            scroll_top.addClass('enabled');
        } else {
            scroll_top.removeClass('enabled');
        }

    };

    var setMargins = function () {
        var bgPos = $('.site-container').css('background-position'),
            isNormal = bgPos === '0px 940px',
            isLarge = bgPos === '0px 1186px';

        if (isNormal || isLarge) {
            setClasses();
        } else {
            container.css('margin-top', 0);
        }

    };

    $(window).on('ready', setClasses);
    $(window).on('scroll', _throttle(100, setClasses));
    $(window).on('resize', _throttle(100, setMargins));

    scroll_top_a.on('click', function (e) {

        e.preventDefault();
        if (scroll_move) {
            return;
        }
        scroll_move = true;
        scroll_top.removeClass('enabled');
        $('html, body').animate({scrollTop: 0}, {
            duration: 800,
            easing: 'easeInOutQuart',
            complete: function () {
                scroll_move = false;
            }
        });

    });

}

// Init mobile navigation
function mobileNav_init() {

    $('.mobile-nav-list').mobileNav({
        'lang': {'code': 'hu'}
    });

}

// Add down arrow
function mainNav_icon() {

    $('.main-nav ul > li.menu-item-has-children > a')
        .append('<i class="icon-down"></i>');

}


// Open external links in new window
function external_links() {

    $('a[rel*=external]').on('click', function (e) {
        e.preventDefault();
        window.open(this.href);
    });

}

// Turn <span class=email> to clickable mailto links
function spam_protected_emails() {

    var spans = $('span.email'),
        j = spans.length;
    for (var i = 0; i < j; i++) {
        var html = $(spans[i]).html(),
            text = html.replace(/(<([^>]+)>)/ig, ''),
            before = html.replace(text, ''),
            reps = {' kukac ': '@', ' pont ': '.'};

        for (var val in reps) {
            text = text.split(val).join(reps[val]);
        }
        var mailto = '<a class="email" href="mailto:' + text + '" rel="external">' + before + text + '</a>';
        $(spans[i]).replaceWith(mailto);
    }

}

// Swift Contact Form sending
function scf_forms() {

    var showMessagesTime = 4000, // ms
        form = $('form#contact, form#wedding_request'),
        btn = form.find(':submit');

    btn.removeAttr('disabled');
    btn.after('<img class="scf-loader" src="/assets/img/scf-loader.gif">');
    form.prepend('<input type="hidden" name="potato" value="' + ( Math.round(Math.random() * (89576 - 28345)) + 28345 ) + '">');

    form.submit(function (e) {
        e.preventDefault();
        var loader = form.find('img.scf-loader');
        loader.show();

        $.post('/sendform', form.serializeArray())
            .done(function (data) {
                console.log(data);
                loader.hide();
                var result = $.parseJSON(data);
                if (result.errors) {
                    $.each(result.errors, function (i, item) {
                        if (item.field == 'none') {
                            form.append('<div class="result error">' + item.error + '</div>');
                        } else {
                            var field_id = '#' + item.field,
                                field = $(field_id);
                            field.parent().append('<span class="error">' + item.error + '</span>');
                        }
                    });
                }
                if (result.success) {
                    form.append('<div class="result success">' + result.success + '</div>');
                    form[0].reset();
                }
                setTimeout(function () {
                    var resitems = form.find('div.result, span.error');
                    if (resitems.length > 0) {
                        resitems.fadeOut(700, 'easeInOutExpo', function () {
                            resitems.remove();
                        });
                    }
                }, showMessagesTime);
            });
    });

}

// Wedding Request open/close
function wedding_request() {

    var btn = $('button.send-request'),
        div = $('div.wedding-request');

    btn.on('click', function () {
        div.toggleClass('opened');
    });

}

// Light Gallery init
// function lightGallery_init() {
//
//     var gallery = $('.lg-gallery');
//
//     if (gallery.length > 0) {
//         gallery.lightGallery({
//             speed: 700,
//             lang: {allPhotos: 'Összes kép'},
//             hideControlOnEnd: true,
//             mobileSrc: true
//         });
//     }
//
// }

// bxSlider init
function bxslider_init() {

    var members = $('ul.members-slider'),
        related = $('ul.related-posts-slider');

    if (members.length > 0) {
        members.bxSlider({
            useCSS: false,
            easing: 'easeInOutQuart',
            speed: 1000,
            minSlides: 1,
            maxSlides: 3,
            slideWidth: 374,
            slideMargin: 9,
            pager: false,
            auto: true,
            pause: 7000,
            autoHover: true,
            autoDelay: 1000,
            nextText: '<span class="icon-right"></span>',
            prevText: '<span class="icon-left"></span>',
            onSliderLoad: function () {
                members.addClass('visible');
            }
        });
    }

    if (related.length > 0) {
        related.bxSlider({
            infiniteLoop: false,
            hideControlOnEnd: true,
            useCSS: false,
            easing: 'easeInOutQuart',
            speed: 1000,
            minSlides: 1,
            maxSlides: 3,
            slideWidth: 248,
            slideMargin: 8,
            pager: false,
            nextText: '<span class="icon-right"></span>',
            prevText: '<span class="icon-left"></span>',
            onSliderLoad: function () {
                related.addClass('visible');
            }
        });
    }

}

// gallery init
function gallery_init() {

    var gal = $('.jq-gallery');

    if (gal.length > 0) {
        gal.gallery();
    }

}

// Simple console log wrapper
// Set var debug = false; if everything done.
function log(str) {
    var debug = false;
    if (debug) {
        console.log(str);
    }
}