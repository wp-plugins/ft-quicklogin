(function($) {
	$.fn.fqlBox = function(options) {
		var settings = $.extend({}, $.fn.fqlBox.defaults, options);

		var overlay;

		_init = function() {
			_add_overlay();
			_add_buttons();
			_add_focus_form();
			_check_for_message();

			if (document.getElementById('fql-bar')) {
				$("html").css('paddingTop', 40);
			}

			if (document.getElementById('admin-bar-css')) {
				$("#fql-bar").css('top', 28);
			}
		}

		_add_overlay = function() {
			$('body').append(
				overlay	= $('<div id="fql-overlay"></div>')
			);

			overlay.css({
				'opacity' : 0.8,
				'position' : 'absolute',
				'top' : 0,
				'left' : 0,
				'width' : '100%',
				'z-index' : 99999,
				'display' : 'none',
				'height' : $(document).height()
			});
		}

		_add_buttons = function() {
			$('a[rel=' + settings.buttonClose + '], .' + settings.buttonClose ).click(function() {
				_hide_box();
				_hide_all_sections();

				return false;
			});

			$('a[rel=' + settings.buttonLogin + '], a[title=' + settings.buttonLogin + '], .' + settings.buttonLogin ).click(function() {
				_show_box();
				_show_box_section('fql-box-login');

				document.location.href = '#';
				return false;
			});

			$('a[rel=' + settings.buttonRegister + '], a[title=' + settings.buttonRegister + '], .' + settings.buttonRegister ).click(function() {
				_show_box();
				_show_box_section('fql-box-register');

				document.location.href = '#';
				return false;
			});

			$('a[rel=' + settings.buttonLostpassword + '], a[title=' + settings.buttonLostpassword + '], .' + settings.buttonLostpassword ).click(function() {
				_show_box();
				_show_box_section('fql-box-lost-password');

				document.location.href = '#';
				return false;
			});

			var fql_box_links = $(settings.boxLinks, $(settings.boxID)).children();

			fql_box_links.each(function() {
				$(this).click(function() {
					_show_box_section( $(this).attr("rel") );
					return false;
				});
			});
		}

		_add_focus_form = function() {
			$('input[type="text"], input[type="password"]', $(settings.boxID)).each(function(){
				var defaultValue = $(this).attr("value");

				if(defaultValue != "") {
					$(this).focus(function() {
						if( defaultValue == $(this).attr("value") ) {
							$(this).attr("value", "");
						}
					}).blur(function() {
						if( $(this).attr("value") == "" ) {
							$(this).attr("value", defaultValue);
						}
					});
				}
			});
		}

		_check_for_message = function() {
			var $_GET = {};

			document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
				function decode(s) {
					return decodeURIComponent(s.split("+").join(" "));
				}
				$_GET[decode(arguments[1])] = decode(arguments[2]);
			});

			if($_GET['fql-message']) {
				_show_box();
				_show_box_section('fql-box-message');
			}
		}

		_show_overlay = function() {
			if (!overlay.is(':visible')) {
				overlay.fadeIn("fast");
			}
		}

		_hide_overlay = function() {
			if (overlay.is(':visible')) {
				overlay.hide();
			}
		}

		_show_box = function() {
			if (!$(settings.boxID).is(':visible')) {
				_show_overlay();
				$(settings.boxID).fadeIn("fast");
			}
		}

		_hide_box = function() {
			if ($(settings.boxID).is(':visible')) {
				_hide_overlay();
				$(settings.boxID).hide();
			}
		}

		_show_box_section = function(section) {
			_hide_all_sections();

			if (!$('#' + section, $(settings.boxID)).is(':visible')) {
				$('#' + section, $(settings.boxID)).show();
			}
		}
		
		_hide_box_section = function(section) {
			if ($('#' + section, $(settings.boxID)).is(':visible')) {
				$('#' + section, $(settings.boxID)).hide();
			}
		}

		_hide_all_sections = function() {
			_hide_box_section('fql-box-login');
			_hide_box_section('fql-box-register');
			_hide_box_section('fql-box-lost-password');
			_hide_box_section('fql-box-message');
		}

		_init();
	};

	$.fn.fqlBox.defaults = {
		boxID: '#fql-box',
		loginBoxID: '#fql-box-login',
		registerBoxID: '#fql-box-register',
		lostpasswordBoxID: '#fql-box-lost-password',
		messageBoxID: '#fql-box-lost-message',

		boxLinks: '.fql-box-links',

		buttonClose: 'fql-close',
		buttonLogin: 'fql-login',
		buttonRegister: 'fql-register',
		buttonLostpassword: 'fql-lost-password'
	};
})(jQuery);

(function($) {
	$.fn.fqlNews = function(options) {
		var settings = $.extend({}, $.fn.fqlNews.defaults, options);

		if( $(settings.newsID).length == 0 ) return false;

		var t = setTimeout(function() { _run(); }, settings.hold);

		var vars = {
			currentSlide: 0,
			totalSlides: 0,
			nextSlide: 1,
			count: 0
		};

		var kids = $(settings.newsID).children();
		
		vars.totalSlides = kids.length;
		
		$("li", $(settings.newsID)).hide();
		$("li:eq(0)", $(settings.newsID)).show();

		$(settings.newsID).hover(function () {
			clearTimeout(t);
		}, function () {
			clearTimeout(t);
			t = setTimeout(function() { _run(); }, settings.hold);
		});

		function _run() {
			vars.nextSlide = vars.currentSlide + 1;

			if(vars.nextSlide > vars.totalSlides - 1 )
				vars.nextSlide = 0;

			if(vars.nextSlide < 0 )
				vars.nextSlide = vars.totalSlides - 1;

			//alert('tavicu');
			$('li:eq('+ vars.currentSlide +')', $(settings.newsID)).fadeToggle(300, function() {
				$('li:eq('+ vars.nextSlide +')', $(settings.newsID)).fadeToggle(300);
			});;

			vars.currentSlide = vars.nextSlide;

			clearTimeout(t);
			t = setTimeout(function() { _run(); }, settings.hold);
		}
	};
	
	$.fn.fqlNews.defaults = {
		hold: 3000,
		newsID: '#fql-bar #fql-news'
	};
})(jQuery);

jQuery(function(){
	jQuery().fqlBox();
	jQuery().fqlNews();
});