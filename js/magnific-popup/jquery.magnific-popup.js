/*! Magnific Popup - v0.9.9 - 2014-09-06
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2014 Dmitry Semenov; */
; (function ($) {

	/*>>core*/
	/**
	 * 
	 * Magnific Popup Core JS file
	 * 
	 */


	/**
	 * Private static constants
	 */
	var CLOSE_EVENT = 'Close',
		BEFORE_CLOSE_EVENT = 'BeforeClose',
		AFTER_CLOSE_EVENT = 'AfterClose',
		BEFORE_APPEND_EVENT = 'BeforeAppend',
		MARKUP_PARSE_EVENT = 'MarkupParse',
		OPEN_EVENT = 'Open',
		CHANGE_EVENT = 'Change',
		NS = 'mfp',
		EVENT_NS = '.' + NS,
		READY_CLASS = 'mfp-ready',
		REMOVING_CLASS = 'mfp-removing',
		PREVENT_CLOSE_CLASS = 'mfp-prevent-close';


	/**
	 * Private vars 
	 */
	var mfp, // As we have only one instance of MagnificPopup object, we define it locally to not to use 'this'
		MagnificPopup = function () { },
		_isJQ = !!(window.jQuery),
		_prevStatus,
		_window = $(window),
		_body,
		_document,
		_prevContentType,
		_wrapClasses,
		_currPopupType;


	/**
	 * Private functions
	 */
	var _mfpOn = function (name, f) {
		mfp.ev.on(NS + name + EVENT_NS, f);
	},
		_getEl = function (className, appendTo, html, raw) {
			var el = document.createElement('div');
			el.className = 'mfp-' + className;
			if (html) {
				el.innerHTML = html;
			}
			if (!raw) {
				el = $(el);
				if (appendTo) {
					el.appendTo(appendTo);
				}
			} else if (appendTo) {
				appendTo.appendChild(el);
			}
			return el;
		},
		_mfpTrigger = function (e, data) {
			mfp.ev.triggerHandler(NS + e, data);

			if (mfp.st.callbacks) {
				// converts "mfpEventName" to "eventName" callback and triggers it if it's present
				e = e.charAt(0).toLowerCase() + e.slice(1);
				if (mfp.st.callbacks[e]) {
					mfp.st.callbacks[e].apply(mfp, $.isArray(data) ? data : [data]);
				}
			}
		},
		_getCloseBtn = function (type) {
			if (type !== _currPopupType || !mfp.currTemplate.closeBtn) {
				mfp.currTemplate.closeBtn = $(mfp.st.closeMarkup.replace('%title%', mfp.st.tClose));
				_currPopupType = type;
			}
			return mfp.currTemplate.closeBtn;
		},
		// Initialize Magnific Popup only when called at least once
		_checkInstance = function () {
			if (!$.magnificPopup.instance) {
				mfp = new MagnificPopup();
				mfp.init();
				$.magnificPopup.instance = mfp;
			}
		},
		// CSS transition detection, http://stackoverflow.com/questions/7264899/detect-css-transitions-using-javascript-and-without-modernizr
		supportsTransitions = function () {
			var s = document.createElement('p').style, // 's' for style. better to create an element if body yet to exist
				v = ['ms', 'O', 'Moz', 'Webkit']; // 'v' for vendor

			if (s['transition'] !== undefined) {
				return true;
			}

			while (v.length) {
				if (v.pop() + 'Transition' in s) {
					return true;
				}
			}

			return false;
		};



	/**
	 * Public functions
	 */
	MagnificPopup.prototype = {

		constructor: MagnificPopup,

		/**
		 * Initializes Magnific Popup plugin. 
		 * This function is triggered only once when $.fn.magnificPopup or $.magnificPopup is executed
		 */
		init: function () {
			var appVersion = navigator.appVersion;
			mfp.isIE7 = appVersion.indexOf("MSIE 7.") !== -1;
			mfp.isIE8 = appVersion.indexOf("MSIE 8.") !== -1;
			mfp.isLowIE = mfp.isIE7 || mfp.isIE8;
			mfp.isAndroid = (/android/gi).test(appVersion);
			mfp.isIOS = (/iphone|ipad|ipod/gi).test(appVersion);
			mfp.supportsTransition = supportsTransitions();

			// We disable fixed positioned lightbox on devices that don't handle it nicely.
			// If you know a better way of detecting this - let me know.
			mfp.probablyMobile = (mfp.isAndroid || mfp.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent));
			_document = $(document);

			mfp.popupsCache = {};
		},

		/**
		 * Opens popup
		 * @param  data [description]
		 */
		open: function (data) {

			var i;

			if (data.isObj === false) {
				// convert jQuery collection to array to avoid conflicts later
				mfp.items = data.items.toArray();

				mfp.index = 0;
				var items = data.items,
					item;
				for (i = 0; i < items.length; i++) {
					item = items[i];
					if (item.parsed) {
						item = item.el[0];
					}
					if (item === data.el[0]) {
						mfp.index = i;
						break;
					}
				}
			} else {
				mfp.items = $.isArray(data.items) ? data.items : [data.items];
				mfp.index = data.index || 0;
			}

			// if popup was opened before but was closed by user
			if (mfp.isOpen) {
				mfp.updateItemHTML();
				return;
			}

			mfp.types = [];
			_wrapClasses = '';
			if (data.mainEl && data.mainEl.length) {
				mfp.ev = data.mainEl.eq(0);
			} else {
				mfp.ev = _document;
			}

			if (data.key) {
				if (!mfp.popupsCache[data.key]) {
					mfp.popupsCache[data.key] = {};
				}
				mfp.currTemplate = mfp.popupsCache[data.key];
			} else {
				mfp.currTemplate = {};
			}



			mfp.st = $.extend(true, {}, $.magnificPopup.defaults, data);
			mfp.fixedContentPos = mfp.st.fixedContentPos === 'auto' ? !mfp.probablyMobile : mfp.st.fixedContentPos;

			if (mfp.st.modal) {
				mfp.st.closeOnContentClick = false;
				mfp.st.closeOnBgClick = false;
				mfp.st.showCloseBtn = false;
				mfp.st.enableEscapeKey = false;
			}


			// Building markup
			// main containers are created only once
			if (!mfp.bgOverlay) {

				// Dark overlay
				mfp.bgOverlay = _getEl('bg').on('click' + EVENT_NS, function () {
					mfp.close();
				});

				mfp.wrap = _getEl('wrap').attr('tabindex', -1).on('click' + EVENT_NS, function (e) {
					if (mfp._checkIfClose(e.target)) {
						mfp.close();
					}
				});

				mfp.container = _getEl('container', mfp.wrap);
			}

			mfp.contentContainer = _getEl('content');
			if (mfp.st.preloader) {
				mfp.preloader = _getEl('preloader', mfp.container, mfp.st.tLoading);
			}


			// Initializing modules
			var modules = $.magnificPopup.modules;
			for (i = 0; i < modules.length; i++) {
				var n = modules[i];
				n = n.charAt(0).toUpperCase() + n.slice(1);
				mfp['init' + n].call(mfp);
			}
			_mfpTrigger('BeforeOpen');


			if (mfp.st.showCloseBtn) {
				// Close button
				if (!mfp.st.closeBtnInside) {
					mfp.wrap.append(_getCloseBtn());
				} else {
					_mfpOn(MARKUP_PARSE_EVENT, function (e, template, values, item) {
						values.close_replaceWith = _getCloseBtn(item.type);
					});
					_wrapClasses += ' mfp-close-btn-in';
				}
			}

			if (mfp.st.alignTop) {
				_wrapClasses += ' mfp-align-top';
			}



			if (mfp.fixedContentPos) {
				mfp.wrap.css({
					overflow: mfp.st.overflowY,
					overflowX: 'hidden',
					overflowY: mfp.st.overflowY
				});
			} else {
				mfp.wrap.css({
					top: _window.scrollTop(),
					position: 'absolute'
				});
			}
			if (mfp.st.fixedBgPos === false || (mfp.st.fixedBgPos === 'auto' && !mfp.fixedContentPos)) {
				mfp.bgOverlay.css({
					height: _document.height(),
					position: 'absolute'
				});
			}



			if (mfp.st.enableEscapeKey) {
				// Close on ESC key
				_document.on('keyup' + EVENT_NS, function (e) {
					if (e.keyCode === 27) {
						mfp.close();
					}
				});
			}

			_window.on('resize' + EVENT_NS, function () {
				mfp.updateSize();
			});


			if (!mfp.st.closeOnContentClick) {
				_wrapClasses += ' mfp-auto-cursor';
			}

			if (_wrapClasses)
				mfp.wrap.addClass(_wrapClasses);


			// this triggers recalculation of layout, so we get it early to not to trigger it on append
			var windowHeight = mfp.wH = _window.height();


			var windowStyles = {};

			if (mfp.fixedContentPos) {
				if (mfp._hasScrollBar) {
					windowStyles.overflowY = 'scroll';
				} else {
					windowStyles.overflow = 'hidden';
					windowStyles.overflowY = 'hidden';
				}
			}

			if (mfp.fixedBgPos) {
				windowStyles.overflow = 'hidden';
			}

			mfp.bodyObject.css(windowStyles);

			mfp.updateItemHTML();

			_mfpTrigger('Open');

			mfp.bgOverlay.appendTo(mfp.st.container || i);

			mfp.wrap.appendTo(mfp.st.container ? mfp.st.container : i);

			/*if(mfp.currTemplate[mfp.currItem.type]) {
				mfp.currTemplate[mfp.currItem.type] = $(mfp.currTemplate[mfp.currItem.type]).appendTo(mfp.container);
				_mfpTrigger('BeforeChange', mfp.currTemplate[mfp.currItem.type]);
			}*/


			mfp._setDimension();

			mfp._preloadImages();

			if (!mfp.supportsTransition) {
				mfp._afterZoomIn();
			} else {
				mfp.wrap.css('overflow', 'hidden');
				mfp._afterZoomIn();
			}


			if (mfp.isOpen) {
				mfp.wrap.css('transition', 'none');
			}


			mfp.isOpen = true;

			mfp.updateSize(windowHeight);


			// Delay adding content to avoid height jump
			setTimeout(function () {
				mfp._preloadImages();
			}, 16);

			_mfpTrigger('DelayContent');

		},

		/**
		 * Closes the popup
		 */
		close: function () {
			if (!mfp.isOpen) return;
			_mfpTrigger(BEFORE_CLOSE_EVENT);

			mfp.isOpen = false;
			// undefined transition duration is considered as 0
			var duration = mfp.st.removalDelay || 0;

			mfp.container.addClass(REMOVING_CLASS);

			if (mfp.bgOverlay)
				mfp.bgOverlay.addClass(REMOVING_CLASS);

			if (mfp.st.removalDelay) {
				setTimeout(function () {
					mfp._close();
				}, duration);
			} else {
				mfp._close();
			}
		},

		_close: function () {

			_mfpTrigger(CLOSE_EVENT);

			var classesToRemove = REMOVING_CLASS + ' ' + READY_CLASS + ' ';

			mfp.bgOverlay.detach();
			mfp.wrap.detach();
			mfp.container.empty();

			if (mfp.st.mainClass) {
				classesToRemove += mfp.st.mainClass + ' ';
			}

			mfp._removeClassFromMFP(classesToRemove);

			if (mfp.fixedContentPos) {
				var windowStyles = { marginRight: '' };
				if (mfp.isIE7) {
					$('body, html').css('overflow', '');
				} else {
					windowStyles.overflow = '';
				}
				$('html').css(windowStyles);
			}

			_document.off('keyup' + EVENT_NS + ' focusin' + EVENT_NS);
			mfp.ev.off(EVENT_NS);

			// clean up DOM elements that aren't removed
			mfp.wrap.attr('class', 'mfp-wrap').removeAttr('style');
			mfp.bgOverlay.attr('class', 'mfp-bg');
			mfp.container.attr('class', 'mfp-container');

			// remove close button from target element
			if (mfp.st.showCloseBtn &&
				(!mfp.st.closeBtnInside || mfp.currTemplate[mfp.currItem.type] === true)) {
				if (mfp.currTemplate.closeBtn)
					mfp.currTemplate.closeBtn.detach();
			}


			if (mfp._lastFocusedEl) {
				$(mfp._lastFocusedEl).trigger('focus'); // put tab focus back
			}

			mfp.currItem = null;
			mfp.content = null;
			mfp.currTemplate = null;
			mfp.prevHeight = 0;

			_mfpTrigger(AFTER_CLOSE_EVENT);
		},

		updateSize: function (winHeight) {

			if (mfp.isIOS) {
				// fixes iOS nav bars https://github.com/dimsemenov/Magnific-Popup/issues/2
				var zoomLevel = document.documentElement.clientWidth / window.innerWidth;
				var height = window.innerHeight * zoomLevel;
				mfp.wrap.css('height', height);
				mfp.wH = height;
			} else {
				mfp.wH = winHeight || I.height();
			}
			// Fixes #84: popup incorrectly positioned with position:relative on body
			if (!mfp.fixedContentPos) {
				mfp.wrap.css('height', mfp.wH);
			}

			_mfpTrigger('Resize');

		},

		/**
		 * Set content of popup based on current index
		 */
		updateItemHTML: function () {
			var item = mfp.items[mfp.index];

			// Detach and perform modifications
			mfp.contentContainer.detach();

			if (mfp.content)
				mfp.content.detach();

			if (!item.parsed) {
				item = mfp.parseEl(mfp.index);
			}

			var type = item.type;

			_mfpTrigger('BeforeChange', [mfp.currItem ? mfp.currItem.type : '', type]);
			// BeforeChange event works like so:
			// _mfpOn('BeforeChange', function(e, prevType, newType) { });

			mfp.currItem = item;





			if (!mfp.currTemplate[type]) {
				var markup = mfp.st[type] ? mfp.st[type].markup : false;

				// allows to modify markup
				_mfpTrigger('FirstMarkupParse', markup);

				if (markup) {
					mfp.currTemplate[type] = $(markup);
				} else {
					// if there is no markup found we just define that template is parsed
					mfp.currTemplate[type] = true;
				}
			}

			if (_prevContentType && _prevContentType !== item.type) {
				mfp.container.removeClass('mfp-' + _prevContentType + '-holder');
			}

			var newContent = mfp['get' + type.charAt(0).toUpperCase() + type.slice(1)](item, mfp.currTemplate[type]);
			mfp.appendContent(newContent, type);

			item.preloaded = true;

			_mfpTrigger(CHANGE_EVENT, item);
			_prevContentType = item.type;

			// Append container back after changes
			mfp.container.prepend(mfp.contentContainer);

			_mfpTrigger('AfterChange');
		},


		/**
		 * Set HTML content of popup
		 */
		appendContent: function (newContent, type) {
			mfp.content = newContent;

			if (newContent) {
				if (mfp.st.showCloseBtn && mfp.st.closeBtnInside &&
					mfp.currTemplate[type] === true) {
					// if there is no markup, we see the close button
					if (!mfp.content.find('.mfp-close').length) {
						mfp.content.append(_getCloseBtn());
					}
				} else {
					mfp.content = newContent;
				}
			} else {
				mfp.content = '';
			}

			_mfpTrigger(BEFORE_APPEND_EVENT);
			mfp.container.addClass('mfp-' + type + '-holder');

			mfp.contentContainer.append(mfp.content);
		},




		/**
		 * Create array of object with data on each item
		 */
		parseEl: function (index) {
			var item = mfp.items[index],
				type = item.type || 'image',
				data = $.extend(true, {}, mfp.st[type], item);

			if (item.tagName) {
				item.el = $(item);
			} else {
				item.el = mfp._getEl(type);
			}

			if (data.parseEl) {
				data = data.parseEl(index, item);
			}

			item.type = type;

			if (mfp.currItem === item) {
				mfp.currItem.parsed = true;
			}

			mfp.items[index] = item;
			_mfpTrigger('ElementParse', item);

			return mfp.items[index];
		},


		/**
		 * Initializes mfp with provided or all items
		 */
		addGroup: function (el, options) {
			var eHandler = function (e) {
				e.mfpEl = this;
				mfp._openClick(e, el, options);
			};

			if (!options) {
				options = {};
			}

			var eName = 'click.magnificPopup';
			options.mainEl = el;

			if (options.items) {
				options.isObj = true;
				el.off(eName).on(eName, eHandler);
			} else {
				options.isObj = false;
				if (options.delegate) {
					el.off(eName).on(eName, options.delegate, eHandler);
				} else {
					options.items = el;
					el.off(eName).on(eName, eHandler);
				}
			}
		},
		_openClick: function (e, el, options) {
			var midClick = options.midClick !== undefined ? options.midClick : $.magnificPopup.defaults.midClick;


			if (midClick || e.which !== 2 && !e.ctrlKey && !e.metaKey) {
				var disableOn = options.disableOn !== undefined ? options.disableOn : $.magnificPopup.defaults.disableOn;

				if (disableOn) {
					if ($.isFunction(disableOn)) {
						if (!disableOn.call(mfp)) {
							return true;
						}
					} else { // else it's number
						if (_window.width() < disableOn) {
							return true;
						}
					}
				}

				if (e.type) {
					e.preventDefault();

					// This will prevent popup from closing if element is inside and popup is already open
					if (mfp.isOpen) {
						e.stopPropagation();
					}
				}


				options.el = $(e.mfpEl);
				if (options.delegate) {
					options.items = el.find(options.selector);
				}
				mfp.open(options);
			}
		},


		/**
		 * Updates text on preloader
		 */
		updateStatus: function (status, text) {

			if (mfp.preloader) {
				if (_prevStatus !== status) {
					mfp.container.removeClass('mfp-s-' + _prevStatus);
				}

				if (!text && status === 'loading') {
					text = mfp.st.tLoading;
				}

				var data = {
					status: status,
					text: text
				};
				// allows to modify status
				_mfpTrigger('UpdateStatus', data);

				status = data.status;
				text = data.text;

				mfp.preloader.html(text);

				mfp.preloader.find('a').on('click', function (e) {
					e.stopImmediatePropagation();
				});

				mfp.container.addClass('mfp-s-' + status);
				_prevStatus = status;
			}
		},


		/*
			"Private" helpers that aren't private at all
		 */
		// Check to close popup or not
		// "target" is an element that was clicked
		_checkIfClose: function (target) {

			if ($(target).hasClass(PREVENT_CLOSE_CLASS)) {
				return;
			}

			var closeOnContent = mfp.st.closeOnContentClick;
			var closeOnBg = mfp.st.closeOnBgClick;

			if (closeOnContent && closeOnBg) {
				return true;
			} else {

				// We close the popup if click is on close button or on preloader. Or if there is no content.
				if (!mfp.content || $(target).hasClass('mfp-close') || (mfp.preloader && target === mfp.preloader[0])) {
					return true;
				}

				// if click is outside the content
				if ((target !== mfp.content[0] && !$.contains(mfp.content[0], target))) {
					if (closeOnBg) {
						// last (sometimes closes content when clicking in the middle of the screen)
						return true;
					}
				} else if (closeOnContent) {
					return true;
				}

			}
			return false;
		},
		_addClassToMFP: function (cName) {
			mfp.bgOverlay.addClass(cName);
			mfp.wrap.addClass(cName);
		},
		_removeClassFromMFP: function (cName) {
			this.bgOverlay.removeClass(cName);
			mfp.wrap.removeClass(cName);
		},
		_hasScrollBar: function (winHeight) {
			return ((mfp.fixedContentPos === 'auto' ? winHeight > _document.height() : !mfp.fixedContentPos) && _body.css('overflow') !== 'hidden');
		},


		/**
		 * Private vars
		 */
		_setFocus: function () {
			if (mfp.st.focus) {
				mfp.content.attr('tabindex', -1).focus();
			} else {
				mfp.wrap.focus();
			}
		},
		_onFocusIn: function (e) {
			if (e.target !== mfp.wrap[0] && !$.contains(mfp.wrap[0], e.target) && mfp._setFocus()) {
				return false;
			}
		},
		_parseMarkup: function (template, values, item) {
			var arr;
			if (item.data) {
				values = $.extend(item.data, values);
			}
			_mfpTrigger(MARKUP_PARSE_EVENT, [template, values, item]);

			$.each(values, function (key, value) {
				if (value === undefined || value === false) {
					return true;
				}
				arr = key.split('_');
				if (arr.length > 1) {
					var el = template.find(EVENT_NS + '-' + arr[0]);

					if (el.length > 0) {
						var attr = arr[1];
						if (attr === 'replaceWith') {
							if (el[0] !== value[0]) {
								el.replaceWith(value);
							}
						} else if (attr === 'img') {
							if (el.is('img')) {
								el.attr('src', value);
							} else {
								el.replaceWith('<img src="' + value + '" class="' + el.attr('class') + '" />');
							}
						} else {
							el.attr(arr[1], value);
						}
					}

				} else {
					template.find(EVENT_NS + '-' + key).html(value);
				}
			});
		},

		_getScrollbarSize: function () {
			// thx David
			if (mfp.scrollbarSize === undefined) {
				var scrollDiv = document.createElement("div");
				scrollDiv.id = "mfp-sbm";
				scrollDiv.style.cssText = 'width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;';
				document.body.appendChild(scrollDiv);
				mfp.scrollbarSize = scrollDiv.offsetWidth - scrollDiv.clientWidth;
				document.body.removeChild(scrollDiv);
			}
			return mfp.scrollbarSize;
		}

	}; /* MagnificPopup core prototype end */



	/**
	 * Public static functions
	 */
	$.magnificPopup = {
		instance: null,
		proto: MagnificPopup.prototype,
		modules: [],

		open: function (options, index) {
			_checkInstance();

			if (!$.isPlainObject(options)) {
				options = {};
			}
			if (index !== undefined) {
				options.items = options;
				options.delegate = index;
				options.index = 0;
			}

			// We use "isObj" check to see if it's jQuery collection
			if (options.items instanceof $) {
				options.items = options.toArray();
			}

			return $.magnificPopup.instance.open(options);
		},

		close: function () {
			if ($.magnificPopup.instance) {
				$.magnificPopup.instance.close();
			}
		},

		registerModule: function (name, module) {
			if (module.options) {
				$.magnificPopup.defaults[name] = module.options;
			}
			$.extend(this.proto, module.proto);
			this.modules.push(name);
		},

		defaults: {

			// Info about options is in docs:
			// http://dimsemenov.com/plugins/magnific-popup/documentation.html#options

			disableOn: 0,

			key: null,

			midClick: false,

			mainClass: '',

			preloader: true,

			focus: '', // CSS selector of input to focus after popup is opened

			closeOnContentClick: false,

			closeOnBgClick: true,

			closeBtnInside: true,

			showCloseBtn: true,

			enableEscapeKey: true,

			modal: false,

			alignTop: false,

			removalDelay: 0,

			fixedContentPos: 'auto',

			fixedBgPos: 'auto', // 'auto', true or false

			image: {
				tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
				titleSrc: function (item) {
					return item.el.attr('title') + '<small>by MarsMediaGroup</small>';
				}
			},

			gallery: {
				enabled: false,
				navigateByImgClick: true,
				preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
			},

			iframe: {
				markup: '<div class="mfp-iframe-scaler">' +
					'<div class="mfp-close"></div>' +
					'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
					'</div>',

				patterns: {
					youtube: {
						index: 'youtube.com/',
						id: 'v=',
						src: '//www.youtube.com/embed/%id%?autoplay=1'
					},
					vimeo: {
						index: 'vimeo.com/',
						id: '/',
						src: '//player.vimeo.com/video/%id%?autoplay=1'
					},
					gmaps: {
						index: '//maps.google.',
						src: '%id%&output=embed'
					}
				},

				srcAction: 'iframe_src', // Templating object key. Same as for <a> tag
			},

			ajax: {
				settings: null, // Ajax settings object that will extend default one - http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
				// For example:
				// settings: {cache:false, async:false}

				cursor: 'mfp-ajax-cur', // CSS class that will be added to body during the loading

				tError: '<a href="%url%">The content</a> could not be loaded.' //  Error message, can contain %curr% and %total% tags if gallery is enabled
			},

			callbacks: {

				beforeOpen: function () {
					// Used to trigger event before popup is open
					// this.st.callbacks.beforeOpen.call(this);
				},

				open: function () {
					// Will fire when popup is opened
				},
				// e.t.c (see list of callbacks below)
			},

			tClose: 'Close (Esc)', // Alt text on close button

			tLoading: 'Loading...'

		}
	};



	$.fn.magnificPopup = function (instanceOptions) {

		_checkInstance();

		var jqEls = this; // save to be able to return "chainability"

		if (jqEls.length < 1) {
			return jqEls;
		}

		var options = $.extend(true, {}, $.magnificPopup.defaults, instanceOptions);

		// As magnific popup can be initialized multiple times on same element, we define it only once.
		if (jqEls.data(NS)) {
			return jqEls; // already initialized
		}

		jqEls.data(NS, true);

		if (options.delegate) {
			// bind click event to all elements with this delegate
			jqEls.on('click' + EVENT_NS, options.delegate, function (e) {
				e.preventDefault();
				options.index = jqEls.index(this);
				mfp.open(options);
			});
		} else {
			// bind to self
			jqEls.on('click' + EVENT_NS, function (e) {
				e.preventDefault();
				options.index = 0;
				mfp.open(options);
			});
		}

		return jqEls;

	};



	/**
	 * List of available callbacks:
	 * 
	 * - `beforeOpen`
	 * - `open`
	 * - `beforeClose`
	 * - `close`
	 * - `afterClose`
	 * - `beforeAppend`
	 * - `markupParse`
	 * - `change`
	 * - `updateStatus`
	 * - `lazyLoad`
	 * - `ajaxContentAdded`
	 * - `imageHasSize`
	 * - `resize`
	 * 
	 * You can hook your custom function like so:
	 * 
	 * ```js
	 * $.magnificPopup.instance.myCustomMethod = function() {
	 *     // your code
	 * };
	 * ```
	 */


})(window.jQuery || window.Zepto);