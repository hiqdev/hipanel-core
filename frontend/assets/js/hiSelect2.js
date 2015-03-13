(function ($, window, document, undefined) {
	var pluginName = "hiSelect2",
		defaults = {};

	function Plugin(element, options) {
		this.form = $(element);
		this.fields = {};
		this.settings = $.extend({}, defaults, options);
		this._name = pluginName;
		this.init();
	}

	Plugin.prototype = {
		init: function () {
			return this;
		},
		add: function (element, type, options) {
			console.log(element, type, options);
			var field = $.fn.hiSelect2Config.get({
				'form': this,
				'type': type,
				'pluginOptions': options
			});
			element.select2(field.config());
			element.data('fieldConfig', field.config());
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

(function ($, window, document, undefined) {
	function Plugin(type) {
		this.init(type);
	}

	Plugin.prototype = {
		fields: {},
		init: function () {
			return this;
		},
		add: function (name, config) {
			if (this.exists(name)) {
				return this.get(name);
			} else {
				return this.fields[name] = config;
			}
		},
		get: function (name) {
			console.log(name, this.fields[name]);
			return this.exists(name) ? new Field(this.fields[name]) : {};
		},
		exists: function (name) {
			return this.fields[name] !== undefined;
		}
	};

	function Field(config) {
		this.name = null;
		this.type = null;
		this.config = null;
		this.pluginOptions = {};
		this.config = config;
		this.configure(config);
		this.init();
	}

	Field.prototype = {
		init: function () {
			return true;
		},
		configure: function (config) {
			var field = this;
			$.each(config, function (k, v) {
				if (field[k] !== undefined) {
					if (typeof v == 'Object') {
						$.extend(true, this[k], v);
					} else {
						this[k] = v;
					}
				} else {
					throw "Trying to set unknown property " + k;
				}
			});
			return true;
		},
		getName: function () {
			return this.name;
		},
		getType: function () {
			return this.type;
		}
	};

	$.fn['hiSelect2Config'] = function (type) {
		return new Plugin(type);
	};
})(jQuery, window, document);

$(document).ready(function () {
	$.fn.hiSelect2Config().add('client', {
		name: 'client',
		type: 'client',
		pluginOptions: {
			allowClear: true,
			initSelection: function (element, callback) {
				var data = {
					id: element.val(),
					text: element.attr('data-init-text') ? element.attr('data-init-text') : element.val()
				};

				callback(data);
			},
			ajax: {
				url: "/hosting/clients/clients/list",
				dataType: 'json',
				quietMillis: 400,
				data: function (term) {
					return {
						data: {
							client_like: term
						}
					};
				},
				results: function (data) {
					var ret = [];
					if (!data.error) {
						$.each(data, function (index, value) {
							ret.push({id: value, text: value});
						});
					}
					return {results: ret};
				}
			},
			onChange: function (e) {
				return false;
			},
			someTestEvent: function (e) {
				console.log('yeah!');
			}
		}
	});
});
