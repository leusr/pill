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