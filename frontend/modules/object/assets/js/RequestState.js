(function ($, window, document, undefined) {
	var pluginName = "objectsStateWatcher",
		defaults = {
			afterChange: function (element, data) {
				return true;
			}
		};

	function Plugin(element, options) {
		this.element = $(element);
		this.items = {};
		this.settings = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	Plugin.prototype = {
		init: function () {
			if (!this.settings.module) {
				console.log('failed to init');
				return false;
			}
			this.startQuerier();
		},
		updateItems: function () {
			this.items = this.element.find('.objectState[data-module=' + this.settings.module + ']');
		},
		startQuerier: function () {
			var _this = this;
			setInterval(function () {
				_this.query();
			}, 8 * 1000);
			return this;
		},
		query: function () {
			var _this = this;
			this.updateItems();

			var data = [];
			$.each(this.items, function (i, v) {
				var id = $(v).data('id');
				if (id) data.push(id);
				$(v).data('prev_html', $(v).html());
			});

			if (!data.length) return false;

			$.ajax({
				url: '/' + this.settings.module + '/' + this.settings.module + "/requests-state",
				dataType: 'json',
				data: {ids: data},
				success: function (data) {
					$.each(_this.items, function (i, v) {
						if (!v) return false;
						var $v = $(v);
						var id = $v.data('id');
						var item = data[id];

						if (item['html']) {
							item['old_html'] = $('<div>').append($v.clone()).html();
							if (item['html'] != item['old_html']) {
								if (!_this.settings.afterChange(v, data)) return false;
							}
							$v.replaceWith(data[id]['html']);
						} else {
							/// Currently is not in use. Request always sends html attribute.
							_this.items.splice(i, 1);
							$v.text($v.data('norm_state')).removeData('norm_state');
						}
					});

					return true;
				}
			});

			return this;
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