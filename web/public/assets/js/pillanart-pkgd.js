/*! Pillana(r)t Scripts v1.5.0 by Gyorgy Papp | Mar 24 '17 at 11:11 */
/*
 * jQuery Easing v1.4.0 - http://gsgd.co.uk/sandbox/jquery/easing/
 * Open source under the BSD License.
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * https://raw.github.com/gdsmith/jquery-easing/master/LICENSE
 */

(function (factory) {
    if (typeof define === "function" && define.amd) {
        define(['jquery'], function ($) {
            return factory($);
        });
    } else if (typeof module === "object" && typeof module.exports === "object") {
        exports = factory(require('jquery'));
    } else {
        factory(jQuery);
    }
})(function($){

// Preserve the original jQuery "swing" easing as "jswing"
    $.easing['jswing'] = $.easing['swing'];

    var pow = Math.pow,
        sqrt = Math.sqrt,
        sin = Math.sin,
        cos = Math.cos,
        PI = Math.PI,
        c1 = 1.70158,
        c2 = c1 * 1.525,
        c3 = c1 + 1,
        c4 = ( 2 * PI ) / 3,
        c5 = ( 2 * PI ) / 4.5;

// x is the fraction of animation progress, in the range 0..1
    function bounceOut(x) {
        var n1 = 7.5625,
            d1 = 2.75;
        if ( x < 1/d1 ) {
            return n1*x*x;
        } else if ( x < 2/d1 ) {
            return n1*(x-=(1.5/d1))*x + .75;
        } else if ( x < 2.5/d1 ) {
            return n1*(x-=(2.25/d1))*x + .9375;
        } else {
            return n1*(x-=(2.625/d1))*x + .984375;
        }
    }

    $.extend( $.easing,
        {
            def: 'easeOutQuad',
            swing: function (x) {
                return $.easing[$.easing.def](x);
            },
            easeInQuad: function (x) {
                return x * x;
            },
            easeOutQuad: function (x) {
                return 1 - ( 1 - x ) * ( 1 - x );
            },
            easeInOutQuad: function (x) {
                return x < 0.5 ?
                2 * x * x :
                1 - pow( -2 * x + 2, 2 ) / 2;
            },
            easeInCubic: function (x) {
                return x * x * x;
            },
            easeOutCubic: function (x) {
                return 1 - pow( 1 - x, 3 );
            },
            easeInOutCubic: function (x) {
                return x < 0.5 ?
                4 * x * x * x :
                1 - pow( -2 * x + 2, 3 ) / 2;
            },
            easeInQuart: function (x) {
                return x * x * x * x;
            },
            easeOutQuart: function (x) {
                return 1 - pow( 1 - x, 4 );
            },
            easeInOutQuart: function (x) {
                return x < 0.5 ?
                8 * x * x * x * x :
                1 - pow( -2 * x + 2, 4 ) / 2;
            },
            easeInQuint: function (x) {
                return x * x * x * x * x;
            },
            easeOutQuint: function (x) {
                return 1 - pow( 1 - x, 5 );
            },
            easeInOutQuint: function (x) {
                return x < 0.5 ?
                16 * x * x * x * x * x :
                1 - pow( -2 * x + 2, 5 ) / 2;
            },
            easeInSine: function (x) {
                return 1 - cos( x * PI/2 );
            },
            easeOutSine: function (x) {
                return sin( x * PI/2 );
            },
            easeInOutSine: function (x) {
                return -( cos( PI * x ) - 1 ) / 2;
            },
            easeInExpo: function (x) {
                return x === 0 ? 0 : pow( 2, 10 * x - 10 );
            },
            easeOutExpo: function (x) {
                return x === 1 ? 1 : 1 - pow( 2, -10 * x );
            },
            easeInOutExpo: function (x) {
                return x === 0 ? 0 : x === 1 ? 1 : x < 0.5 ?
                pow( 2, 20 * x - 10 ) / 2 :
                ( 2 - pow( 2, -20 * x + 10 ) ) / 2;
            },
            easeInCirc: function (x) {
                return 1 - sqrt( 1 - pow( x, 2 ) );
            },
            easeOutCirc: function (x) {
                return sqrt( 1 - pow( x - 1, 2 ) );
            },
            easeInOutCirc: function (x) {
                return x < 0.5 ?
                ( 1 - sqrt( 1 - pow( 2 * x, 2 ) ) ) / 2 :
                ( sqrt( 1 - pow( -2 * x + 2, 2 ) ) + 1 ) / 2;
            },
            easeInElastic: function (x) {
                return x === 0 ? 0 : x === 1 ? 1 :
                -pow( 2, 10 * x - 10 ) * sin( ( x * 10 - 10.75 ) * c4 );
            },
            easeOutElastic: function (x) {
                return x === 0 ? 0 : x === 1 ? 1 :
                pow( 2, -10 * x ) * sin( ( x * 10 - 0.75 ) * c4 ) + 1;
            },
            easeInOutElastic: function (x) {
                return x === 0 ? 0 : x === 1 ? 1 : x < 0.5 ?
                -( pow( 2, 20 * x - 10 ) * sin( ( 20 * x - 11.125 ) * c5 )) / 2 :
                pow( 2, -20 * x + 10 ) * sin( ( 20 * x - 11.125 ) * c5 ) / 2 + 1;
            },
            easeInBack: function (x) {
                return c3 * x * x * x - c1 * x * x;
            },
            easeOutBack: function (x) {
                return 1 + c3 * pow( x - 1, 3 ) + c1 * pow( x - 1, 2 );
            },
            easeInOutBack: function (x) {
                return x < 0.5 ?
                ( pow( 2 * x, 2 ) * ( ( c2 + 1 ) * 2 * x - c2 ) ) / 2 :
                ( pow( 2 * x - 2, 2 ) *( ( c2 + 1 ) * ( x * 2 - 2 ) + c2 ) + 2 ) / 2;
            },
            easeInBounce: function (x) {
                return 1 - bounceOut( 1 - x );
            },
            easeOutBounce: bounceOut,
            easeInOutBounce: function (x) {
                return x < 0.5 ?
                ( 1 - bounceOut( 1 - 2 * x ) ) / 2 :
                ( 1 + bounceOut( 2 * x - 1 ) ) / 2;
            }
        });

});

/*
  --------------------------------------------------------------
  jQuery Mobile Nav v1.1

  Turn wp_nav_menus to mobile friendly navigation.
  Only flat (single level) menu supperted yet.

  @author   Gyorgy Papp <gyorgy.pap@gmail.com>
  @lastmod  Aug, 2014
  --------------------------------------------------------------
*/

;(function ( $, window, document, undefined ) {

  var pluginName = 'mobileNav',
		defaults = {
			navToggler : '.mobile-nav-show', // Menu toggler element

			// Translation
			lang:	{
				code : 'en',  // Translate strings with changing lang.code
				en: {
					back  : 'Back',
					close : 'Close Menu'
				},
				hu: {
					back  : 'Vissza',
					close : 'Menü bezárása'
				}
			},

			// Icons
			icon: {
				item   : 'icon-right-sm',  // Normal menu item
				subnav : 'icon-down-sm',   // Menu items that has children
				back   : 'icon-up-sm',     // Menu-level up
				close  : 'icon-close'      // Close menu
			},

			// Classnames
			classNames: {
				opened   : 'nav-opened',
				subNav   : 'nav-open-sub',
				closeNav : 'nav-close',
				backNav  : 'nav-back'
			}
    };

  // The actual plugin constructor
  function Plugin( el, options ) {
    this.el = el;
    this.settings = $.extend( true, {}, defaults, options );
    // this._defaults = defaults;
    this._name = pluginName;

    this.init();
  }

  $.extend(Plugin.prototype, {

		// Add DOM elements
		init: function () {
			var _i = this.settings.icon,
				_cn = this.settings.classNames,
				_lc = this.settings.lang.code,
				_str = this.settings.lang[ _lc ],
				el = $( this.el ),
			  items = el.find( 'a' ),
				subCtrl = el.find( 'span' );

			// <i> html tags from icon names
			$.each( _i, function( name, val ) {
					if ( val ) {
						_i[ name ] = '<i class="' + val + '"></i>';
					}
				});

			// Add close menu
			el.append( '<li><span class="' + _cn.closeNav + '">' +
									_i.close + _str.close + '</span></li>' );

			// Add icon to normal menu items
			if ( _i.item ) {
				items.prepend( _i.item );
			}

			// Add class and icon to subnav controls (actually: to empty spans)
			if ( subCtrl.length > 0 ) {
				subCtrl
					.prepend( _i.subnav )
					.addClass( _cn.subNav );

				// Add back items
				el.find( 'li ul' )
					.append( '<li><span class="' + _cn.backNav + '">' +
									 _i.back + _str.back + '</span></li>' );
			}

			this.attachEvents();
		},

		// Add DOM events
		attachEvents: function () {
		  var el = $( this.el ),
				self = this,
				toggler = $( this.settings.navToggler ),
				_cn = this.settings.classNames,
				closeCtrl = el.find( 'span.' + _cn.closeNav ),
				subCtrl = el.find( 'span.' + _cn.subNav );

			toggler.on( 'click', function () {
					self.toggleNav();
				});
			closeCtrl.on( 'click', function () {
					self.closeNav();
				});
		},

		// Call openNav or closeNav depending on 'opened' class
		toggleNav: function () {
			var el = $( this.el ),
				_cn = this.settings.classNames;

			if ( !el.hasClass( _cn.opened ) ) {
				this.openNav();
			} else {
				this.closeNav();
			}
		},

		// Add 'opened' class
		openNav: function () {
			var el = $( this.el ),
				_cn = this.settings.classNames;
			el.addClass( _cn.opened );
		},

		// Remove 'opened' class
		closeNav: function () {
			var el = $( this.el ),
				_cn = this.settings.classNames;
			el.removeClass( _cn.opened );
		},

	});

  // Lightweight plugin wrapper around the constructor
  $.fn[pluginName] = function ( options ) {
    return this.each( function () {
      if ( !$.data( this, 'plugin_' + pluginName ) ) {
        $.data( this, 'plugin_' + pluginName, new Plugin( this, options ) );
      }
    });
  }

})( jQuery, window, document );
/*
  --------------------------------------------------------------
  jQuery Gallery v1.0

  A forked version of lightGallery. Simplified DOM structure,
  clean css, readable javascript code...

  @author   Gyorgy Papp <gyorgy.pap@gmail.com>
  @lastmod  Aug, 2014
  --------------------------------------------------------------
*/

;(function ( $, window, document, undefined ) {

  var pluginName = 'gallery',
		defaults = {
			loop:     false,  // First image is next of last?
			pinit:    true    // Display Pin it button
    },
		galleryOn = false,
		thumbsOpened = false,
		usingThumb = false,
		mobileSrcMaxWidth = 650,
		isTouch = document.createTouch !== undefined || ( 'ontouchstart' in window ) || ( 'onmsgesturechange' in window ) || navigator.msMaxTouchPoints,
		html = 	'<div class="jg-container"><div class="jg">' +
							'<div class="jg-slides"></div>' +
							'<i class="jg-close icon-close"></i>' +
							'<div class="jg-ctrls">' +
								'<i class="jg-prev icon-left"></i>' +
								'<i class="jg-thumbs-open icon-thumbs"></i>' +
								'<i class="jg-next icon-right"></i>' +
							'</div>' +
							'<div class="jg-thumbs">' +
								'<i class="jg-thumbs-close icon-down"></i>' +
								'<div class="jg-thumbs-inner"></div>' +
							'</div>' +
						'</div></div>',
		pinIt = '<a class="pinit-btn" title="Megosztás Pinteresten" rel="external"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="355px" height="161px" viewBox="134.5 0 355 161" enable-background="new 134.5 0 355 161" xml:space="preserve"><path d="M199.47 0c-42.778 0-64.347 30.67-64.347 56.246c0 15.5 5.9 29.3 18.4 34.4 c2.062 0.8 3.9 0 4.507-2.254c0.416-1.58 1.4-5.565 1.839-7.226c0.603-2.258 0.369-3.05-1.295-5.018 c-3.626-4.277-5.943-9.814-5.943-17.657c0-22.754 17.024-43.124 44.33-43.124c24.179 0 37.5 14.8 37.5 34.5 c0 25.961-11.489 47.872-28.545 47.872c-9.419 0-16.47-7.79-14.21-17.344c2.706-11.406 7.948-23.716 7.948-31.949 c0-7.37-3.956-13.517-12.143-13.517c-9.629 0-17.364 9.961-17.364 23.305c0 8.5 2.9 14.2 2.9 14.2 s-9.854 41.751-11.581 49.063c-3.44 14.562-0.517 32.413-0.27 34.216c0.145 1.1 1.5 1.3 2.1 0.5 c0.888-1.159 12.356-15.317 16.255-29.465c1.103-4.006 6.333-24.748 6.333-24.748c3.128 6 12.3 11.2 22 11.2 c28.944 0 48.581-26.387 48.581-61.707C256.472 24.9 233.9 0 199.5 0z" class="pinit"/><path d="M373.623 119.66c-1.503 0.764-5.688 3.904-9.6 3.904c-4.723 0-6.72-2.383-6.72-7.494 c0-8.049 7.923-28.198 7.923-37.341c0-12.213-6.551-19.289-19.974-19.289c-8.461 0-17.235 5.463-20.952 10.3 c0 0 1.127-3.925 1.514-5.434c0.412-1.594-0.443-3.16-2.537-3.16c-3 0-10.274 0-13.123 0c-2.793 0-3.565 1.493-3.976 3.1 c-0.164 0.659-4.892 19.23-9.432 37.056c-3.106 12.207-10.651 22.407-18.717 22.407c-4.148 0-5.995-2.603-5.995-7.024 c0-3.82 2.468-13.246 5.413-24.945c3.583-14.244 6.744-25.972 7.082-27.342c0.441-1.756-0.344-3.286-2.607-3.286 c-2.837 0-10.204 0-13.204 0c-2.391 0-3.246 1.255-3.75 2.999c0 0-3.715 14.067-7.651 29.7 c-2.858 11.365-6.014 22.941-6.014 28.38c0 9.7 4.4 17 16.3 17.022c9.247 0 16.566-4.689 22.154-10.664 c-0.822 3.234-1.345 5.281-1.399 5.49c-0.482 1.8 0.1 3.5 2.3 3.483c3 0 10.6 0 13.5 0c2.352 0 3.256-0.946 3.747-2.997 c0.474-1.957 10.565-41.648 10.565-41.648c2.667-10.662 9.263-17.722 18.545-17.722c4.401 0 8.2 2.9 7.8 8.6 c-0.49 6.23-8.004 28.611-8.004 38.434c0 7.4 2.8 17.1 16.8 17.054c7.467 0 13.387-2.809 18.059-6.857L373.623 119.66z" class="pinit"/><path d="M277.382 55.254c6.723 0 13.173-5.597 14.295-12.502c1.327-6.721-3.231-12.318-9.949-12.318 c-6.72 0-13.171 5.597-14.309 12.318C266.108 49.7 270.5 55.3 277.4 55.254z" class="pinit"/><path d="M482.623 110.439c-3.283 6.521-10.648 12.172-16.309 12.172c-4.146 0-6.459-2.604-6.459-7.025 c0-3.818 2.689-13.154 5.635-24.854c0.986-3.914 2.484-9.885 4.145-16.501c7.501 0 13.5 0 13.8 0 c2.057 0 3.263-0.837 3.729-2.76c0.662-2.769 1.874-7.6 2.218-8.921c0.446-1.731-0.556-3.043-2.481-3.043 c-0.455 0-13.582 0-13.582 0s6.253-24.95 6.401-25.591c0.603-2.537-1.52-4.001-3.741-3.534c0 0-10.498 2.055-12.445 2.5 c-1.957 0.395-3.476 1.471-4.183 4.292c-0.09 0.361-5.561 22.372-5.561 22.372s-10.656 0-10.906 0 c-2.055 0-3.264 0.839-3.725 2.758c-0.664 2.765-1.882 7.602-2.218 8.923c-0.451 1.7 0.6 3 2.5 3 c0.393 0 4.9 0 10.7 0c-0.077 0.306-3.863 14.712-6.926 27.613c-0.715 3.073-2.268 8.534-4.609 13.3 c-3.749 5.164-8.939 8.524-13.564 8.524c-4.147 0-5.994-2.603-5.994-7.024c0-3.82 2.468-13.246 5.412-24.945 c3.584-14.244 6.744-25.972 7.082-27.342c0.441-1.756-0.344-3.286-2.606-3.286c-2.837 0-10.204 0-13.204 0 c-2.391 0-3.246 1.255-3.75 2.999c0 0-3.715 14.067-7.65 29.727c-2.858 11.365-6.014 22.941-6.014 28.4 c0 9.7 4.4 17 16.3 17.022c2.974 0 5.745-0.49 8.326-1.347c7.338-1.164 12.98-5.17 17.266-10.122 c1.869 6.3 6.8 10.5 15.9 10.476c11.113 0 18.969-4.855 24.479-11.002L482.623 110.439z" class="pinit"/><path d="M424.382 55.254c6.724 0 13.173-5.597 14.296-12.502c1.326-6.721-3.231-12.318-9.949-12.318 c-6.721 0-13.172 5.597-14.31 12.318C413.107 49.7 417.5 55.3 424.4 55.254z" class="pinit"/></svg></a>',
		children, index, prevIndex,
		galleryCont, gallery,
		slidesCont, slides,
		thumbsCont, thumbsInner, thumbs,
		pinitBtn, windowWidth;

  // The actual plugin constructor
  function Plugin( el, options ) {
    this.el = el;
    this.settings = $.extend( true, {}, defaults, options );
    this.defaults = defaults;
    this.name = pluginName;
    this.init();
  }

  $.extend(Plugin.prototype, {

		init: function () {
			var _this = this,
				_el = $( this.el );

			children = _el.children();

			children.on( 'click', function ( e ) {
				e.preventDefault();
				e.stopPropagation();
				index = children.index( this );
				prevIndex = index;
				_this.openGallery();
				_this.slide( index );
			});
		},

		openGallery: function () {
			$( 'body' ).append( html ).addClass( 'jg-on' );

			galleryCont = $( 'div.jg-container' );
			gallery = galleryCont.find( 'div.jg' );
			var _this = this,
				jgClose = gallery.find( 'i.jg-close' ),
				jgPrev = gallery.find( 'i.jg-prev' ),
				jgNext = gallery.find( 'i.jg-next' ),
				slideList = '';

      children.each( function () {
        slideList += '<div class="jg-slide"></div>';
      });

			slidesCont = gallery.find( 'div.jg-slides' );
      slidesCont.append( slideList );
      slides = slidesCont.find( 'div.jg-slide' );

			if ( children.length < 2 ) {
				jgPrev.addClass( 'disabled' );
				jgNext.addClass( 'disabled' );
			}

			jgPrev.bind( 'click touchend', function () {
				_this.prevSlide();
			});

			jgNext.bind( 'click touchend', function () {
				_this.nextSlide();
			});

			jgClose.bind( 'click touchend', function () {
				_this.closeGallery();
			});

			if ( this.settings.pinit ) {
				gallery.append( pinIt );
				pinitBtn = gallery.find( 'a.pinit-btn' );
				pinitBtn.on( 'click', function( e ) {
					e.preventDefault();
					window.open( this.href );
				});
			}

      this.getWidth();
			this.buildThumbs();
			this.keyPress();
			this.mouseTouch();
			this.fingerTouch();

			setTimeout( function () {
				gallery.addClass( 'fadein' );
			}, 50 );
		},

		getWidth: function () {
			var ev = 'resize.' + this.name,
				resizeWindow = function () {
					windowWidth = $( window ).width();
				};

			$( window ).bind( ev, resizeWindow() );
		},

		buildThumbs: function () {
			var thumbsOpen = gallery.find( 'i.jg-thumbs-open' );
			if ( children.length < 2 ) {
				thumbsOpen.addClass( 'disabled' );
				return;
			}

			thumbsCont = gallery.find( 'div.jg-thumbs' );
			thumbsInner = thumbsCont.find( 'div.jg-thumbs-inner' );
			var _this = this,
				thumbsClose = thumbsCont.find( 'i.jg-thumbs-close' ),
				thumbSrc,
				thumbList = '';

			children.each( function () {
				thumbSrc = $( this ).find( 'img' ).attr( 'src' );
				thumbList += '<div class="thumb"><img src="' + thumbSrc + '"></div>';
			});
			thumbsInner.append( thumbList );

			thumbs = thumbsInner.find( 'div.thumb' );
			thumbs.bind( 'click touchend', function () {
				usingThumb = true;
				index = $( this ).index();
				thumbs.removeClass( 'current' );
				$( this ).addClass( 'current' );
				_this.slide( index );
			});

			thumbsOpen.bind( 'click touchend', function () {
				_this.openThumbs();
			});
			thumbsClose.bind( 'click touchend', function() {
				_this.closeThumbs();
			});
		},

		keyPress: function() {
			var _this = this,
				ev = 'keyup.' + _this.name;

			$( window ).bind( ev, function( e ) {
				e.preventDefault();
				e.stopPropagation();
				switch ( e.keyCode ) {
					case 37 :
						_this.prevSlide();
						break;
					case 39 :
						_this.nextSlide();
						break;
					case 38 :
						if ( !thumbsOpened ) {
							_this.openThumbs();
						}
						break;
					case 40 :
						if (thumbsOpened) {
							_this.closeThumbs();
						}
						break;
					case 27 :
						if ( thumbsOpened ) {
							_this.closeThumbs();
						} else {
							_this.closeGallery();
						}
						break;
				}
			});
		},

		mouseTouch: function () {
			var _this = this,
				xStart,
				xEnd;

			gallery.bind( 'mousedown', function( e ) {
				e.stopPropagation();
				e.preventDefault();
				xStart = e.pageX;
			});

			gallery.bind( 'mouseup', function( e ) {
				e.stopPropagation();
				e.preventDefault();
				xEnd = e.pageX;
				if ( xEnd - xStart > 20 ) {
					_this.prevSlide();
				} else if ( xStart - xEnd > 20 ) {
					_this.nextSlide();
				}
			});
		},

		fingerTouch: function () {
			var _this = this;
			if ( isTouch ) {
				var startCoords = {},
					endCoords = {},
					evstart = 'touchstart.' + _this.name,
					evmove = 'touchmove.' + _this.name,
					evend = 'touchend.' + _this.name;

				$( 'body' ).on( evstart, function( e ) {
					var oe = e.originalEvent;
					endCoords = oe.targetTouches[0];
					startCoords.pageX = oe.targetTouches[0].pageX;
					startCoords.pageY = oe.targetTouches[0].pageY;
				});

				$( 'body' ).on( evmove, function( e ) {
					var oe = e.originalEvent;
					endCoords = oe.targetTouches[0];
					e.preventDefault();
				});

				$( 'body' ).on( evend, function( e ) {
					var distance = endCoords.pageX - startCoords.pageX,
						swipeThreshold = 50;

					if ( distance > swipeThreshold ) {
						_this.prevSlide();
					} else if ( distance < -swipeThreshold ) {
						_this.nextSlide();
					}
				});
			}
		},

		openThumbs: function () {
			slides.eq( index ).prevAll().removeClass( 'next-slide' ).addClass( 'prev-slide' );
			slides.eq( index ).nextAll().removeClass( 'prev-slide' ).addClass( 'next-slide' );
			thumbsCont.addClass( 'opened' );
			thumbsOpened = true;
		},

		closeThumbs: function () {
			thumbsCont.removeClass( 'opened' );
			thumbsOpened = false;
		},

		nextSlide: function () {
			index = slides.index( slides.eq( prevIndex ) );
			if ( index + 1 < children.length ) {
				index++;
				this.slide( index );
			} else {
				if ( this.settings.loop ) {
					index = 0;
					this.slide( index );
				}
			}
		},

		prevSlide: function() {
			index = slides.index( slides.eq( prevIndex ) );
			if ( index > 0 ) {
				index--;
				this.slide( index );
			} else {
				if ( this.settings.loop ) {
					index = children.length - 1;
					this.slide( index );
				}
			}
		},

		slide: function ( index ) {
			this.loadContent( index, false );

			if ( galleryOn && !slidesCont.hasClass( 'on' ) ) {
				slidesCont.addClass( 'on' );
			}

			var isiPad = navigator.userAgent.match(/iPad/i) != null;

			if ( !slidesCont.hasClass( 'slide' ) && !isiPad ) {
				slidesCont.addClass( 'slide' );
			} else if ( !slidesCont.hasClass( 'slide-ipad' ) && isiPad ) {
				slidesCont.addClass( 'slide-ipad' );
			}

			slides.eq( prevIndex ).removeClass( 'current' );
			slides.eq( index ).addClass( 'current' );

			if ( usingThumb ) {
				slides.eq( index ).prevAll().removeClass( 'next-slide' ).addClass( 'prev-slide' );
				slides.eq( index ).nextAll().removeClass( 'prev-slide' ).addClass( 'next-slide' );
			} else {
				$( 'div.prev-slide' ).removeClass( 'prev-slide' );
				$( 'div.next-slide' ).removeClass( 'next-slide' );
				slides.eq( index - 1 ).addClass( 'prev-slide');
				slides.eq( index + 1 ).addClass( 'next-slide');
			}

			var n = children.length,
				jgPrev = gallery.find( 'i.jg-prev' ),
				jgNext = gallery.find( 'i.jg-next' );

			if ( n > 1 ) {
				thumbs.removeClass( 'current' );
				thumbs.eq( index ).addClass( 'current' );
				n = parseInt(n) - 1;

				if ( index === 0 ) {
					jgPrev.addClass( 'disabled' );
					jgNext.removeClass( 'disabled' );
				} else if ( index === n ) {
					jgPrev.removeClass( 'disabled' );
					jgNext.addClass( 'disabled' );
				} else {
					jgPrev.removeClass( 'disabled' );
					jgNext.removeClass( 'disabled' );
				}
			}

			prevIndex = index;
			galleryOn = true;
			usingThumb = false;
		},

		loadContent: function( index, recoursive ) {
			var _this = this,
				preload = 1,
				n = children.length,
				l = n - index,
				i, j,	src;

			if ( preload > n ) {
				preload = n;
			}

			if ( windowWidth <= mobileSrcMaxWidth ) {
				src = children.eq( index ).data( 'responsive-src' );
				if ( src === null ) {
					src = children.eq( index ).data( 'src' );
				}
			} else {
				src = children.eq( index ).data( 'src' );
			}

			if ( !slides.eq( index ).hasClass( 'loaded' ) ) {
				slides.eq( index )
					.prepend( '<img src="' + src + '">' )
					.addClass( 'loaded' );
			}

			if ( !recoursive ) {
				var complete = false;
				if ( slides.eq( index ).find( 'img' )[0].complete ) {
					complete = true;
				}
				if ( !complete ) {
					slides.eq( index ).find( 'img' ).on( 'load error', function () {
						var newIndex = index;
						for ( var k = 0; k <= preload; k++ ) {
							if ( k >= n - index ) {
								break;
							}
							_this.loadContent( newIndex + k, true );
						}
						for ( var h = 0; h <= preload; h++ ) {
							if ( newIndex - h < 0 ) {
								break;
							}
							_this.loadContent( newIndex - h, true );
						}
					});
				} else {
					var newIndex = index;
					for ( var k = 0; k <= preload; k++ ) {
						if ( k >= n - index ) {
							break;
						}
						_this.loadContent( newIndex + k, true );
					}
					for ( var h = 0; h <= preload; h++ ) {
						if ( newIndex - h < 0 ) {
							break;
						}
						_this.loadContent( newIndex - h, true );
					}
				}

				if ( this.settings.pinit ) {
					var pinitLink = children.eq( index ).data( 'pinit-link' );
					pinitBtn.attr( 'href', pinitLink );
				}
			}

		},

		closeGallery: function () {
			var eBody = 'touchstart.' + this.name + ' touchmove.' + this.name + ' touchend.' + this.name,
				eWindow = 'resize.' + this.name + ' keyup.' + this.name;

			galleryOn = false;
			gallery.off( 'mousedown mouseup' );
			$( 'body' ).off( eBody );
			$( window ).off( eWindow );
			gallery.addClass( 'fadeout' );
			setTimeout( function () {
				galleryCont.remove();
				$( 'body' ).removeClass( 'jg-on' );
			}, 500 );
		}

	});

  // Lightweight plugin wrapper around the constructor
  $.fn[pluginName] = function ( options ) {
    return this.each( function () {
      if ( !$.data( this, 'plugin_' + pluginName ) ) {
        $.data( this, 'plugin_' + pluginName, new Plugin( this, options ) );
      }
    });
  }

})( jQuery, window, document );
/**
 * bxSlider v4.2.5
 * Copyright 2013-2015 Steven Wanderski
 * Written while drinking Belgian ales and listening to jazz
 * Licensed under MIT (http://opensource.org/licenses/MIT)
 */

;(function($) {

    var defaults = {

        // GENERAL
        mode: 'horizontal',
        slideSelector: '',
        infiniteLoop: true,
        hideControlOnEnd: false,
        speed: 500,
        easing: null,
        slideMargin: 0,
        startSlide: 0,
        randomStart: false,
        captions: false,
        ticker: false,
        tickerHover: false,
        adaptiveHeight: false,
        adaptiveHeightSpeed: 500,
        video: false,
        useCSS: true,
        preloadImages: 'visible',
        responsive: true,
        slideZIndex: 50,
        wrapperClass: 'bx-wrapper',

        // TOUCH
        touchEnabled: true,
        swipeThreshold: 50,
        oneToOneTouch: true,
        preventDefaultSwipeX: true,
        preventDefaultSwipeY: false,

        // ACCESSIBILITY
        ariaLive: true,
        ariaHidden: true,

        // KEYBOARD
        keyboardEnabled: false,

        // PAGER
        pager: true,
        pagerType: 'full',
        pagerShortSeparator: ' / ',
        pagerSelector: null,
        buildPager: null,
        pagerCustom: null,

        // CONTROLS
        controls: true,
        nextText: 'Next',
        prevText: 'Prev',
        nextSelector: null,
        prevSelector: null,
        autoControls: false,
        startText: 'Start',
        stopText: 'Stop',
        autoControlsCombine: false,
        autoControlsSelector: null,

        // AUTO
        auto: false,
        pause: 4000,
        autoStart: true,
        autoDirection: 'next',
        stopAutoOnClick: false,
        autoHover: false,
        autoDelay: 0,
        autoSlideForOnePage: false,

        // CAROUSEL
        minSlides: 1,
        maxSlides: 1,
        moveSlides: 0,
        slideWidth: 0,
        shrinkItems: false,

        // CALLBACKS
        onSliderLoad: function() { return true; },
        onSlideBefore: function() { return true; },
        onSlideAfter: function() { return true; },
        onSlideNext: function() { return true; },
        onSlidePrev: function() { return true; },
        onSliderResize: function() { return true; }
    };

    $.fn.bxSlider = function(options) {

        if (this.length === 0) {
            return this;
        }

        // support multiple elements
        if (this.length > 1) {
            this.each(function() {
                $(this).bxSlider(options);
            });
            return this;
        }

        // create a namespace to be used throughout the plugin
        var slider = {},
            // set a reference to our slider element
            el = this,
            // get the original window dimens (thanks a lot IE)
            windowWidth = $(window).width(),
            windowHeight = $(window).height();

        // Return if slider is already initialized
        if ($(el).data('bxSlider')) { return; }

        /**
         * ===================================================================================
         * = PRIVATE FUNCTIONS
         * ===================================================================================
         */

        /**
         * Initializes namespace settings to be used throughout plugin
         */
        var init = function() {
            // Return if slider is already initialized
            if ($(el).data('bxSlider')) { return; }
            // merge user-supplied options with the defaults
            slider.settings = $.extend({}, defaults, options);
            // parse slideWidth setting
            slider.settings.slideWidth = parseInt(slider.settings.slideWidth);
            // store the original children
            slider.children = el.children(slider.settings.slideSelector);
            // check if actual number of slides is less than minSlides / maxSlides
            if (slider.children.length < slider.settings.minSlides) { slider.settings.minSlides = slider.children.length; }
            if (slider.children.length < slider.settings.maxSlides) { slider.settings.maxSlides = slider.children.length; }
            // if random start, set the startSlide setting to random number
            if (slider.settings.randomStart) { slider.settings.startSlide = Math.floor(Math.random() * slider.children.length); }
            // store active slide information
            slider.active = { index: slider.settings.startSlide };
            // store if the slider is in carousel mode (displaying / moving multiple slides)
            slider.carousel = slider.settings.minSlides > 1 || slider.settings.maxSlides > 1 ? true : false;
            // if carousel, force preloadImages = 'all'
            if (slider.carousel) { slider.settings.preloadImages = 'all'; }
            // calculate the min / max width thresholds based on min / max number of slides
            // used to setup and update carousel slides dimensions
            slider.minThreshold = (slider.settings.minSlides * slider.settings.slideWidth) + ((slider.settings.minSlides - 1) * slider.settings.slideMargin);
            slider.maxThreshold = (slider.settings.maxSlides * slider.settings.slideWidth) + ((slider.settings.maxSlides - 1) * slider.settings.slideMargin);
            // store the current state of the slider (if currently animating, working is true)
            slider.working = false;
            // initialize the controls object
            slider.controls = {};
            // initialize an auto interval
            slider.interval = null;
            // determine which property to use for transitions
            slider.animProp = slider.settings.mode === 'vertical' ? 'top' : 'left';
            // determine if hardware acceleration can be used
            slider.usingCSS = slider.settings.useCSS && slider.settings.mode !== 'fade' && (function() {
                    // create our test div element
                    var div = document.createElement('div'),
                        // css transition properties
                        props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
                    // test for each property
                    for (var i = 0; i < props.length; i++) {
                        if (div.style[props[i]] !== undefined) {
                            slider.cssPrefix = props[i].replace('Perspective', '').toLowerCase();
                            slider.animProp = '-' + slider.cssPrefix + '-transform';
                            return true;
                        }
                    }
                    return false;
                }());
            // if vertical mode always make maxSlides and minSlides equal
            if (slider.settings.mode === 'vertical') { slider.settings.maxSlides = slider.settings.minSlides; }
            // save original style data
            el.data('origStyle', el.attr('style'));
            el.children(slider.settings.slideSelector).each(function() {
                $(this).data('origStyle', $(this).attr('style'));
            });

            // perform all DOM / CSS modifications
            setup();
        };

        /**
         * Performs all DOM and CSS modifications
         */
        var setup = function() {
            var preloadSelector = slider.children.eq(slider.settings.startSlide); // set the default preload selector (visible)

            // wrap el in a wrapper
            el.wrap('<div class="' + slider.settings.wrapperClass + '"><div class="bx-viewport"></div></div>');
            // store a namespace reference to .bx-viewport
            slider.viewport = el.parent();

            // add aria-live if the setting is enabled and ticker mode is disabled
            if (slider.settings.ariaLive && !slider.settings.ticker) {
                slider.viewport.attr('aria-live', 'polite');
            }
            // add a loading div to display while images are loading
            slider.loader = $('<div class="bx-loading" />');
            slider.viewport.prepend(slider.loader);
            // set el to a massive width, to hold any needed slides
            // also strip any margin and padding from el
            el.css({
                width: slider.settings.mode === 'horizontal' ? (slider.children.length * 1000 + 215) + '%' : 'auto',
                position: 'relative'
            });
            // if using CSS, add the easing property
            if (slider.usingCSS && slider.settings.easing) {
                el.css('-' + slider.cssPrefix + '-transition-timing-function', slider.settings.easing);
                // if not using CSS and no easing value was supplied, use the default JS animation easing (swing)
            } else if (!slider.settings.easing) {
                slider.settings.easing = 'swing';
            }
            // make modifications to the viewport (.bx-viewport)
            slider.viewport.css({
                width: '100%',
                overflow: 'hidden',
                position: 'relative'
            });
            slider.viewport.parent().css({
                maxWidth: getViewportMaxWidth()
            });
            // make modification to the wrapper (.bx-wrapper)
            if (!slider.settings.pager && !slider.settings.controls) {
                slider.viewport.parent().css({
                    margin: '0 auto 0px'
                });
            }
            // apply css to all slider children
            slider.children.css({
                float: slider.settings.mode === 'horizontal' ? 'left' : 'none',
                listStyle: 'none',
                position: 'relative'
            });
            // apply the calculated width after the float is applied to prevent scrollbar interference
            slider.children.css('width', getSlideWidth());
            // if slideMargin is supplied, add the css
            if (slider.settings.mode === 'horizontal' && slider.settings.slideMargin > 0) { slider.children.css('marginRight', slider.settings.slideMargin); }
            if (slider.settings.mode === 'vertical' && slider.settings.slideMargin > 0) { slider.children.css('marginBottom', slider.settings.slideMargin); }
            // if "fade" mode, add positioning and z-index CSS
            if (slider.settings.mode === 'fade') {
                slider.children.css({
                    position: 'absolute',
                    zIndex: 0,
                    display: 'none'
                });
                // prepare the z-index on the showing element
                slider.children.eq(slider.settings.startSlide).css({zIndex: slider.settings.slideZIndex, display: 'block'});
            }
            // create an element to contain all slider controls (pager, start / stop, etc)
            slider.controls.el = $('<div class="bx-controls" />');
            // if captions are requested, add them
            if (slider.settings.captions) { appendCaptions(); }
            // check if startSlide is last slide
            slider.active.last = slider.settings.startSlide === getPagerQty() - 1;
            // if video is true, set up the fitVids plugin
            if (slider.settings.video) { el.fitVids(); }
            if (slider.settings.preloadImages === 'all' || slider.settings.ticker) { preloadSelector = slider.children; }
            // only check for control addition if not in "ticker" mode
            if (!slider.settings.ticker) {
                // if controls are requested, add them
                if (slider.settings.controls) { appendControls(); }
                // if auto is true, and auto controls are requested, add them
                if (slider.settings.auto && slider.settings.autoControls) { appendControlsAuto(); }
                // if pager is requested, add it
                if (slider.settings.pager) { appendPager(); }
                // if any control option is requested, add the controls wrapper
                if (slider.settings.controls || slider.settings.autoControls || slider.settings.pager) { slider.viewport.after(slider.controls.el); }
                // if ticker mode, do not allow a pager
            } else {
                slider.settings.pager = false;
            }
            loadElements(preloadSelector, start);
        };

        var loadElements = function(selector, callback) {
            var total = selector.find('img:not([src=""]), iframe').length,
                count = 0;
            if (total === 0) {
                callback();
                return;
            }
            selector.find('img:not([src=""]), iframe').each(function() {
                $(this).one('load error', function() {
                    if (++count === total) { callback(); }
                }).each(function() {
                    if (this.complete) { $(this).trigger('load'); }
                });
            });
        };

        /**
         * Start the slider
         */
        var start = function() {
            // if infinite loop, prepare additional slides
            if (slider.settings.infiniteLoop && slider.settings.mode !== 'fade' && !slider.settings.ticker) {
                var slice    = slider.settings.mode === 'vertical' ? slider.settings.minSlides : slider.settings.maxSlides,
                    sliceAppend  = slider.children.slice(0, slice).clone(true).addClass('bx-clone'),
                    slicePrepend = slider.children.slice(-slice).clone(true).addClass('bx-clone');
                if (slider.settings.ariaHidden) {
                    sliceAppend.attr('aria-hidden', true);
                    slicePrepend.attr('aria-hidden', true);
                }
                el.append(sliceAppend).prepend(slicePrepend);
            }
            // remove the loading DOM element
            slider.loader.remove();
            // set the left / top position of "el"
            setSlidePosition();
            // if "vertical" mode, always use adaptiveHeight to prevent odd behavior
            if (slider.settings.mode === 'vertical') { slider.settings.adaptiveHeight = true; }
            // set the viewport height
            slider.viewport.height(getViewportHeight());
            // make sure everything is positioned just right (same as a window resize)
            el.redrawSlider();
            // onSliderLoad callback
            slider.settings.onSliderLoad.call(el, slider.active.index);
            // slider has been fully initialized
            slider.initialized = true;
            // bind the resize call to the window
            if (slider.settings.responsive) { $(window).on('resize', resizeWindow); }
            // if auto is true and has more than 1 page, start the show
            if (slider.settings.auto && slider.settings.autoStart && (getPagerQty() > 1 || slider.settings.autoSlideForOnePage)) { initAuto(); }
            // if ticker is true, start the ticker
            if (slider.settings.ticker) { initTicker(); }
            // if pager is requested, make the appropriate pager link active
            if (slider.settings.pager) { updatePagerActive(slider.settings.startSlide); }
            // check for any updates to the controls (like hideControlOnEnd updates)
            if (slider.settings.controls) { updateDirectionControls(); }
            // if touchEnabled is true, setup the touch events
            if (slider.settings.touchEnabled && !slider.settings.ticker) { initTouch(); }
            // if keyboardEnabled is true, setup the keyboard events
            if (slider.settings.keyboardEnabled && !slider.settings.ticker) {
                $(document).keydown(keyPress);
            }
        };

        /**
         * Returns the calculated height of the viewport, used to determine either adaptiveHeight or the maxHeight value
         */
        var getViewportHeight = function() {
            var height = 0;
            // first determine which children (slides) should be used in our height calculation
            var children = $();
            // if mode is not "vertical" and adaptiveHeight is false, include all children
            if (slider.settings.mode !== 'vertical' && !slider.settings.adaptiveHeight) {
                children = slider.children;
            } else {
                // if not carousel, return the single active child
                if (!slider.carousel) {
                    children = slider.children.eq(slider.active.index);
                    // if carousel, return a slice of children
                } else {
                    // get the individual slide index
                    var currentIndex = slider.settings.moveSlides === 1 ? slider.active.index : slider.active.index * getMoveBy();
                    // add the current slide to the children
                    children = slider.children.eq(currentIndex);
                    // cycle through the remaining "showing" slides
                    for (i = 1; i <= slider.settings.maxSlides - 1; i++) {
                        // if looped back to the start
                        if (currentIndex + i >= slider.children.length) {
                            children = children.add(slider.children.eq(i - 1));
                        } else {
                            children = children.add(slider.children.eq(currentIndex + i));
                        }
                    }
                }
            }
            // if "vertical" mode, calculate the sum of the heights of the children
            if (slider.settings.mode === 'vertical') {
                children.each(function(index) {
                    height += $(this).outerHeight();
                });
                // add user-supplied margins
                if (slider.settings.slideMargin > 0) {
                    height += slider.settings.slideMargin * (slider.settings.minSlides - 1);
                }
                // if not "vertical" mode, calculate the max height of the children
            } else {
                height = Math.max.apply(Math, children.map(function() {
                    return $(this).outerHeight(false);
                }).get());
            }

            if (slider.viewport.css('box-sizing') === 'border-box') {
                height += parseFloat(slider.viewport.css('padding-top')) + parseFloat(slider.viewport.css('padding-bottom')) +
                    parseFloat(slider.viewport.css('border-top-width')) + parseFloat(slider.viewport.css('border-bottom-width'));
            } else if (slider.viewport.css('box-sizing') === 'padding-box') {
                height += parseFloat(slider.viewport.css('padding-top')) + parseFloat(slider.viewport.css('padding-bottom'));
            }

            return height;
        };

        /**
         * Returns the calculated width to be used for the outer wrapper / viewport
         */
        var getViewportMaxWidth = function() {
            var width = '100%';
            if (slider.settings.slideWidth > 0) {
                if (slider.settings.mode === 'horizontal') {
                    width = (slider.settings.maxSlides * slider.settings.slideWidth) + ((slider.settings.maxSlides - 1) * slider.settings.slideMargin);
                } else {
                    width = slider.settings.slideWidth;
                }
            }
            return width;
        };

        /**
         * Returns the calculated width to be applied to each slide
         */
        var getSlideWidth = function() {
            var newElWidth = slider.settings.slideWidth, // start with any user-supplied slide width
                wrapWidth      = slider.viewport.width();    // get the current viewport width
            // if slide width was not supplied, or is larger than the viewport use the viewport width
            if (slider.settings.slideWidth === 0 ||
                (slider.settings.slideWidth > wrapWidth && !slider.carousel) ||
                slider.settings.mode === 'vertical') {
                newElWidth = wrapWidth;
                // if carousel, use the thresholds to determine the width
            } else if (slider.settings.maxSlides > 1 && slider.settings.mode === 'horizontal') {
                if (wrapWidth > slider.maxThreshold) {
                    return newElWidth;
                } else if (wrapWidth < slider.minThreshold) {
                    newElWidth = (wrapWidth - (slider.settings.slideMargin * (slider.settings.minSlides - 1))) / slider.settings.minSlides;
                } else if (slider.settings.shrinkItems) {
                    newElWidth = Math.floor((wrapWidth + slider.settings.slideMargin) / (Math.ceil((wrapWidth + slider.settings.slideMargin) / (newElWidth + slider.settings.slideMargin))) - slider.settings.slideMargin);
                }
            }
            return newElWidth;
        };

        /**
         * Returns the number of slides currently visible in the viewport (includes partially visible slides)
         */
        var getNumberSlidesShowing = function() {
            var slidesShowing = 1,
                childWidth = null;
            if (slider.settings.mode === 'horizontal' && slider.settings.slideWidth > 0) {
                // if viewport is smaller than minThreshold, return minSlides
                if (slider.viewport.width() < slider.minThreshold) {
                    slidesShowing = slider.settings.minSlides;
                    // if viewport is larger than maxThreshold, return maxSlides
                } else if (slider.viewport.width() > slider.maxThreshold) {
                    slidesShowing = slider.settings.maxSlides;
                    // if viewport is between min / max thresholds, divide viewport width by first child width
                } else {
                    childWidth = slider.children.first().width() + slider.settings.slideMargin;
                    slidesShowing = Math.floor((slider.viewport.width() +
                        slider.settings.slideMargin) / childWidth);
                }
                // if "vertical" mode, slides showing will always be minSlides
            } else if (slider.settings.mode === 'vertical') {
                slidesShowing = slider.settings.minSlides;
            }
            return slidesShowing;
        };

        /**
         * Returns the number of pages (one full viewport of slides is one "page")
         */
        var getPagerQty = function() {
            var pagerQty = 0,
                breakPoint = 0,
                counter = 0;
            // if moveSlides is specified by the user
            if (slider.settings.moveSlides > 0) {
                if (slider.settings.infiniteLoop) {
                    pagerQty = Math.ceil(slider.children.length / getMoveBy());
                } else {
                    // when breakpoint goes above children length, counter is the number of pages
                    while (breakPoint < slider.children.length) {
                        ++pagerQty;
                        breakPoint = counter + getNumberSlidesShowing();
                        counter += slider.settings.moveSlides <= getNumberSlidesShowing() ? slider.settings.moveSlides : getNumberSlidesShowing();
                    }
                }
                // if moveSlides is 0 (auto) divide children length by sides showing, then round up
            } else {
                pagerQty = Math.ceil(slider.children.length / getNumberSlidesShowing());
            }
            return pagerQty;
        };

        /**
         * Returns the number of individual slides by which to shift the slider
         */
        var getMoveBy = function() {
            // if moveSlides was set by the user and moveSlides is less than number of slides showing
            if (slider.settings.moveSlides > 0 && slider.settings.moveSlides <= getNumberSlidesShowing()) {
                return slider.settings.moveSlides;
            }
            // if moveSlides is 0 (auto)
            return getNumberSlidesShowing();
        };

        /**
         * Sets the slider's (el) left or top position
         */
        var setSlidePosition = function() {
            var position, lastChild, lastShowingIndex;
            // if last slide, not infinite loop, and number of children is larger than specified maxSlides
            if (slider.children.length > slider.settings.maxSlides && slider.active.last && !slider.settings.infiniteLoop) {
                if (slider.settings.mode === 'horizontal') {
                    // get the last child's position
                    lastChild = slider.children.last();
                    position = lastChild.position();
                    // set the left position
                    setPositionProperty(-(position.left - (slider.viewport.width() - lastChild.outerWidth())), 'reset', 0);
                } else if (slider.settings.mode === 'vertical') {
                    // get the last showing index's position
                    lastShowingIndex = slider.children.length - slider.settings.minSlides;
                    position = slider.children.eq(lastShowingIndex).position();
                    // set the top position
                    setPositionProperty(-position.top, 'reset', 0);
                }
                // if not last slide
            } else {
                // get the position of the first showing slide
                position = slider.children.eq(slider.active.index * getMoveBy()).position();
                // check for last slide
                if (slider.active.index === getPagerQty() - 1) { slider.active.last = true; }
                // set the respective position
                if (position !== undefined) {
                    if (slider.settings.mode === 'horizontal') { setPositionProperty(-position.left, 'reset', 0); }
                    else if (slider.settings.mode === 'vertical') { setPositionProperty(-position.top, 'reset', 0); }
                }
            }
        };

        /**
         * Sets the el's animating property position (which in turn will sometimes animate el).
         * If using CSS, sets the transform property. If not using CSS, sets the top / left property.
         *
         * @param value (int)
         *  - the animating property's value
         *
         * @param type (string) 'slide', 'reset', 'ticker'
         *  - the type of instance for which the function is being
         *
         * @param duration (int)
         *  - the amount of time (in ms) the transition should occupy
         *
         * @param params (array) optional
         *  - an optional parameter containing any variables that need to be passed in
         */
        var setPositionProperty = function(value, type, duration, params) {
            var animateObj, propValue;
            // use CSS transform
            if (slider.usingCSS) {
                // determine the translate3d value
                propValue = slider.settings.mode === 'vertical' ? 'translate3d(0, ' + value + 'px, 0)' : 'translate3d(' + value + 'px, 0, 0)';
                // add the CSS transition-duration
                el.css('-' + slider.cssPrefix + '-transition-duration', duration / 1000 + 's');
                if (type === 'slide') {
                    // set the property value
                    el.css(slider.animProp, propValue);
                    if (duration !== 0) {
                        // bind a callback method - executes when CSS transition completes
                        el.on('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(e) {
                            //make sure it's the correct one
                            if (!$(e.target).is(el)) { return; }
                            // unbind the callback
                            el.off('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd');
                            updateAfterSlideTransition();
                        });
                    } else { //duration = 0
                        updateAfterSlideTransition();
                    }
                } else if (type === 'reset') {
                    el.css(slider.animProp, propValue);
                } else if (type === 'ticker') {
                    // make the transition use 'linear'
                    el.css('-' + slider.cssPrefix + '-transition-timing-function', 'linear');
                    el.css(slider.animProp, propValue);
                    if (duration !== 0) {
                        el.on('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(e) {
                            //make sure it's the correct one
                            if (!$(e.target).is(el)) { return; }
                            // unbind the callback
                            el.off('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd');
                            // reset the position
                            setPositionProperty(params.resetValue, 'reset', 0);
                            // start the loop again
                            tickerLoop();
                        });
                    } else { //duration = 0
                        setPositionProperty(params.resetValue, 'reset', 0);
                        tickerLoop();
                    }
                }
                // use JS animate
            } else {
                animateObj = {};
                animateObj[slider.animProp] = value;
                if (type === 'slide') {
                    el.animate(animateObj, duration, slider.settings.easing, function() {
                        updateAfterSlideTransition();
                    });
                } else if (type === 'reset') {
                    el.css(slider.animProp, value);
                } else if (type === 'ticker') {
                    el.animate(animateObj, duration, 'linear', function() {
                        setPositionProperty(params.resetValue, 'reset', 0);
                        // run the recursive loop after animation
                        tickerLoop();
                    });
                }
            }
        };

        /**
         * Populates the pager with proper amount of pages
         */
        var populatePager = function() {
            var pagerHtml = '',
                linkContent = '',
                pagerQty = getPagerQty();
            // loop through each pager item
            for (var i = 0; i < pagerQty; i++) {
                linkContent = '';
                // if a buildPager function is supplied, use it to get pager link value, else use index + 1
                if (slider.settings.buildPager && $.isFunction(slider.settings.buildPager) || slider.settings.pagerCustom) {
                    linkContent = slider.settings.buildPager(i);
                    slider.pagerEl.addClass('bx-custom-pager');
                } else {
                    linkContent = i + 1;
                    slider.pagerEl.addClass('bx-default-pager');
                }
                // var linkContent = slider.settings.buildPager && $.isFunction(slider.settings.buildPager) ? slider.settings.buildPager(i) : i + 1;
                // add the markup to the string
                pagerHtml += '<div class="bx-pager-item"><a href="" data-slide-index="' + i + '" class="bx-pager-link">' + linkContent + '</a></div>';
            }
            // populate the pager element with pager links
            slider.pagerEl.html(pagerHtml);
        };

        /**
         * Appends the pager to the controls element
         */
        var appendPager = function() {
            if (!slider.settings.pagerCustom) {
                // create the pager DOM element
                slider.pagerEl = $('<div class="bx-pager" />');
                // if a pager selector was supplied, populate it with the pager
                if (slider.settings.pagerSelector) {
                    $(slider.settings.pagerSelector).html(slider.pagerEl);
                    // if no pager selector was supplied, add it after the wrapper
                } else {
                    slider.controls.el.addClass('bx-has-pager').append(slider.pagerEl);
                }
                // populate the pager
                populatePager();
            } else {
                slider.pagerEl = $(slider.settings.pagerCustom);
            }
            // assign the pager click binding
            slider.pagerEl.on('click touchend', 'a', clickPagerBind);
        };

        /**
         * Appends prev / next controls to the controls element
         */
        var appendControls = function() {
            slider.controls.next = $('<a class="bx-next" href="">' + slider.settings.nextText + '</a>');
            slider.controls.prev = $('<a class="bx-prev" href="">' + slider.settings.prevText + '</a>');
            // bind click actions to the controls
            slider.controls.next.on('click touchend', clickNextBind);
            slider.controls.prev.on('click touchend', clickPrevBind);
            // if nextSelector was supplied, populate it
            if (slider.settings.nextSelector) {
                $(slider.settings.nextSelector).append(slider.controls.next);
            }
            // if prevSelector was supplied, populate it
            if (slider.settings.prevSelector) {
                $(slider.settings.prevSelector).append(slider.controls.prev);
            }
            // if no custom selectors were supplied
            if (!slider.settings.nextSelector && !slider.settings.prevSelector) {
                // add the controls to the DOM
                slider.controls.directionEl = $('<div class="bx-controls-direction" />');
                // add the control elements to the directionEl
                slider.controls.directionEl.append(slider.controls.prev).append(slider.controls.next);
                // slider.viewport.append(slider.controls.directionEl);
                slider.controls.el.addClass('bx-has-controls-direction').append(slider.controls.directionEl);
            }
        };

        /**
         * Appends start / stop auto controls to the controls element
         */
        var appendControlsAuto = function() {
            slider.controls.start = $('<div class="bx-controls-auto-item"><a class="bx-start" href="">' + slider.settings.startText + '</a></div>');
            slider.controls.stop = $('<div class="bx-controls-auto-item"><a class="bx-stop" href="">' + slider.settings.stopText + '</a></div>');
            // add the controls to the DOM
            slider.controls.autoEl = $('<div class="bx-controls-auto" />');
            // bind click actions to the controls
            slider.controls.autoEl.on('click', '.bx-start', clickStartBind);
            slider.controls.autoEl.on('click', '.bx-stop', clickStopBind);
            // if autoControlsCombine, insert only the "start" control
            if (slider.settings.autoControlsCombine) {
                slider.controls.autoEl.append(slider.controls.start);
                // if autoControlsCombine is false, insert both controls
            } else {
                slider.controls.autoEl.append(slider.controls.start).append(slider.controls.stop);
            }
            // if auto controls selector was supplied, populate it with the controls
            if (slider.settings.autoControlsSelector) {
                $(slider.settings.autoControlsSelector).html(slider.controls.autoEl);
                // if auto controls selector was not supplied, add it after the wrapper
            } else {
                slider.controls.el.addClass('bx-has-controls-auto').append(slider.controls.autoEl);
            }
            // update the auto controls
            updateAutoControls(slider.settings.autoStart ? 'stop' : 'start');
        };

        /**
         * Appends image captions to the DOM
         */
        var appendCaptions = function() {
            // cycle through each child
            slider.children.each(function(index) {
                // get the image title attribute
                var title = $(this).find('img:first').attr('title');
                // append the caption
                if (title !== undefined && ('' + title).length) {
                    $(this).append('<div class="bx-caption"><span>' + title + '</span></div>');
                }
            });
        };

        /**
         * Click next binding
         *
         * @param e (event)
         *  - DOM event object
         */
        var clickNextBind = function(e) {
            e.preventDefault();
            if (slider.controls.el.hasClass('disabled')) { return; }
            // if auto show is running, stop it
            if (slider.settings.auto && slider.settings.stopAutoOnClick) { el.stopAuto(); }
            el.goToNextSlide();
        };

        /**
         * Click prev binding
         *
         * @param e (event)
         *  - DOM event object
         */
        var clickPrevBind = function(e) {
            e.preventDefault();
            if (slider.controls.el.hasClass('disabled')) { return; }
            // if auto show is running, stop it
            if (slider.settings.auto && slider.settings.stopAutoOnClick) { el.stopAuto(); }
            el.goToPrevSlide();
        };

        /**
         * Click start binding
         *
         * @param e (event)
         *  - DOM event object
         */
        var clickStartBind = function(e) {
            el.startAuto();
            e.preventDefault();
        };

        /**
         * Click stop binding
         *
         * @param e (event)
         *  - DOM event object
         */
        var clickStopBind = function(e) {
            el.stopAuto();
            e.preventDefault();
        };

        /**
         * Click pager binding
         *
         * @param e (event)
         *  - DOM event object
         */
        var clickPagerBind = function(e) {
            var pagerLink, pagerIndex;
            e.preventDefault();
            if (slider.controls.el.hasClass('disabled')) {
                return;
            }
            // if auto show is running, stop it
            if (slider.settings.auto  && slider.settings.stopAutoOnClick) { el.stopAuto(); }
            pagerLink = $(e.currentTarget);
            if (pagerLink.attr('data-slide-index') !== undefined) {
                pagerIndex = parseInt(pagerLink.attr('data-slide-index'));
                // if clicked pager link is not active, continue with the goToSlide call
                if (pagerIndex !== slider.active.index) { el.goToSlide(pagerIndex); }
            }
        };

        /**
         * Updates the pager links with an active class
         *
         * @param slideIndex (int)
         *  - index of slide to make active
         */
        var updatePagerActive = function(slideIndex) {
            // if "short" pager type
            var len = slider.children.length; // nb of children
            if (slider.settings.pagerType === 'short') {
                if (slider.settings.maxSlides > 1) {
                    len = Math.ceil(slider.children.length / slider.settings.maxSlides);
                }
                slider.pagerEl.html((slideIndex + 1) + slider.settings.pagerShortSeparator + len);
                return;
            }
            // remove all pager active classes
            slider.pagerEl.find('a').removeClass('active');
            // apply the active class for all pagers
            slider.pagerEl.each(function(i, el) { $(el).find('a').eq(slideIndex).addClass('active'); });
        };

        /**
         * Performs needed actions after a slide transition
         */
        var updateAfterSlideTransition = function() {
            // if infinite loop is true
            if (slider.settings.infiniteLoop) {
                var position = '';
                // first slide
                if (slider.active.index === 0) {
                    // set the new position
                    position = slider.children.eq(0).position();
                    // carousel, last slide
                } else if (slider.active.index === getPagerQty() - 1 && slider.carousel) {
                    position = slider.children.eq((getPagerQty() - 1) * getMoveBy()).position();
                    // last slide
                } else if (slider.active.index === slider.children.length - 1) {
                    position = slider.children.eq(slider.children.length - 1).position();
                }
                if (position) {
                    if (slider.settings.mode === 'horizontal') { setPositionProperty(-position.left, 'reset', 0); }
                    else if (slider.settings.mode === 'vertical') { setPositionProperty(-position.top, 'reset', 0); }
                }
            }
            // declare that the transition is complete
            slider.working = false;
            // onSlideAfter callback
            slider.settings.onSlideAfter.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index);
        };

        /**
         * Updates the auto controls state (either active, or combined switch)
         *
         * @param state (string) "start", "stop"
         *  - the new state of the auto show
         */
        var updateAutoControls = function(state) {
            // if autoControlsCombine is true, replace the current control with the new state
            if (slider.settings.autoControlsCombine) {
                slider.controls.autoEl.html(slider.controls[state]);
                // if autoControlsCombine is false, apply the "active" class to the appropriate control
            } else {
                slider.controls.autoEl.find('a').removeClass('active');
                slider.controls.autoEl.find('a:not(.bx-' + state + ')').addClass('active');
            }
        };

        /**
         * Updates the direction controls (checks if either should be hidden)
         */
        var updateDirectionControls = function() {
            if (getPagerQty() === 1) {
                slider.controls.prev.addClass('disabled');
                slider.controls.next.addClass('disabled');
            } else if (!slider.settings.infiniteLoop && slider.settings.hideControlOnEnd) {
                // if first slide
                if (slider.active.index === 0) {
                    slider.controls.prev.addClass('disabled');
                    slider.controls.next.removeClass('disabled');
                    // if last slide
                } else if (slider.active.index === getPagerQty() - 1) {
                    slider.controls.next.addClass('disabled');
                    slider.controls.prev.removeClass('disabled');
                    // if any slide in the middle
                } else {
                    slider.controls.prev.removeClass('disabled');
                    slider.controls.next.removeClass('disabled');
                }
            }
        };

        /**
         * Initializes the auto process
         */
        var initAuto = function() {
            // if autoDelay was supplied, launch the auto show using a setTimeout() call
            if (slider.settings.autoDelay > 0) {
                var timeout = setTimeout(el.startAuto, slider.settings.autoDelay);
                // if autoDelay was not supplied, start the auto show normally
            } else {
                el.startAuto();

                //add focus and blur events to ensure its running if timeout gets paused
                $(window).focus(function() {
                    el.startAuto();
                }).blur(function() {
                    el.stopAuto();
                });
            }
            // if autoHover is requested
            if (slider.settings.autoHover) {
                // on el hover
                el.hover(function() {
                    // if the auto show is currently playing (has an active interval)
                    if (slider.interval) {
                        // stop the auto show and pass true argument which will prevent control update
                        el.stopAuto(true);
                        // create a new autoPaused value which will be used by the relative "mouseout" event
                        slider.autoPaused = true;
                    }
                }, function() {
                    // if the autoPaused value was created be the prior "mouseover" event
                    if (slider.autoPaused) {
                        // start the auto show and pass true argument which will prevent control update
                        el.startAuto(true);
                        // reset the autoPaused value
                        slider.autoPaused = null;
                    }
                });
            }
        };

        /**
         * Initializes the ticker process
         */
        var initTicker = function() {
            var startPosition = 0,
                position, transform, value, idx, ratio, property, newSpeed, totalDimens;
            // if autoDirection is "next", append a clone of the entire slider
            if (slider.settings.autoDirection === 'next') {
                el.append(slider.children.clone().addClass('bx-clone'));
                // if autoDirection is "prev", prepend a clone of the entire slider, and set the left position
            } else {
                el.prepend(slider.children.clone().addClass('bx-clone'));
                position = slider.children.first().position();
                startPosition = slider.settings.mode === 'horizontal' ? -position.left : -position.top;
            }
            setPositionProperty(startPosition, 'reset', 0);
            // do not allow controls in ticker mode
            slider.settings.pager = false;
            slider.settings.controls = false;
            slider.settings.autoControls = false;
            // if autoHover is requested
            if (slider.settings.tickerHover) {
                if (slider.usingCSS) {
                    idx = slider.settings.mode === 'horizontal' ? 4 : 5;
                    slider.viewport.hover(function() {
                        transform = el.css('-' + slider.cssPrefix + '-transform');
                        value = parseFloat(transform.split(',')[idx]);
                        setPositionProperty(value, 'reset', 0);
                    }, function() {
                        totalDimens = 0;
                        slider.children.each(function(index) {
                            totalDimens += slider.settings.mode === 'horizontal' ? $(this).outerWidth(true) : $(this).outerHeight(true);
                        });
                        // calculate the speed ratio (used to determine the new speed to finish the paused animation)
                        ratio = slider.settings.speed / totalDimens;
                        // determine which property to use
                        property = slider.settings.mode === 'horizontal' ? 'left' : 'top';
                        // calculate the new speed
                        newSpeed = ratio * (totalDimens - (Math.abs(parseInt(value))));
                        tickerLoop(newSpeed);
                    });
                } else {
                    // on el hover
                    slider.viewport.hover(function() {
                        el.stop();
                    }, function() {
                        // calculate the total width of children (used to calculate the speed ratio)
                        totalDimens = 0;
                        slider.children.each(function(index) {
                            totalDimens += slider.settings.mode === 'horizontal' ? $(this).outerWidth(true) : $(this).outerHeight(true);
                        });
                        // calculate the speed ratio (used to determine the new speed to finish the paused animation)
                        ratio = slider.settings.speed / totalDimens;
                        // determine which property to use
                        property = slider.settings.mode === 'horizontal' ? 'left' : 'top';
                        // calculate the new speed
                        newSpeed = ratio * (totalDimens - (Math.abs(parseInt(el.css(property)))));
                        tickerLoop(newSpeed);
                    });
                }
            }
            // start the ticker loop
            tickerLoop();
        };

        /**
         * Runs a continuous loop, news ticker-style
         */
        var tickerLoop = function(resumeSpeed) {
            var speed = resumeSpeed ? resumeSpeed : slider.settings.speed,
                position = {left: 0, top: 0},
                reset = {left: 0, top: 0},
                animateProperty, resetValue, params;

            // if "next" animate left position to last child, then reset left to 0
            if (slider.settings.autoDirection === 'next') {
                position = el.find('.bx-clone').first().position();
                // if "prev" animate left position to 0, then reset left to first non-clone child
            } else {
                reset = slider.children.first().position();
            }
            animateProperty = slider.settings.mode === 'horizontal' ? -position.left : -position.top;
            resetValue = slider.settings.mode === 'horizontal' ? -reset.left : -reset.top;
            params = {resetValue: resetValue};
            setPositionProperty(animateProperty, 'ticker', speed, params);
        };

        /**
         * Check if el is on screen
         */
        var isOnScreen = function(el) {
            var win = $(window),
                viewport = {
                    top: win.scrollTop(),
                    left: win.scrollLeft()
                },
                bounds = el.offset();

            viewport.right = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();
            bounds.right = bounds.left + el.outerWidth();
            bounds.bottom = bounds.top + el.outerHeight();

            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
        };

        /**
         * Initializes keyboard events
         */
        var keyPress = function(e) {
            var activeElementTag = document.activeElement.tagName.toLowerCase(),
                tagFilters = 'input|textarea',
                p = new RegExp(activeElementTag,['i']),
                result = p.exec(tagFilters);

            if (result == null && isOnScreen(el)) {
                if (e.keyCode === 39) {
                    clickNextBind(e);
                    return false;
                } else if (e.keyCode === 37) {
                    clickPrevBind(e);
                    return false;
                }
            }
        };

        /**
         * Initializes touch events
         */
        var initTouch = function() {
            // initialize object to contain all touch values
            slider.touch = {
                start: {x: 0, y: 0},
                end: {x: 0, y: 0}
            };
            slider.viewport.on('touchstart MSPointerDown pointerdown', onTouchStart);

            //for browsers that have implemented pointer events and fire a click after
            //every pointerup regardless of whether pointerup is on same screen location as pointerdown or not
            slider.viewport.on('click', '.bxslider a', function(e) {
                if (slider.viewport.hasClass('click-disabled')) {
                    e.preventDefault();
                    slider.viewport.removeClass('click-disabled');
                }
            });
        };

        /**
         * Event handler for "touchstart"
         *
         * @param e (event)
         *  - DOM event object
         */
        var onTouchStart = function(e) {
            //disable slider controls while user is interacting with slides to avoid slider freeze that happens on touch devices when a slide swipe happens immediately after interacting with slider controls
            slider.controls.el.addClass('disabled');

            if (slider.working) {
                e.preventDefault();
                slider.controls.el.removeClass('disabled');
            } else {
                // record the original position when touch starts
                slider.touch.originalPos = el.position();
                var orig = e.originalEvent,
                    touchPoints = (typeof orig.changedTouches !== 'undefined') ? orig.changedTouches : [orig];
                // record the starting touch x, y coordinates
                slider.touch.start.x = touchPoints[0].pageX;
                slider.touch.start.y = touchPoints[0].pageY;

                if (slider.viewport.get(0).setPointerCapture) {
                    slider.pointerId = orig.pointerId;
                    slider.viewport.get(0).setPointerCapture(slider.pointerId);
                }
                // bind a "touchmove" event to the viewport
                slider.viewport.on('touchmove MSPointerMove pointermove', onTouchMove);
                // bind a "touchend" event to the viewport
                slider.viewport.on('touchend MSPointerUp pointerup', onTouchEnd);
                slider.viewport.on('MSPointerCancel pointercancel', onPointerCancel);
            }
        };

        /**
         * Cancel Pointer for Windows Phone
         *
         * @param e (event)
         *  - DOM event object
         */
        var onPointerCancel = function(e) {
            /* onPointerCancel handler is needed to deal with situations when a touchend
             doesn't fire after a touchstart (this happens on windows phones only) */
            setPositionProperty(slider.touch.originalPos.left, 'reset', 0);

            //remove handlers
            slider.controls.el.removeClass('disabled');
            slider.viewport.off('MSPointerCancel pointercancel', onPointerCancel);
            slider.viewport.off('touchmove MSPointerMove pointermove', onTouchMove);
            slider.viewport.off('touchend MSPointerUp pointerup', onTouchEnd);
            if (slider.viewport.get(0).releasePointerCapture) {
                slider.viewport.get(0).releasePointerCapture(slider.pointerId);
            }
        };

        /**
         * Event handler for "touchmove"
         *
         * @param e (event)
         *  - DOM event object
         */
        var onTouchMove = function(e) {
            var orig = e.originalEvent,
                touchPoints = (typeof orig.changedTouches !== 'undefined') ? orig.changedTouches : [orig],
                // if scrolling on y axis, do not prevent default
                xMovement = Math.abs(touchPoints[0].pageX - slider.touch.start.x),
                yMovement = Math.abs(touchPoints[0].pageY - slider.touch.start.y),
                value = 0,
                change = 0;

            // x axis swipe
            if ((xMovement * 3) > yMovement && slider.settings.preventDefaultSwipeX) {
                e.preventDefault();
                // y axis swipe
            } else if ((yMovement * 3) > xMovement && slider.settings.preventDefaultSwipeY) {
                e.preventDefault();
            }
            if (slider.settings.mode !== 'fade' && slider.settings.oneToOneTouch) {
                // if horizontal, drag along x axis
                if (slider.settings.mode === 'horizontal') {
                    change = touchPoints[0].pageX - slider.touch.start.x;
                    value = slider.touch.originalPos.left + change;
                    // if vertical, drag along y axis
                } else {
                    change = touchPoints[0].pageY - slider.touch.start.y;
                    value = slider.touch.originalPos.top + change;
                }
                setPositionProperty(value, 'reset', 0);
            }
        };

        /**
         * Event handler for "touchend"
         *
         * @param e (event)
         *  - DOM event object
         */
        var onTouchEnd = function(e) {
            slider.viewport.off('touchmove MSPointerMove pointermove', onTouchMove);
            //enable slider controls as soon as user stops interacing with slides
            slider.controls.el.removeClass('disabled');
            var orig    = e.originalEvent,
                touchPoints = (typeof orig.changedTouches !== 'undefined') ? orig.changedTouches : [orig],
                value       = 0,
                distance    = 0;
            // record end x, y positions
            slider.touch.end.x = touchPoints[0].pageX;
            slider.touch.end.y = touchPoints[0].pageY;
            // if fade mode, check if absolute x distance clears the threshold
            if (slider.settings.mode === 'fade') {
                distance = Math.abs(slider.touch.start.x - slider.touch.end.x);
                if (distance >= slider.settings.swipeThreshold) {
                    if (slider.touch.start.x > slider.touch.end.x) {
                        el.goToNextSlide();
                    } else {
                        el.goToPrevSlide();
                    }
                    el.stopAuto();
                }
                // not fade mode
            } else {
                // calculate distance and el's animate property
                if (slider.settings.mode === 'horizontal') {
                    distance = slider.touch.end.x - slider.touch.start.x;
                    value = slider.touch.originalPos.left;
                } else {
                    distance = slider.touch.end.y - slider.touch.start.y;
                    value = slider.touch.originalPos.top;
                }
                // if not infinite loop and first / last slide, do not attempt a slide transition
                if (!slider.settings.infiniteLoop && ((slider.active.index === 0 && distance > 0) || (slider.active.last && distance < 0))) {
                    setPositionProperty(value, 'reset', 200);
                } else {
                    // check if distance clears threshold
                    if (Math.abs(distance) >= slider.settings.swipeThreshold) {
                        if (distance < 0) {
                            el.goToNextSlide();
                        } else {
                            el.goToPrevSlide();
                        }
                        el.stopAuto();
                    } else {
                        // el.animate(property, 200);
                        setPositionProperty(value, 'reset', 200);
                    }
                }
            }
            slider.viewport.off('touchend MSPointerUp pointerup', onTouchEnd);
            if (slider.viewport.get(0).releasePointerCapture) {
                slider.viewport.get(0).releasePointerCapture(slider.pointerId);
            }
        };

        /**
         * Window resize event callback
         */
        var resizeWindow = function(e) {
            // don't do anything if slider isn't initialized.
            if (!slider.initialized) { return; }
            // Delay if slider working.
            if (slider.working) {
                window.setTimeout(resizeWindow, 10);
            } else {
                // get the new window dimens (again, thank you IE)
                var windowWidthNew = $(window).width(),
                    windowHeightNew = $(window).height();
                // make sure that it is a true window resize
                // *we must check this because our dinosaur friend IE fires a window resize event when certain DOM elements
                // are resized. Can you just die already?*
                if (windowWidth !== windowWidthNew || windowHeight !== windowHeightNew) {
                    // set the new window dimens
                    windowWidth = windowWidthNew;
                    windowHeight = windowHeightNew;
                    // update all dynamic elements
                    el.redrawSlider();
                    // Call user resize handler
                    slider.settings.onSliderResize.call(el, slider.active.index);
                }
            }
        };

        /**
         * Adds an aria-hidden=true attribute to each element
         *
         * @param startVisibleIndex (int)
         *  - the first visible element's index
         */
        var applyAriaHiddenAttributes = function(startVisibleIndex) {
            var numberOfSlidesShowing = getNumberSlidesShowing();
            // only apply attributes if the setting is enabled and not in ticker mode
            if (slider.settings.ariaHidden && !slider.settings.ticker) {
                // add aria-hidden=true to all elements
                slider.children.attr('aria-hidden', 'true');
                // get the visible elements and change to aria-hidden=false
                slider.children.slice(startVisibleIndex, startVisibleIndex + numberOfSlidesShowing).attr('aria-hidden', 'false');
            }
        };

        /**
         * Returns index according to present page range
         *
         * @param slideOndex (int)
         *  - the desired slide index
         */
        var setSlideIndex = function(slideIndex) {
            if (slideIndex < 0) {
                if (slider.settings.infiniteLoop) {
                    return getPagerQty() - 1;
                }else {
                    //we don't go to undefined slides
                    return slider.active.index;
                }
                // if slideIndex is greater than children length, set active index to 0 (this happens during infinite loop)
            } else if (slideIndex >= getPagerQty()) {
                if (slider.settings.infiniteLoop) {
                    return 0;
                } else {
                    //we don't move to undefined pages
                    return slider.active.index;
                }
                // set active index to requested slide
            } else {
                return slideIndex;
            }
        };

        /**
         * ===================================================================================
         * = PUBLIC FUNCTIONS
         * ===================================================================================
         */

        /**
         * Performs slide transition to the specified slide
         *
         * @param slideIndex (int)
         *  - the destination slide's index (zero-based)
         *
         * @param direction (string)
         *  - INTERNAL USE ONLY - the direction of travel ("prev" / "next")
         */
        el.goToSlide = function(slideIndex, direction) {
            // onSlideBefore, onSlideNext, onSlidePrev callbacks
            // Allow transition canceling based on returned value
            var performTransition = true,
                moveBy = 0,
                position = {left: 0, top: 0},
                lastChild = null,
                lastShowingIndex, eq, value, requestEl;
            // store the old index
            slider.oldIndex = slider.active.index;
            //set new index
            slider.active.index = setSlideIndex(slideIndex);

            // if plugin is currently in motion, ignore request
            if (slider.working || slider.active.index === slider.oldIndex) { return; }
            // declare that plugin is in motion
            slider.working = true;

            performTransition = slider.settings.onSlideBefore.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index);

            // If transitions canceled, reset and return
            if (typeof (performTransition) !== 'undefined' && !performTransition) {
                slider.active.index = slider.oldIndex; // restore old index
                slider.working = false; // is not in motion
                return;
            }

            if (direction === 'next') {
                // Prevent canceling in future functions or lack there-of from negating previous commands to cancel
                if (!slider.settings.onSlideNext.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index)) {
                    performTransition = false;
                }
            } else if (direction === 'prev') {
                // Prevent canceling in future functions or lack there-of from negating previous commands to cancel
                if (!slider.settings.onSlidePrev.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index)) {
                    performTransition = false;
                }
            }

            // check if last slide
            slider.active.last = slider.active.index >= getPagerQty() - 1;
            // update the pager with active class
            if (slider.settings.pager || slider.settings.pagerCustom) { updatePagerActive(slider.active.index); }
            // // check for direction control update
            if (slider.settings.controls) { updateDirectionControls(); }
            // if slider is set to mode: "fade"
            if (slider.settings.mode === 'fade') {
                // if adaptiveHeight is true and next height is different from current height, animate to the new height
                if (slider.settings.adaptiveHeight && slider.viewport.height() !== getViewportHeight()) {
                    slider.viewport.animate({height: getViewportHeight()}, slider.settings.adaptiveHeightSpeed);
                }
                // fade out the visible child and reset its z-index value
                slider.children.filter(':visible').fadeOut(slider.settings.speed).css({zIndex: 0});
                // fade in the newly requested slide
                slider.children.eq(slider.active.index).css('zIndex', slider.settings.slideZIndex + 1).fadeIn(slider.settings.speed, function() {
                    $(this).css('zIndex', slider.settings.slideZIndex);
                    updateAfterSlideTransition();
                });
                // slider mode is not "fade"
            } else {
                // if adaptiveHeight is true and next height is different from current height, animate to the new height
                if (slider.settings.adaptiveHeight && slider.viewport.height() !== getViewportHeight()) {
                    slider.viewport.animate({height: getViewportHeight()}, slider.settings.adaptiveHeightSpeed);
                }
                // if carousel and not infinite loop
                if (!slider.settings.infiniteLoop && slider.carousel && slider.active.last) {
                    if (slider.settings.mode === 'horizontal') {
                        // get the last child position
                        lastChild = slider.children.eq(slider.children.length - 1);
                        position = lastChild.position();
                        // calculate the position of the last slide
                        moveBy = slider.viewport.width() - lastChild.outerWidth();
                    } else {
                        // get last showing index position
                        lastShowingIndex = slider.children.length - slider.settings.minSlides;
                        position = slider.children.eq(lastShowingIndex).position();
                    }
                    // horizontal carousel, going previous while on first slide (infiniteLoop mode)
                } else if (slider.carousel && slider.active.last && direction === 'prev') {
                    // get the last child position
                    eq = slider.settings.moveSlides === 1 ? slider.settings.maxSlides - getMoveBy() : ((getPagerQty() - 1) * getMoveBy()) - (slider.children.length - slider.settings.maxSlides);
                    lastChild = el.children('.bx-clone').eq(eq);
                    position = lastChild.position();
                    // if infinite loop and "Next" is clicked on the last slide
                } else if (direction === 'next' && slider.active.index === 0) {
                    // get the last clone position
                    position = el.find('> .bx-clone').eq(slider.settings.maxSlides).position();
                    slider.active.last = false;
                    // normal non-zero requests
                } else if (slideIndex >= 0) {
                    //parseInt is applied to allow floats for slides/page
                    requestEl = slideIndex * parseInt(getMoveBy());
                    position = slider.children.eq(requestEl).position();
                }

                /* If the position doesn't exist
                 * (e.g. if you destroy the slider on a next click),
                 * it doesn't throw an error.
                 */
                if (typeof (position) !== 'undefined') {
                    value = slider.settings.mode === 'horizontal' ? -(position.left - moveBy) : -position.top;
                    // plugin values to be animated
                    setPositionProperty(value, 'slide', slider.settings.speed);
                } else {
                    slider.working = false;
                }
            }
            if (slider.settings.ariaHidden) { applyAriaHiddenAttributes(slider.active.index * getMoveBy()); }
        };

        /**
         * Transitions to the next slide in the show
         */
        el.goToNextSlide = function() {
            // if infiniteLoop is false and last page is showing, disregard call
            if (!slider.settings.infiniteLoop && slider.active.last) { return; }
            var pagerIndex = parseInt(slider.active.index) + 1;
            el.goToSlide(pagerIndex, 'next');
        };

        /**
         * Transitions to the prev slide in the show
         */
        el.goToPrevSlide = function() {
            // if infiniteLoop is false and last page is showing, disregard call
            if (!slider.settings.infiniteLoop && slider.active.index === 0) { return; }
            var pagerIndex = parseInt(slider.active.index) - 1;
            el.goToSlide(pagerIndex, 'prev');
        };

        /**
         * Starts the auto show
         *
         * @param preventControlUpdate (boolean)
         *  - if true, auto controls state will not be updated
         */
        el.startAuto = function(preventControlUpdate) {
            // if an interval already exists, disregard call
            if (slider.interval) { return; }
            // create an interval
            slider.interval = setInterval(function() {
                if (slider.settings.autoDirection === 'next') {
                    el.goToNextSlide();
                } else {
                    el.goToPrevSlide();
                }
            }, slider.settings.pause);
            // if auto controls are displayed and preventControlUpdate is not true
            if (slider.settings.autoControls && preventControlUpdate !== true) { updateAutoControls('stop'); }
        };

        /**
         * Stops the auto show
         *
         * @param preventControlUpdate (boolean)
         *  - if true, auto controls state will not be updated
         */
        el.stopAuto = function(preventControlUpdate) {
            // if no interval exists, disregard call
            if (!slider.interval) { return; }
            // clear the interval
            clearInterval(slider.interval);
            slider.interval = null;
            // if auto controls are displayed and preventControlUpdate is not true
            if (slider.settings.autoControls && preventControlUpdate !== true) { updateAutoControls('start'); }
        };

        /**
         * Returns current slide index (zero-based)
         */
        el.getCurrentSlide = function() {
            return slider.active.index;
        };

        /**
         * Returns current slide element
         */
        el.getCurrentSlideElement = function() {
            return slider.children.eq(slider.active.index);
        };

        /**
         * Returns a slide element
         * @param index (int)
         *  - The index (zero-based) of the element you want returned.
         */
        el.getSlideElement = function(index) {
            return slider.children.eq(index);
        };

        /**
         * Returns number of slides in show
         */
        el.getSlideCount = function() {
            return slider.children.length;
        };

        /**
         * Return slider.working variable
         */
        el.isWorking = function() {
            return slider.working;
        };

        /**
         * Update all dynamic slider elements
         */
        el.redrawSlider = function() {
            // resize all children in ratio to new screen size
            slider.children.add(el.find('.bx-clone')).outerWidth(getSlideWidth());
            // adjust the height
            slider.viewport.css('height', getViewportHeight());
            // update the slide position
            if (!slider.settings.ticker) { setSlidePosition(); }
            // if active.last was true before the screen resize, we want
            // to keep it last no matter what screen size we end on
            if (slider.active.last) { slider.active.index = getPagerQty() - 1; }
            // if the active index (page) no longer exists due to the resize, simply set the index as last
            if (slider.active.index >= getPagerQty()) { slider.active.last = true; }
            // if a pager is being displayed and a custom pager is not being used, update it
            if (slider.settings.pager && !slider.settings.pagerCustom) {
                populatePager();
                updatePagerActive(slider.active.index);
            }
            if (slider.settings.ariaHidden) { applyAriaHiddenAttributes(slider.active.index * getMoveBy()); }
        };

        /**
         * Destroy the current instance of the slider (revert everything back to original state)
         */
        el.destroySlider = function() {
            // don't do anything if slider has already been destroyed
            if (!slider.initialized) { return; }
            slider.initialized = false;
            $('.bx-clone', this).remove();
            slider.children.each(function() {
                if ($(this).data('origStyle') !== undefined) {
                    $(this).attr('style', $(this).data('origStyle'));
                } else {
                    $(this).removeAttr('style');
                }
            });
            if ($(this).data('origStyle') !== undefined) {
                this.attr('style', $(this).data('origStyle'));
            } else {
                $(this).removeAttr('style');
            }
            $(this).unwrap().unwrap();
            if (slider.controls.el) { slider.controls.el.remove(); }
            if (slider.controls.next) { slider.controls.next.remove(); }
            if (slider.controls.prev) { slider.controls.prev.remove(); }
            if (slider.pagerEl && slider.settings.controls && !slider.settings.pagerCustom) { slider.pagerEl.remove(); }
            $('.bx-caption', this).remove();
            if (slider.controls.autoEl) { slider.controls.autoEl.remove(); }
            clearInterval(slider.interval);
            if (slider.settings.responsive) { $(window).off('resize', resizeWindow); }
            if (slider.settings.keyboardEnabled) { $(document).off('keydown', keyPress); }
            //remove self reference in data
            $(this).removeData('bxSlider');
        };

        /**
         * Reload the slider (revert all DOM changes, and re-initialize)
         */
        el.reloadSlider = function(settings) {
            if (settings !== undefined) { options = settings; }
            el.destroySlider();
            init();
            //store reference to self in order to access public functions later
            $(el).data('bxSlider', this);
        };

        init();

        $(el).data('bxSlider', this);

        // returns the current jQuery object
        return this;
    };

})(jQuery);
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

    btn.removeProp('disabled');
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