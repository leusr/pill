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