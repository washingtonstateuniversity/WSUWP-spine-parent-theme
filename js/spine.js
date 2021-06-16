/*
	TermTemplate:"<strong> <%this.term%> </strong>",
	this.options.relatedHeader

	termTemplate
	autoSearchObj.options.provider.termTemplate
*/

/* jshint onevar: false */
( function( $ ) {
	"use strict";
	$.widget( "ui.autosearch", $.ui.autocomplete, {
		_renderMenu: function( ul, items ) {
			var that	= this;
			var related	= $.grep( items, function( obj ) {//, item) {
				return obj.related !== "false";
			} );
			var unrelated = $.grep( items, function( obj ) {//, item) {
				return obj.related === "false";
			} );
			$.each( unrelated, function( i, item ) {
				that._renderItemData( ul, item );
			} );

			if ( this.options.showRelated && related.length ) {
				if ( this.options.relatedHeader ) {
					that._renderHeader( ul, this.options.relatedHeader );
				}
				$.each( related, function( i, item ) {
					that._renderItemData( ul, item );
				} );
			}
		},
		_renderItemData: function( ul, item ) {
			return this._renderItem( ul, item ).data( "ui-autocomplete-item", item );
		},
		_renderItem: function( ul, item ) {
			var text	= item.label;

			return $( "<li></li>" ).data( "item.autocomplete", item ).append( text ).appendTo( ul );
		},
		_renderHeader: function( ul, text ) {
			return $( "<li></li>" ).append( "<a href=''>" + text + "</a>" ).appendTo( ul );
		}
	} );
} )( jQuery );

/** Intended usage is
*	$.spine({
*		"option":"value"
*	});
**/
/*jshint -W054 */
;( function( $, window, document, undefined ) {
	/**
	 * Strip one or more classes from a class attribute matching a given prefix.
	 *
	 * @param {string} partialMatch The class partial to match against, like `btn-` to match `btn-danger btn-active`, but not `btn`.
	 * @param {string} endOrBegin   Omit to match the beginning. Provide a truthy value to only find classes ending with a match.
	 * @returns {jQuery}
	 */
	$.fn.stripClass = function( partialMatch, endOrBegin ) {
		var x;
		x = new RegExp( ( !endOrBegin ? "\\b" : "\\S+" ) + partialMatch + "\\S*", "g" );

		// http://stackoverflow.com/a/2644364/1037948
		this.attr( "class", function( i, c ) {
			if ( !c ) {
				return; // Protect against no class
			}
			return c.replace( x, "" );
		} );
		return this;
	};

	/**
	 * Refresh a snapshot of stored jQuery selector data.
	 *
	 * Not all stored object properties would normally be reflected when
	 * the original selector is modified. This ensures we capture the
	 * latest version.
	 *
	 * @returns {*}
	 */
	$.fn.refresh = function() {
		var elems;
		elems = $( this );
		this.splice( 0, this.length );

		try {
			this.push.apply( this, elems );
		}
		catch ( err ) {
			if ( $( this ).html() !== "" ) {
				return $( this );
			} else {
				return $( "<div>" );
			}
    	}

		return this;
	};

	/**
	 * A small templating engine for processing HTML with given data.
	 *
	 * @see TemplateEngine via MIT Licensed https://github.com/krasimir/absurd/
	 *
	 * @param {string} html
	 * @param {Object} options
	 * @returns {*}
	 */
	$.runTemplate = function( html, options ) {
		var re, add, match, cursor, code, reExp, result;

		re = /<%(.+?)%>/g;
		reExp = /(^( )?(var|if|for|else|switch|case|break|{|}|;))(.*)?/g;
		code = "var r=[];\n";
		cursor = 0;

		add = function( line, js ) {
			if ( js ) {
				code += line.match( reExp ) ? line + "\n" : "r.push(" + line + ");\n";
			}else {
				code += line !== "" ? "r.push('" + line.replace( /'/g, "\"" ) + "');\n" : "";
			}
			return add;
		};

		while ( ( match = re.exec( html ) ) ) {
			add( html.slice( cursor, match.index ) )( match[ 1 ], true );
			cursor = match.index + match[ 0 ].length;
		}

		add( html.substr( cursor, html.length - cursor ) );
		code = ( code + "return r.join('');" ).replace( /[\r\t\n]/g, "" );
		result = new Function( code ).apply( options );

		return result;
	};

	/**
	 * Unused in Spine.
	 *
	 * @todo Consider deprecating.
	 *
	 * @returns {*}
	 */
	$.whenAll = function() {
		return $.when.apply( $, arguments );
	};

	/**
	 * Determine if the current view is an iOS device.
	 *
	 * @returns {boolean}
	 */
	$.is_iOS = function() {
		return ( window.navigator.userAgent.match( /(iPad|iPhone|iPod)/ig ) ? true : false );
	};

	/**
	 * Determine if the current view is an Android device.
	 *
	 * @returns {boolean}
	 */
	$.is_Android = function() {
		return ( window.navigator.userAgent.match( /(Android)/ig ) ? true : false );
	};

	/**
	 * Detect browser support for SVG images.
	 *
	 * @returns {boolean}
	 */
	$.svg_enabled = function() {
		return document.implementation.hasFeature( "http://www.w3.org/TR/SVG11/feature#Image", "1.1" );
	};

	/**
	 * Use MutationObserver to watch for any changes to a specific DOM element and trigger
	 * the passed callback when a change is made.
	 *
	 * This is currently only used within the Spine to watch `#glue` for changes such as
	 * menu expansion, etc...
	 *
	 * @param obj
	 * @param callback
	 */
	$.observeDOM = function( obj, callback ) {
		var config, mutationObserver;

		if ( window.MutationObserver ) {
			config = {
				childList: true,
				attributes: true,
				subtree: true,
				attributeOldValue: true,
				attributeFilter: [ "class", "style" ]
			};

			mutationObserver = new MutationObserver( function( mutationRecords ) {
				var fire_callback = false; // Assume no callback is needed.

				$.each( mutationRecords, function( index, mutationRecord ) {
					if ( mutationRecord.type === "childList" ) {
						if ( mutationRecord.addedNodes.length > 0 ) {
							fire_callback = true;
						} else if ( mutationRecord.removedNodes.length > 0 ) {
							fire_callback = true;
						}
					} else if ( mutationRecord.type === "attributes" ) {
						if ( mutationRecord.attributeName === "class" ) {
							fire_callback = true;
						}
					}
				} );

				// If one of our matched mutations has been observed, fire the callback.
				if ( fire_callback ) {
					callback();
				}
			} );
			mutationObserver.observe( obj[ 0 ], config );
		} else {

			// Set a fallback function to fire every 200ms and watch for DOM changes.
			window.setTimeout( function() {
				var current_obj = obj.refresh();

				if ( typeof window.obj_watch === "undefined" ) {
					window.obj_watch = current_obj[ 0 ];
				}

				/**
				 * If the current object does not match the object we're watching, assume
				 * a DOM mutation has occurred and fire the callback.
				 */
				if ( window.obj_watch !== current_obj[ 0 ] ) {
					callback();
				}

				window.obj_watch = current_obj[ 0 ];

				// Reset observation on the current object.
				$.observeDOM( current_obj, callback );
			}, 200 );
		}
	};

	/**
	 * Setup the plugin's prototype.
	 *
	 * @param {string} name
	 * @param {object} prototype
	 */
	$.s = function( name, prototype ) {
		var namespace;

		namespace = name.split( "." )[ 0 ];
		name = name.split( "." )[ 1 ];

		$[ namespace ] = $[ namespace ] || {};

		$[ namespace ][ name ] = function( options, element ) {
			if ( arguments.length ) {
				this._setup( options, element );
			}
		};

		$[ namespace ][ name ].prototype = $.extend( {
			namespace: namespace,
			pluginName: name
		}, prototype );

		$.fn[ name ] = function( context ) {
			var isMethodCall, context_options, args, returnValue;

			context_options = {};

			if ( arguments[ 1 ] ) {
				context_options = arguments[ 1 ];
			}

			context = context || {};

			this.options = $.extend( {}, context );

			isMethodCall = ( typeof context === "string" );
			args = Array.prototype.slice.call( arguments, 1 );
			returnValue = this;

			if ( isMethodCall && context.substring( 0, 1 ) === "_" ) {
				return returnValue;
			}

			this.each( function() {
				var instance;

				instance = $.data( this, name );

				if ( !instance ) {
					instance = $.data( this, name, new $[ namespace ][ name ]( context, this ) );
				}

				if ( instance[ context + "_init" ] !== undefined ) {
					if ( instance[ context + "_init" ] ) {
						instance[ context + "_init" ]( context_options );
					}
				}

				if ( isMethodCall && instance[ context ] !== undefined ) {
					returnValue = instance[ context ].apply( instance, args );
				}
			} );
			return returnValue;
		};
	};

	/**
	 * Configure and create the jQuery.ui.spine plugin.
	 *
	 * Based on a fork of MIT Licensed jquery-ui-map
	 * See: https://code.google.com/p/jquery-ui-map/source/browse/trunk/ui/jquery.ui.map.js
	 */
	$.s( "ui.spine", {

		globals: {
			version: "0.1.0",
			current_url:window.location.href
		},

		options: {},

		/**
		 * Setup plugin basics.
		 *
		 * @param {object}      options
		 * @param {HTMLElement} element
		 */
		_setup: function( options, element ) {
			this.el = element;
			options = options || {};
			$.extend( this.options, options, {} );
			this._create();
		},

		/**
		 * Instantiate the object
		 */
		_create: function() {
			var self;

			self = this;
			this.instance = {
				spine: self.options,
				framework: [],
				search: [],
				social: []
			};

			self._call( self.options.callback, self.instance.spine );
		},

		/**
		 * Add objects to the global spine object.
		 *
		 * Note: Context is not yet implemented.
		 *
		 * @param {object} obj     e.g. {'foo':'bar'}
		 * @param {string} context e.g. 'search', 'social', 'framework'
		 */
		_set_globals: function( obj, context ) {
			context = null; // Avoiding jshint error temporarily.

			if ( typeof( obj ) !== "object" ) {
				return;
			}
			$.extend( this.globals, obj );
		},

		/**
		 * Retrieve a context's objects from the global spine object.
		 *
		 * @param {string} context e.g. 'search', 'social', 'framework'
		 * @returns {*}
		 * @private
		 */
		_get_globals: function( context ) {
			return this.globals[ context ];
		},

		/**
		 * Clears an object of a context.
		 *
		 * @param {string} context e.g. 'search', 'social', 'framework'
		 */
		clear: function( context ) {
			this._c( this.get( context ) );
			this.set( context, [] );

			return this;
		},

		/**
		 * Clears an object of its properties.
		 *
		 * @param {object} obj
		 */
		_c: function( obj ) {
			for ( var property in obj ) {
				if ( obj.hasOwnProperty( property ) ) {
					obj[ property ] = null;
				}
			}
		},

		/**
		 * Returns objects with a specific context.
		 *
		 * @param {string} context In what context, e.g. 'search', 'social', 'framework'
		 * @param {object} options Contains string property, string value, string operator (AND/OR).
		 * @param callback:function(search:jObj, isFound:boolean)
		 */
		find: function( context, options, callback ) {
			var obj, isFound, property, value;
			obj = this.get( context );
			options.value = $.isArray( options.value ) ? options.value : [ options.value ];
			for ( property in obj ) {
				if ( obj.hasOwnProperty( property ) ) {
					isFound = false;
					for ( value in options.value ) {
						if ( $.inArray( options.value[ value ], obj[ property ][ options.property ] ) > -1 ) {
							isFound = true;
						} else {
							if ( options.operator && options.operator === "AND" ) {
								isFound = false;
								break;
							}
						}
					}
					callback( obj[ property ], isFound );
				}
			}
			return this;
		},

		/**
		 * Returns an instance property by key. Has the ability to set an object if the property does not exist
		 * @param key:string
		 * @param value:object(optional)
		 */
		get: function( key, value ) {
			var instance, e, i;
			instance = this.instance;
			if ( !instance[ key ] ) {
				if ( key.indexOf( ">" ) > -1 ) {
					e = key.replace( / /g, "" ).split( ">" );
					for ( i = 0; i < e.length; i++ ) {
						if ( !instance[ e[ i ] ] ) {
							if ( value ) {
								instance[ e[ i ] ] = ( ( i + 1 ) < e.length ) ? [] : value;
							} else {
								return null;
							}
						}
						instance = instance[ e[ i ] ];
					}
					return instance;
				} else if ( value && !instance[ key ] ) {
					this.set( key, value );
				}
			}
			return instance[ key ];
		},

		/**
		 * Sets an instance property
		 * @param key:string
		 * @param value:object
		 */
		set: function( key, value ) {
			this.instance[ key ] = value;
			return this;
		},

		/**
		 * Helper method for unwrapping jQuery/DOM/string elements
		 * @param obj:string/node/jQuery
		 */
		_unwrap: function( obj ) {
			return ( !obj ) ? null : ( ( obj instanceof jQuery ) ? obj[ 0 ] : ( ( obj instanceof Object ) ? obj : $( "#" + obj )[ 0 ] ) );
		},

		/**
		 * Helper method for calling a function
		 * @param callback
		 */
		_call: function( callback ) {
			if ( callback && $.isFunction( callback ) ) {
				callback.apply( this, Array.prototype.slice.call( arguments, 1 ) );
			}
		},

		/**
		 * Destroys spine elements and options.
		 */
		clear_spine: function() {
			this.clear( "search" ).clear( "framework" ).clear( "social" );
		},

		/**
		 * Destroys the plugin.
		 */
		destroy: function( callback ) {
			this.clear( "search" ).clear( "framework" ).clear( "social" )._c( this.instance );
			$.removeData( this.el, this.name );
			this._call( callback, this );
		}
	} );

	/**
	 * The primary Spine method used to start things up.
	 *
	 * @param {object} options
	 * @returns {*}
	 */
	$.spine = function( options ) {
		var targ;

		targ = this.jquery === undefined ? $( "body" ) : this;

		return $.each( targ, function() {
			var targ;
			targ = $( this );

			// Initialize the Spine plugin.
			targ.spine( {} );

			options = $.extend( { "framework":{}, "search":{}, "social":{} }, options );

			// Setup each of the extensions.
			$.each( options, function( i, v ) {
				targ.spine( i, v );
			} );
		} );
	};

} )( jQuery, window, document );

 /*!
 *
 * Depends:
 *		jquery.ui.v.js
 */
/*jshint multistr: true */
( function( $ ) {
	$.extend( $.ui.spine.prototype, {
		/**
		 * Initialize the Spine framework. Fired automatically in `$.s`, found
		 * in ui.spine.js.
		 *
		 * @param {object} options
		 */
		framework_init: function( options ) {
			$.extend( this.framework_options, options );
			this._set_globals( this.framework_globals );
			this.framework_create();
		},

		/**
		 * Global framework options for the Spine framework.
		 */
		framework_options: {
			viewport_offset: 0,
			equalizer_filter: ".skip*",
			contact_template: "<address itemscope itemtype='http://schema.org/Organization' class='hcard'>" +
								"<% if (typeof(this.department) != 'undefined') { %><div class='organization-unit fn org'>" +
									"<% if (typeof(this.url) != 'undefined') { %><a href='<%this.url%>' class='url'><% } %>" +
										"<%this.department%>" +
									"<% if (typeof(this.url) != 'undefined') { %></a><% } %>" +
								"</div><% } %> " +
								"<% if (typeof(this.name) != 'undefined') { %><div class='organization-name'><%this.name%></div><% } %>" +
								"<div class='address'>" +
									"<% if (typeof(this.streetAddress) != 'undefined') { %><div class='street-address'><%this.streetAddress%></div><% } %>" +
									"<% if (typeof(this.addressLocality) != 'undefined' || typeof(this.postalCode) != 'undefined') { %><div class='locality'>" +
										"<% if (typeof(this.addressLocality) != 'undefined' ) { %><%this.addressLocality%><% } %>" +
										"<% if (typeof(this.addressRegion) != 'undefined' ) { %>, <%this.addressRegion%><% } %>" +
										"<% if (typeof(this.postalCode) != 'undefined' ) { %> <span class='postalcode'><%this.postalCode%></span><% } %>" +
									"</div><% } %>" +
								"</div>" +
								"<% if (typeof(this.telephone) != 'undefined' ) { %><div class='tel'><%this.telephone%></div><% } %>" +
								"<% if (typeof(this.email) != 'undefined' ) { %><div class='email' rel='email'><a href='mailto:<%this.email%>'>Email us</a></div><% } %>" +
								"<% if (typeof(this.ContactPoint) != 'undefined' && typeof(this.ContactPointTitle) != 'undefined') { %>" +
									"<div class='more'><a href='<%this.ContactPoint%>'><%this.ContactPointTitle%></a></div>" +
								"<% } %>" +
								"</address>"
		},

		/**
		 * Global objects that are part of the Spine framework.
		 */
		framework_globals: {
			spine: $( "#spine" ),
			glue: $( "#glue" ),
			main: $( "main" ),
			wsu_actions: $( "#wsu-actions" )
		},

		/**
		 * Data on the current state of navigation for use when calculating
		 * sizes and placement of other elements.
		 */
		nav_state:{
			viewport_ht: 0,
			scroll_dif: 0,
			positionLock: 0,
			scroll_top: 0,
			spine_ht: 0,
			glue_ht: 0,
			height_dif: 0
		},

		/**
		 * Setup a scroll container for use with iOS.
		 */
		setup_nav_scroll: function() {
			$( "#glue" ).wrapInner( "<div id='scroll'>" );
			$( "#spine header" ).insertBefore( $( "#glue" ) );
		},

		/**
		 * Determine if the page view is in a mobile state, defined as less than 990px;
		 */
		is_mobile_view: function() {
			if ( window.matchMedia ) {
				return window.matchMedia( "(max-width: 989px)" ).matches;
			} else if ( window.styleMedia ) {

				// Fallback for IE 9. IE 8 and below do not support media queries anyway.
				return window.styleMedia.matchMedium( "(max-width: 989px)" );
			}

			return false;
		},

		/**
		 * Set the Spine to a given state, mobile or full.
		 *
		 * @param {string} state The state of the Spine to set.
		 */
		set_spine_state: function( state ) {
			if ( "mobile" === state ) {
				$( "html" ).removeClass( "spine-full" ).addClass( "ios spine-mobile" );
				this.setup_nav_scroll();
			} else {
				$( "html" ).removeClass( "ios spine-mobile spine-mobile-open" ).addClass( "spine-full" );
				if ( $( "#scroll" ).length ) {
					$( "#wsu-actions" ).unwrap();
					$( "#spine header" ).prependTo( "#glue" );
				}
			}
		},

		/**
		 * Determine if the Spine is already in a mobile state.
		 *
		 * @returns {boolean}
		 */
		has_mobile_state: function() {
			if ( $( "html" ).hasClass( "spine-mobile" ) ) {
				return true;
			}

			return false;
		},

		/**
		 * On a resize event, adjust pieces of the Spine framework accordingly.
		 */
		framework_adjust_on_resize: function() {
			var self, spread, verso, page, para, recto, recto_margin, verso_width,
				viewport_ht, spine, glue, main;

			self = this;

			// Refresh data for global elements.
			spine = self._get_globals( "spine" ).refresh();
			glue = self._get_globals( "glue" ).refresh();
			main = self._get_globals( "main" ).refresh();

			if ( self.is_mobile_view() && !self.has_mobile_state() ) {
				self.set_spine_state( "mobile" );
			} else if ( !self.is_mobile_view() && self.has_mobile_state() ) {
				self.set_spine_state( "full" );
			}

			self.sizing();
			self.equalizing();

			if ( self.is_mobile_view() ) {
				self.mainheight();
			}

			// Only run function if an unbound element exists
			if ( $( ".unbound, #binder.broken" ).length ) {
				spread = $( window ).width();
				verso = self._get_globals( "main" ).offset().left;
				page = self._get_globals( "main" ).width();
				recto = spread - self._get_globals( "main" ).offset().left;
				recto_margin = "";

				if ( recto >= page ) {
					recto_margin = recto - page;
				} else {
					recto_margin = 0;
				}

				/* Broken Binding */
				if ( $( "#binder" ).is( ".broken" ) ) {
					self._get_globals( "main" ).css( "width", recto );
				}

				verso_width = verso + self._get_globals( "main" ).width();

				$( ".unbound:not(.element).recto" ).css( "width", recto ).css( "margin-right", -( recto_margin ) );
				$( ".unbound.element.recto" ).each( function() {
					para = $( this ).width();
					$( this ).css( "width", para + recto_margin ).css( "margin-right", -( recto_margin ) );
				} );
				$( ".unbound.verso" ).css( "width", verso_width ).css( "margin-left", -( verso ) );
				$( ".unbound.verso.recto" ).css( "width", spread );
			}

			viewport_ht = $( window ).height() - this.framework_options.viewport_offset;

			if ( !self.is_mobile_view() ) {
				glue.css( "min-height", viewport_ht );
				spine.css( "min-height", viewport_ht );

				$( document ).trigger( "scroll" );
			} else {
				glue.css( "min-height", "" );
				spine.css( "min-height", "" );
			}
		},

		/**
		 * Create the Spine framework and setup basic events based on information present in the DOM.
		 */
		framework_create: function() {
			var self, contactHtml, propmap = {}, svg_imgs;

			self = this; // Preserve scope.

			// Generate the contact section.
			if ( !$( "#wsu-contact" ).length ) {
				contactHtml = "<section id='wsu-contact' class='spine-contact spine-action closed'>";
				propmap = {};

				$.each( $( "[itemtype='http://schema.org/Organization']" ), function() {
					var tar = this;
					$.each( $( tar ).find( "[itemprop]" ), function( i, v ) {
						var tmp = {};
						tmp[ $( v ).attr( "itemprop" ) ] = $( v ).attr( "content" );
						$.extend( propmap, tmp );
					} );
					contactHtml += $.runTemplate( self.framework_options.contact_template, propmap );
				} );
				contactHtml += "</section>";
				self.setup_tabs( "contact", contactHtml );
			}

			self.setup_nav();

			// Set the initial state of the Spine on page load. Mobile is defined as less than 990px.
			if ( self.is_mobile_view() ) {
				self.set_spine_state( "mobile" );
			} else {
				$( "html" ).addClass( "spine-full" );
			}

			// If SVG is not supported, add a class and replace Spine SVG files with PNG equivalents.
			if ( !$.svg_enabled() ) {
				$( "html" ).addClass( "nosvg" );
				svg_imgs = $( "img[src$='.svg']" );

				if ( svg_imgs.length ) {
					$.each( svg_imgs, function() {
						$( this ).attr( "src", $( this ).attr( "src" ).replace( ".svg", ".png" ) );
					} );
				}
			}

			self.setup_spine();
			self.setup_printing();

			$( window ).on( "resize orientationchange", function() { self.framework_adjust_on_resize(); } ).trigger( "resize" );

			if ( !self.is_mobile_view() ) {
				$( document ).trigger( "scroll" );
			}
		},

		/**
		 * Label `#jacket` with the current window size.
		 *
		 * @param {HTMLelement} jacket
		 */
		sizing: function( jacket ) {
			var current_width, jacket_classes, px_width, size_intermediate, size_medium, size_large;

            jacket = jacket || $( "#jacket" );

            current_width = $( window ).width();

			size_intermediate = "size-intermediate size-smallish size-lt-medium size-lt-large size-lt-xlarge size-gt-small";
			size_medium = "size-medium size-lt-xlarge size-lt-large size-gt-intermediate size-gt-smallish size-gt-small";
			size_large = "size-large size-lt-xlarge size-gt-small size-gt-intermediate size-gt-smallish size-gt-medium";

			px_width = "";

			if ( current_width >= 1188 ) {
				jacket_classes = "size-xlarge size-gt-small size-gt-intermediate size-gt-smallish size-gt-medium size-gt-large";
			} else if ( current_width >= 990 ) {
				jacket_classes = size_large;
			} else if ( ( current_width < 990 ) && current_width >= 792 ) {
				px_width = "size-lt-990";
				jacket_classes = $( "#binder" ).is( ".fluid" ) ? size_large : size_medium;
			} else if ( current_width < 792 && current_width >= 694 ) {
				px_width = "size-lt-792";
				jacket_classes = $( "#binder" ).is( ".fixed" ) ? size_intermediate : size_medium;
			} else if ( current_width < 694 && current_width >= 396 ) {
				jacket_classes = "size-small size-lt-intermediate size-lt-smallish size-lt-medium size-lt-large size-lt-xlarge";
			} else {
				jacket_classes = "size-small size-lt-small size-lt-intermediate size-lt-smallish size-lt-medium size-lt-large size-lt-xlarge";
			}

			jacket.stripClass( "size-" ).addClass( jacket_classes + " " + px_width );
		},

		/**
		 * Equalize columns in a layout.
		 */
		equalizing: function() {
			var obj;

			if ( $( ".equalize" ).length ) {
				obj = $( ".equalize" );
				obj.find( ".column" ).css( "min-height", "" );

				$.each( obj, function() {
					var tallestBox = 0;
					$.each( $( ".column", this ), function() {
						tallestBox = ( $( this ).outerHeight() > tallestBox ) ? $( this ).outerHeight() : tallestBox;
					} );

					if ( ( $( window ).width() <= 792 && !obj.is( ".equalize-medium" ) ) || ( $( window ).width() <= 694 && !obj.is( ".equalize-small" ) ) ) {
						$( ".column", this ).not( ".unequaled" ).css( "min-height", "1" );
					} else {
						$( ".column", this ).not( ".unequaled" ).css( "min-height", tallestBox );
					}
					$( "section.equalize .column", this ).css( "min-height", "auto" );
				} );
			}
		},

		/**
		 * Apply a minimum height to the `main` element.
		 */
		mainheight: function() {
			var main, window_height, main_height;

			main = this._get_globals( "main" ).refresh();

			if ( main.offset() ) {
				window_height = $( window ).height();
				main_height = window_height;
				if ( $( "#binder" ).is( ".size-lt-large" ) ) {
					main_height -= 50;
				}
				$( "main:not(.height-auto)" ).css( "min-height", main_height );
			}
		},

		/**
		 * Sets up framework html and other DOM attributes
		 */
		setup_jacket: function() {},

		/**
		 * Sets up framework html and other DOM attributes
		 */
		setup_binder: function() {},

		 /**
		 * Sets up framework html and other DOM attributes
		 */
		setup_content: function() {},

		/**
		 * Toggle the display and removal of the mobile navigation.
		 *
		 * @param e
		 */
		toggle_mobile_nav: function( e ) {
			var html, body, shelve, spine, glue, transitionEnd;

			if ( typeof e !== "undefined" ) {
				e.preventDefault();
			}

			html = $( "html" );
			body = $( "body" );
			shelve = $( "#shelve" );
			spine = $.ui.spine.prototype._get_globals( "spine" ).refresh();
			glue = $.ui.spine.prototype._get_globals( "glue" ).refresh();

			/* Cross browser support for CSS "transition end" event */
			transitionEnd = "transitionend webkitTransitionEnd otransitionend MSTransitionEnd";

			// Whether opening or closing, the Spine will be animating from this point forward.
			body.addClass( "spine-animating" );

			// Tell the browser and stylesheet what direction the Spine is animating.
			if ( html.hasClass( "spine-mobile-open" ) ) {
				body.addClass( "spine-move-left" );
				shelve.attr( "aria-expanded", "false" );
			} else {
				body.addClass( "spine-move-right" );
				shelve.attr( "aria-expanded", "true" );
			}

			glue.on( transitionEnd, function() {
				body.removeClass( "spine-animating spine-move-left spine-move-right" );

				if ( html.hasClass( "spine-mobile-open" ) ) {
					html.removeClass( "spine-mobile-open" );

					$( "#scroll" ).off( "touchstart" );
					$( document ).off( "touchmove touchend touchstart" );
				} else {
					html.addClass( "spine-mobile-open" );

					var scroll_element = document.querySelector( "#scroll" );

					scroll_element.addEventListener( "touchstart", function() {
						var top = scroll_element.scrollTop, totalScroll = scroll_element.scrollHeight, currentScroll = top + scroll_element.offsetHeight;

						if ( top === 0 ) {
							scroll_element.scrollTop = 1;
						} else if ( currentScroll === totalScroll ) {
							scroll_element.scrollTop = top - 1;
						}
					} );

					// Prevent scrolling on mobile outside of `#scroll` while the mobile menu is open.
					$( document ).on( "touchmove touchend touchstart", function( evt ) {
						if ( $( evt.target ).parents( "#scroll" ).length > 0 || $( evt.target ).is( "#scroll" ) ) {
							return true;
						}

						evt.stopPropagation();
						evt.preventDefault();
					} );
				}
				glue.off( transitionEnd );
			} );
		},

		/**
		 * Sets up the spine area
		 */
		setup_spine: function() {
			var self, spine, glue, main, viewport_ht, spine_ht, height_dif, positionLock;

			$( "#spine .spine-header" ).prepend( "<button id='shelve' type='button' aria-label='Site Navigation' aria-haspopup='true' aria-controls='glue' aria-expanded='false' />" );

			self = this;

			spine = self._get_globals( "spine" ).refresh();
			glue = self._get_globals( "glue" ).refresh();
			main = self._get_globals( "main" ).refresh();

			self.nav_state.scroll_top = 0;
			self.nav_state.scroll_dif = 0;
			self.nav_state.positionLock = 0;

			// The menu button should always trigger a toggle of the mobile navigation.
			$( ".spine-header" ).on( "click touchend", "#shelve", self.toggle_mobile_nav );

			// Tapping anything outside of the Spine should trigger a toggle if the menu is open.
			main.on( "click", function( e ) {
				if ( $( "html" ).hasClass( "spine-mobile-open" ) ) {
					self.toggle_mobile_nav( e );
				}
			} );

			if ( !self.is_mobile_view() ) {

				// Watch for DOM changes and resize the Spine to match.
				$.observeDOM( glue, function() {
					self.apply_nav_func( self );
				} );

				// Fixed/Sticky Horizontal Header
				$( document ).on( "scroll", function() {
					self.apply_nav_func( self );
				} );

				$( document ).keydown( function( e ) {
					if ( e.which === 35 || e.which === 36 ) {
						viewport_ht	= $( window ).height();
						spine_ht	= spine[ 0 ].scrollHeight;
						height_dif	= viewport_ht - spine_ht;

						if ( e.which === 35 ) {
							positionLock = height_dif;
						} else if ( e.which === 36 ) {
							positionLock = 0;
						}

						spine.css( { "position": "fixed", "top": positionLock + "px" } );
						self.nav_state.positionLock = positionLock;
					}
				} );

				// Apply the `.skimmed` class to the Spine on non mobile views after 148px.
				$( document ).scroll( function() {
					var top;
					top = $( document ).scrollTop();
					if ( top > 148 ) {
						$( "#spine" ).addClass( "skimmed" );
					} else {
						$( "#spine" ).removeClass( "skimmed" );
					}
				} );
			}

			/**
			 * When the navigation area is shorter than the available window, add a margin to the
			 * Spine footer so that the scroll container becomes active. This avoids issues on
			 * mobile devices when overflow is not applied.
			 */
			if ( self.is_mobile_view() ) {
				var nav_height = $( ".spine-header" ).height() + $( "#wsu-actions" ).height() + $( "#spine-navigation" ).height();
				var spine_footer = $( ".spine-footer" );
				var footer_height = spine_footer.height();
				if ( window.innerHeight - nav_height >= footer_height ) {
					var margin = window.innerHeight - nav_height - footer_height;
					spine_footer.css( "margin-top", margin );
				}
			}
		},

		/**
		 * Ensure Spine navigation is properly positioned and sized to snap to the top
		 * and bottom of the document.
		 *
		 * @param self
		 */
		apply_nav_func: function( self ) {
			var spine, glue, main, top, scroll_top, positionLock, scroll_dif, glue_ht;

			spine = self._get_globals( "spine" ).refresh();
			glue = self._get_globals( "glue" ).refresh();
			main = self._get_globals( "main" ).refresh();

			scroll_top   = self.nav_state.scroll_top;
			positionLock = self.nav_state.positionLock;

			top          = $( document ).scrollTop();
			scroll_dif   = scroll_top - top;
			scroll_top   = top;
			glue_ht		 = glue.height();

			self.nav_state.scroll_top = scroll_top;

			// Main should always be at least as high as `#glue`.
			main.css( { "min-height": glue_ht } );

			/**
			 * When the content in `main` is larger than the content in `#glue`, maintain a
			 * fixed top position on `#spine` for smooth and predictable navigation scrolling.
			 */
			if ( main.outerHeight( true ) > glue_ht ) {
				var upper_bound = glue_ht - window.innerHeight;

				/**
				 * Assume fluid movement by default. As long as we are within the upper bounds
				 * we can calculate the position based on scroll location whether the scroll
				 * goes up or down.
				 */
				positionLock = positionLock + scroll_dif;

				/**
				 * If the position is ever greater than 0, we've scrolled too far up and can
				 * reset the position to 0.
				 */
				if ( positionLock > 0 ) {
					positionLock = 0;
				}

				/**
				 * If we ever scroll to a place where the new position would be calculated
				 * outside of the upper bound, then reset it to the upper bound. This prevents
				 * from scrolling too far down.
				 */
				if ( positionLock < ( -1 * upper_bound ) ) {
					positionLock = ( -1 * upper_bound ) - this.framework_options.viewport_offset;
				}

				spine.css( { "position": "fixed", "top": positionLock + "px" } );
				self.nav_state.positionLock = positionLock;
			} else if ( spine.is( "#spine[style]" ) ) {
				spine.removeAttr( "style" );
			}
		},

		/**
		 * Toggle a Spine action item and the data associated with it between an
		 * open and closed state on touch.
		 *
		 * @param evt
		 * @private
		 */
		_toggle_spine_action_item: function( evt ) {
			var tab, action_ht;

			evt.preventDefault();

			tab = $( evt.target ).parent( "li" ).attr( "id" ).split( "-" )[ 1 ];

			$( "#wsu-actions" ).find( "*.opened, #wsu-" + tab + ", #wsu-" + tab + "-tab" ).toggleClass( "opened closed" );

			if ( $( evt.target ).parent( "li" ).hasClass( "opened" ) ) {
				$( "#spine-navigation, .spine-footer" ).css( "visibility", "hidden" );
			} else {
				$( "#spine-navigation, .spine-footer" ).css( "visibility", "initial" );
			}

			action_ht = window.innerHeight - $( ".spine-header" ).outerHeight() - $( "#wsu-actions-tabs" ).outerHeight();

			$( ".spine-action.opened" ).css( "min-height", action_ht );
			$( evt.target ).off( "mouseup touchend", $.ui.spine.prototype._toggle_spine_action_item );
		},

		/**
		 * Process a WSU action tab (mail, sharing, etc...) and setup the
		 * structure accordingly.
		 */
		setup_tabs: function( tab, html ) {
			var self, wsu_actions, action_ht;

			html = html || "";
			self = this;

			wsu_actions = self._get_globals( "wsu_actions" ).refresh();

			$( "#wsu-" + tab + "-tab" ).append( html );

			if ( self.is_mobile_view() ) {
				$( "#wsu-" + tab + "-tab > button" ).on( "mousedown touchstart", function( e ) {
					$( e.target ).on( "mouseup touchend", $.ui.spine.prototype._toggle_spine_action_item );
					$( e.target ).on( "mousemove touchmove", function( e ) {
						$( e.target ).off( "mouseup touchend", $.ui.spine.prototype._toggle_spine_action_item );
					} );
				} );
			} else {
				$( "#wsu-" + tab + "-tab > button" ).on( "click", function( e ) {
					e.preventDefault();
					wsu_actions.find( "*.opened,#wsu-" + tab + ",#wsu-" + tab + "-tab" ).toggleClass( "opened closed" );

					// Hide the Spine navigation from screen readers when action tabs are in focus.
					if ( $( "#wsu-" + tab + "-tab" ).hasClass( "opened" ) ) {
						$( "#spine-navigation, .spine-footer" ).css( "visibility", "hidden" );
					} else {
						$( "#spine-navigation, .spine-footer" ).css( "visibility", "initial" );
					}

					action_ht = window.innerHeight - $( ".spine-header" ).outerHeight() - $( "#wsu-actions-tabs" ).outerHeight();

					$( ".spine-action.opened" ).css( "min-height", action_ht );
				} );
			}
		},

		/**
		 * Toggle a parent list item and its children between an open
		 * and closed state on touch.
		 *
		 * @param evt
		 * @private
		 */
		_toggle_spine_nav_list: function( evt ) {
			var target = $( evt.target );

			evt.preventDefault();
			target.closest( "li" ).toggleClass( "opened" );

			// Remove the toggle event, as it will be added again on the next touchstart.
			target.off( "mouseup touchend", $.ui.spine.prototype._toggle_spine_nav_list );
		},

		/**
		 * Sets up navigation system
		 */
		setup_nav: function() {
			var self = this;

			// Apply the `parent` class to each parent list item of an unordered list in the navigation.
			$( "#spine nav ul, #spine ul" ).parents( "li" ).addClass( "parent" );

			var couplets = $( "#spine nav li.parent > a" );

			// Assign active elements a class of dogeared unless those elements contain other active elements.
			$( "#spine .active:not(:has(.active))" ).addClass( "dogeared" );

			/**
			 * Walk through each of the anchor elements in the navigation to establish when "Overview"
			 * items should be added and what the text should read.
			 */
			couplets.each( function() {
				var tar, title, url;
				tar = $( this );
				url = tar.attr( "href" );

				// "Overview" anchors are only added for parents with URLs.
				if ( "#" === url ) {
					return;
				}

				var classes = "overview";

				// If a generated overview's parent is marked as dogeared, do the same with the overview.
				if ( tar.closest( ".parent" ).is( ".dogeared" ) ) {
					classes += " dogeared";
				}

				title = ( tar.is( "[title]" )  ) ? tar.attr( "title" ) : "Overview";
				title = ( tar.is( "[data-overview]" ) ) ? tar.data( "overview" ) : title;
				title = title.length > 0 ? title : "Overview"; // This is just triple checking that a value made it here.

				tar.parent( "li" ).children( "ul" ).prepend( "<li class='" + classes + "'></li>" );
				tar.clone( true, true ).appendTo( tar.parent( "li" ).find( "ul .overview:first" ) );
				tar.parent( "li" ).find( "ul .overview:first a" ).html( title );

				// When the overview page is active, that area of the navigation should be opened.
				if ( tar.parent( "li" ).hasClass( "active" ) ) {
					tar.parents( "li" ).removeClass( "active" ).addClass( "opened dogeared" );
				}
			} );

			/**
			 * Account for historical markup in the WSU ecosystem and add the `active` and `dogeared` classes
			 * to any list items that already have classes similar to `current` or `active`. Also apply the
			 * `opened` and `dogeared` classes to any parent list items of these active elements.
			 *
			 * `active` and `dogeared` are both used for the styling of active menu items in the navigation.
			 */
			$( "#spine nav li[class*=current], #spine nav li[class*=active]" ).addClass( "active dogeared" ).parents( "li" ).addClass( "opened dogeared" );

			/**
			 * Also look for any anchor elements using a similar method and apply `active` and `dogeared` classes to
			 * all parent list items.
			 */
			$( "#spine nav li a[class*=current], #spine nav li a[class*=active]" ).parents( "li" ).addClass( "active dogeared" );

			/**
			 * Setup navigation events depending on what the screen size is when the document first
			 * loads. If mobile, we use touch events for navigation. If not mobile, we rely on
			 * standard click events.
			 *
			 * Some additional handling is necessary on mobile to properly handle the sequence of
			 * touchstart, touchmove, and touchend without confusion.
			 */
			if ( self.is_mobile_view() ) {
				couplets.on( "mousedown touchstart", function( e ) {
					$( e.target ).on( "mouseup touchend", $.ui.spine.prototype._toggle_spine_nav_list );
					$( e.target ).on( "mousemove touchmove", function( e ) {
						$( e.target ).off( "mouseup touchend", $.ui.spine.prototype._toggle_spine_nav_list );
					} );
				} );
			} else {

				// Disclosure
				couplets.on( "click", function( e ) {
					e.preventDefault();
					$( e.target ).closest( "li" ).toggleClass( "opened" );
				} );

				// Trigger a scroll action when an anchor link is used.
				$( "main a[href*='#']:not([href*='://'])" ).on( "mouseup", function() {
					$( document ).trigger( "scroll" );
				} );
			}

			// Mark external URLs in the nav menu.
			$( ".spine-navigation a[href^='http']:not([href*='://" + window.location.hostname + "'])" ).addClass( "external" );
		},

		/**
		 * Handle printing action when selected in the Spine.
		 */
		setup_printing: function() {
			var self, spine, wsu_actions, print_controls;

			self = this;
			spine = self._get_globals( "spine" ).refresh();
			wsu_actions = self._get_globals( "wsu_actions" ).refresh();

			// Print & Print View
			print_controls = "<span class='print-controls'><button id='print-invoke'>Print</button><button id='print-cancel'>Cancel</button></span>";

			function printPage() {
				window.print();
			}

			function print_cancel() {
				$( "html" ).toggleClass( "print" );
				$( ".print-controls" ).remove();
			}

			function print( e ) {
				if ( undefined !== e ) {
					e.preventDefault();
				}
				wsu_actions.find( ".opened" ).toggleClass( "opened closed" );
				$( "html" ).toggleClass( "print" );
				spine.find( "header" ).prepend( print_controls );
				$( ".unshelved" ).removeClass( "unshelved" ).addClass( "shelved" );
				$( "#print-invoke" ).on( "click", function() { window.print(); } );
				$( "#print-cancel" ).on( "click", print_cancel );
				window.setTimeout( function() { printPage(); }, 400 );
			}
			$( "#wsu-print-tab button" ).click( print );

			// Shut a tool section
			$( "button.shut" ).on( "click touchend", function( e ) {
				e.preventDefault();
				wsu_actions.find( ".opened" ).toggleClass( "opened closed" );
			} );
		}
	} );
}( jQuery ) );

 /*!
 *
 * Depends:
 *		jquery.ui.v.js
 */
/*jshint multistr: true */
( function( $ ) {
	"use strict";
	$.extend( $.ui.spine.prototype, {
		search_init: function( options ) {
			var self;
			self = this;//Hold to preserve scop
			$.extend( options, self.search_options, options );
			this._set_globals( this.search_globals );
			this.create_search();
		},

		search_options:{
			data:[],
			providers:{
				nav:{
					name:"From Navigation",
					nodes: ".spine-navigation",
					dataType: "html",
					maxRows: 12,
					urlSpaces:"%20"
				},
				atoz:{
					name:"WSU A to Z index",
					url: "https://search.wsu.edu/2013service/searchservice/search.asmx/AZSearch",
					urlSpaces:"+",
					dataType: "jsonp",
					featureClass: "P",
					style: "full",
					maxRows: 12,
					termTemplate:"<strong><%this.term%></strong>",
					resultTemplate:""
				}
			},
			search:{
				minLength: 2,
				maxRows: 12,
				getRelated:true,
				urlSpaces:"+",
				tabTemplate: "<section id='wsu-search' class='spine-search spine-action closed'>" +
								"<form id='default-search'>" +
									"<input name='term' type='text' value='' placeholder='search' title='Type search term here'>" +
									"<button type='submit'>Submit</button>" +
								"</form>" +
								"<div id='spine-shortcuts' class='spine-shortcuts'></div>" +
							"</section>"
			},
			result:{
				appendTo: "#spine-shortcuts",
				showRelated:true,
				target:"_blank",
				relatedHeader:"<b class='related_sep'>Related</b><hr/>",
				providerHeader:"<b class='provider_header'><%this.provider_name%></b><hr/>",
				termTemplate:"<b><%this.term%></b>",
				template:"<li><%this.searchitem%></li>"
			}
		},
		search_globals: {
			wsu_search: $( "#wsu-search" ),
			search_input:$( "#wsu-search input[type=text]" )
		},
		create_search: function() {
			var self, wsu_search, tabhtml;
			self = this;//Hold to preserve scop
			wsu_search = self._get_globals( "wsu_search" ).refresh();
			if ( !wsu_search.length ) {
				tabhtml = $.runTemplate( self.search_options.search.tabTemplate, {} );
			}else {
				tabhtml = "<section id='wsu-search' class='spine-search spine-action closed'>" + wsu_search.html() + "</section>";
				wsu_search.remove();
			}
			this.setup_tabs( "search", tabhtml );

			if ( $( "#spine-shortcuts" ).length <= 0 ) {
				$( "#wsu-search" ).append( "<div id='spine-shortcuts' class='spine-shortcuts'></div>" );
			}

			$( "#wsu-search-tab button" ).on( "click touchend", function() {
				self._get_globals( "search_input" ).refresh().focus();
			} );
			this.setup_search();
		},

		start_search:function( request, callback ) {
			var self, term, queries = [];
			self = this;//Hold to preserve scop

			term = $.trim( request.term );
			self.search_options.data = [];
			$.each( self.search_options.providers, function( i, provider ) {
				$.ui.autocomplete.prototype.options.termTemplate = ( typeof( provider.termTemplate ) !== undefined && provider.termTemplate !== "" ) ? provider.termTemplate : undefined;
				queries.push( self.run_query( term, provider ) );
			} );

			$.when.apply( $, queries ).done(
			function() {
				$.each( arguments, function( i, v ) {
					var data, proData;
					if ( v !== undefined ) {
						data = v[ 0 ];
						if ( data !== undefined && data.length > 0 ) {
							proData = self.setup_result_obj( term, data );
							$.merge( self.search_options.data, proData );
						}
					}
				} );
				self._call( callback, self.search_options.data );
			} );
		},

		run_query:function( term, provider ) {
			var self, result = [], tmpObj = [], nodes;
			self = this;//Hold to preserve scop
			result = [];

			if ( typeof( provider ) !== undefined && typeof( provider.url ) !== undefined && provider.nodes === undefined ) {
				return $.ajax( {
					url: provider.url,
					dataType: provider.dataType,
					data: {
						featureClass: provider.featureClass,
						style: provider.style,
						maxRows: provider.maxRows,
						name_startsWith: term,
						related:self.search_options.search.getRelated
					}
				} );
			}else if ( typeof( provider ) !== undefined && typeof( provider.nodes ) !== undefined ) {
				nodes = $( provider.nodes ).find( "a" );
				$.each( nodes, function( i, v ) {
					var obj, text, localtmpObj;
					obj = $( v );
					text = obj.text();
					if ( text.toLowerCase().indexOf( term.toLowerCase() ) > -1 && obj.attr( "href" ) !== "#" ) {
						localtmpObj = {
							label:text,
							value:obj.attr( "href" ),
							related:"false",
							searchKeywords:""
						};
						tmpObj.push( localtmpObj );
					}
				} );
				return [ tmpObj ];
			}
		},

		format_result_text:function( term, text, value ) {
			var self, termTemplate, regex;
			self = this;//Hold to preserve scope

			termTemplate = "<strong>$1</strong>"; //Typeof($.ui.autocomplete.prototype.options.termTemplate)!==undefined ? $.ui.autocomplete.prototype.options.termTemplate : "<strong>$1</strong>";

			regex	= "(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex( term ) + ")(?![^<>]*>)(?![^&;]+;)";
			text	= "<a href='" + value + "' target='" + self.search_options.result.target + "'>" + text.replace( new RegExp( regex, "gi" ), termTemplate ) + "</a>";

			return text;
		},

		setup_result_obj:function( term, data ) {
			var self, matcher;
			self = this;//Hold to preserve scop
			matcher = new RegExp( $.ui.autocomplete.escapeRegex( term ), "i" );
			return $.map( data, function( item ) {
				var text, value, resultObj;
				text = item.label;
				value	= item.value;
				if ( ( item.value && ( !term || matcher.test( text ) ) || item.related === "true" ) ) {
					text = self.format_result_text( term, text, value );
					resultObj = {
						label: text,
						value: item.value,
						searchKeywords: item.searchKeywords !== undefined ? item.searchKeywords : "false",
						related: item.related !== undefined ? item.related : "false"
					};
					return resultObj;
				}
			} );
		},

		setup_search: function() {
			var self, wsu_search, search_input, focuseitem = {};

			self = this;//Hold to preserve scop
			wsu_search = self._get_globals( "wsu_search" ).refresh();
			search_input = self._get_globals( "search_input" ).refresh();
			focuseitem = {};

			search_input.autosearch( {

				appendTo:			self.search_options.result.appendTo,
				showRelated:		self.search_options.result.showRelated,
				relatedHeader:		self.search_options.result.relatedHeader,
				minLength:			self.search_options.search.minLength,

				source: function( request, response )  {
					self.start_search( request, function( data ) {
						response( data );
					} );
				},
				search: function( ) {
					focuseitem = {};
				},
				select: function( e, ui ) {
					var id, term;
					id = ui.item.searchKeywords;
					term = $( ui.item.label ).text();
					search_input.val( term );
					search_input.autosearch( "close" );
					return false;
				},
				focus: function( e, ui ) {
					search_input.val( $( ui.item.label ).text() );
					focuseitem = {
						label:ui.item.label
					};
					e.preventDefault();
				},
				open: function( ) {},
				close: function( e ) {
					e.preventDefault();
					return false;
				}
			} ).data( "autosearch" );

			$( "#wsu-search form" ).submit( function() {
				var scope, site, cx, cof, search_term, search_url;
				scope = wsu_search.attr( "data-default" );
				site = " site:" + window.location.hostname;
				if ( scope === "wsu.edu" ) {
					site = "";
				}
				cx = "cx=004677039204386950923:xvo7gapmrrg";
				cof = "cof=FORID%3A11";
				search_term = search_input.val();
				search_url = "https://search.wsu.edu/default.aspx?" + cx + "&" + cof + "&q=" + search_term + site;
				window.location.href = search_url;
				return false;
			} );
		}
	} );
}( jQuery ) );

/*!
*
* Depends:
*		jquery.ui.spine.js
*/
/*jshint multistr: true */
( function( $ ) {
	$.extend( $.ui.spine.prototype, {
		social_init: function( options ) {
			$.extend( this.social_options, options );

			this._set_globals( this.social_globals );
			this.social_create();
		},
		/**
		 * These default options can be overridden with an object before
		 * the Spine framework is started and with `$('body').spine( spineoptions )`.
		 *
		 * NOTE: The structure of these social options **will** change and could be
		 * deprecated in a future release. Please communicate via the WSU Spine repository
		 * when using these so that we can reach out before a transition in the future.
		 *
		 * https://github.com/washingtonstateuniversity/WSU-spine/issues/230
		 */
		social_options:{
			share_text:"You should know ...",
			twitter_text:"You should know...",
			twitter_handle:"wsupullman",
			linkedin_source:"wsu.edu"
		},
		social_globals: {
			share_block: $( "#wsu-share" )
		},
		social_create: function() {
			var self, share_block, share_text, current_url, wsu_actions, sharehtml, twitter_text, twitter_handle, linkedin_source;
			self = this;//Hold to preserve scope
			share_block = self._get_globals( "share_block" ).refresh();
			if ( !share_block.length ) {
				share_text = encodeURIComponent( this.social_options.share_text );
				twitter_text = encodeURIComponent( this.social_options.twitter_text );
				twitter_handle = encodeURIComponent( this.social_options.twitter_handle );
				current_url = self._get_globals( "current_url" );
				wsu_actions = self._get_globals( "wsu_actions" ).refresh();

				sharehtml  = "<section id='wsu-share' class='spine-share spine-action closed'>";
				sharehtml += "<ul>";
				sharehtml += "<li class='by-facebook'><a href='https://www.facebook.com/sharer/sharer.php?u=" + current_url + "'><span class='screen-reader-text'>Share this URL on </span>Facebook</a></li>";

				sharehtml += "<li class='by-twitter'><a href='https://twitter.com/intent/tweet?text=" + twitter_text + "&url=" + current_url;
				sharehtml += "&via=" + twitter_handle + "' target='_blank'><span class='screen-reader-text'>Share this URL on </span>Twitter</a></li>";

				sharehtml += "<li class='by-googleplus'><a href='https://plus.google.com/share?url=" + current_url + "'><span class='screen-reader-text'>Share this URL on </span>Google+</a></li>";

				sharehtml += "<li class='by-linkedin'><a href='https://www.linkedin.com/shareArticle?mini=true&url=" + current_url + "&summary=";
				sharehtml += share_text + "&source=" + linkedin_source + "' target='_blank'><span class='screen-reader-text'>Share this URL on </span>Linkedin</a></li>";

				sharehtml += "<li class='by-email'><a href='mailto:?subject=" + share_text + "&body=" + current_url + "'><span class='screen-reader-text'>Share this URL with </span>Email</a></li>";
				sharehtml += "</ul>";
				sharehtml += "</section>";

				self.setup_tabs( "share", sharehtml );
			} // End Share Generation
		}
	} );
}( jQuery ) );

( function( $ ) {
	"use strict";
	$( document ).ready( function() {
		$( "html" ).removeClass( "no-js" ).addClass( "js" );
		var spineoptions = window.spineoptions || {};
		$.spine( spineoptions );
	} );
} )( jQuery );