(function ($, window, document, undefined) {
	var pluginName = "objectsStateWatcher",
		defaults = {
			propertyName: "value"
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
			this.items = this.element.find('.objectState[data-module=' + this.settings.module + ']');
			if (!this.settings.module) {
				console.log('failed to init');
				return false;
			}
			this.startQuerier();
		},
		startQuerier: function () {
			var _this = this;
			setInterval(function () {
				_this.query();
			}, 5000);
			return this;
		},
		query: function () {
			var _this = this;

			var data = [];
			$.each(this.items, function (i, v) {
				var id = $(v).data('id');
				if (id) data.push(id)
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
							$v.html(data[id]['html']);
						} else {
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