(function ($, window, document, undefined) {
	var pluginName = "osSelector",
		defaults = {
			panelInput: '.reinstall-panel',
			osimageInput: '.reinstall-osimage'
		};

	function Plugin(element, options) {
		this.element = $(element);
		this.items = {};
		this.oslist = undefined;
		this.softlist = undefined;
		this.osparams = options.osparams;
		this.settings = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	Plugin.prototype = {
		init: function () {
			this.oslist = this.element.find('.os-list');
			this.softlist = this.element.find('.soft-list');
			this.bindListeners();
			this.afterInit();
			this.enableInfoPopover();
		},
		bindListeners: function () {
			var _this = this;

			this.softlist.find('input').on('change', function () {
				var soft = $(this).val();
				var panel = $(this).data('panel');
				var os = _this.oslist.find('input.radio:checked').first().val();
				$(_this.settings.osimageInput).val(_this.osparams[os]['panel'][panel]['softpack'][soft]['osimage']);
				if (panel != 'no') {
					$(_this.settings.panelInput).removeAttr('disabled');
					$(_this.settings.panelInput).val(panel);
				} else $(_this.settings.panelInput).attr('disabled', 'disabled');
			});

			$(this.oslist).on('change', function () {
				_this.update();
			});
		},
		update: function () {
			var _this = this;

			if (this.oslist.find('.radio:enabled:checked').length < 1) this.oslist.find('.radio:enabled').first().prop('checked', true);
			this.oslist.find('.radio:enabled').each(function () {
				if ($(this).prop('checked')) {
					_this.softlist.find('input').attr('disabled', 'disabled');
					_this.softlist.find('input').closest('label').addClass('disabled');
					_this.softlist.find('.softinfo-bttn').hide();

					var osName = $(this).val();
					for (var key in _this.osparams) {
						if (osName == key) {
							for (var panel in _this.osparams[key]['panel']) {
								for (var soft in _this.osparams[key]['panel'][panel]['softpack']) {
									var selectable = _this.osparams[key]['panel'][panel]['softpack'][soft];
									var $inputs = _this.softlist.filter('[data-panel="' + panel + '"]');
									if (selectable) {
										var $selectable_inputs = $inputs.find('input[value="' + soft + '"]');
										var soft_desc = selectable['html_desc'];

										$selectable_inputs.removeAttr('disabled');
										$selectable_inputs.closest('label').removeClass('disabled');
										if (soft_desc) {
											$selectable_inputs.closest('label').find('.soft-desc').html(soft_desc);
											$selectable_inputs.closest('label').find('.softinfo-bttn').show();
										}
									}
								}
							}
						}
					}
					if (_this.softlist.find('input:enabled:checked').length == 0) {
						_this.softlist.find('input:enabled').first().attr('checked', 'checked');
					}
					_this.softlist.find('input:checked').trigger('change').trigger('click');
				}
			});
		},
		afterInit: function () {
			this.oslist.find('input:enabled').first().attr('checked', 'checked').trigger('change');
		},
		enableInfoPopover: function () {
			var counter;

			$('.softinfo-bttn').popover({
				trigger: 'manual',
				html: true,
				title: function () {
					return $(this).parent().find('.panel_soft').val();
				},
				content: function () {
					return $(this).parent().find('.soft-desc').html();
				},
				container: 'body',
				placement: 'auto'
			}).on("mouseenter", function () {
				var _this = this;
				// clear the counter
				clearTimeout(counter);
				// Close all other Popovers
				$('.softinfo-bttn').not(_this).popover('hide');

				// start new timeout to show popover
				counter = setTimeout(function () {
					if ($(_this).is(':hover')) {
						$(_this).popover("show");
					}
					$(".popover").on("mouseleave", function () {
						$('.thumbcontainer').popover('hide');
					});
				}, 400);

			}).on("mouseleave", function () {
				var _this = this;

				setTimeout(function () {
					if (!$(".popover:hover").length) {
						if (!$(_this).is(':hover')) {
							$(_this).popover('hide');
						}
					}
				}, 200);
			});
		}

	};

	$.fn[pluginName] = function (options) {
		this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName, new Plugin(this, options));
			}
		});
		return this;
	};
})(jQuery, window, document);