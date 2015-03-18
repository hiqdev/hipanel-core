(function ($, window, document, undefined) {
	var pluginName = "hiSelect2",
		defaults = {};

	function Plugin(element, options) {
		this.noextend = 1;
		this._name = pluginName;
		this.form = $(element);
		this.fields = [];
		this.settings = $.extend({}, defaults, options);
		this.init();
		return this;
	}

	Plugin.prototype = {
		init: function () {
			return this;
		},
		/**
		 * Registers the element in the hiSelect2 form handler
		 *
		 * @param {object} element the element's selector or the jQuery object with the element
		 * @param {string} type the type the field (according to the config storage)
		 * @param {object=} [options={}] the additional options that will be passed directly to the select2 config
		 * @returns {Plugin}
		 * @see $.fn.hiSelect2Config
		 */
		register: function (element, type, options) {
			options = options !== undefined ? options : {};
			if (typeof element == 'string') {
				element = this.form.find(element);
			}

			var field = $.fn.hiSelect2Config().get({
				'type': type,
				'form': this,
				'pluginOptions': options
			});
			field.element = element;
			element.select2(field.getConfig());
			element.data('field', field);
			this.fields.push({
				type: type,
				field: field,
				element: element
			});
			return this;
		},
		/**
		 * Generates filters
		 * @param fields Acceptable formats:
		 *
		 * A simple list of attributes
		 * ```
		 *  ['login', 'client', 'server']
		 * ```
		 *
		 * Array of relations between the returned key and requested field
		 * ```
		 *  {'login_like': 'login', 'type': 'type'}
		 * ```
		 *
		 * With custom format
		 * ```
		 *  {
		 *      'server_ids': {
		 *          field: 'server',
		 *          format: function (id, text, field) { return id; }
		 *      },
		 *      'client_ids': {
		 *          field: 'client',
		 *          format: 'id'
	     *      },
	     *      'extremely_unusual_filter': {
	     *          field: 'login',
	     *          format: function (id, text, field) {
	     *              return field.form.getValue('someOtherField') == '1';
	     *          }
	     *      },
	     *      'someStaticValue': {
	     *          format: 'test'
	     *      }
		 *  }
		 * ```
		 *
		 * @returns {{}} the object of generated filters
		 */
		createFilter: function (fields) {
			var _this = this;
			var filters = {};
			$.each(fields, function (k, v) {
				if (isNaN(parseInt(k)) === false) {
					k = v;
				}
				if (typeof v === 'string') {
					v = {field: v};
				}
				if (v.format == 'id') {
					v.format = function (id, text) {
						return id;
					};
				} else if (typeof v.field !== 'string' && typeof v.format === 'string') {
					/// If the result is a static value
					filters[k] = v.format;
					return true;
				} else if ($.isFunction(v.format) == false) {
					v.format = function (id, text) {
						return text;
					};
				}

				var field = _this.getField(v.field);
				var data = field.getData();
				filters[k] = v.format(data['id'], data['text'], field);
			});
			return filters;
		},
		getData: function (type) {
			return this.getField(type).getData();
		},
		getValue: function (type) {
			return this.getData(type).text;
		},
		getField: function (type) {
			var result = {};
			$.each(this.fields, function (k, v) {
				if (v.type == type) {
					result = v.field;
					return false;
				}
			});
			return result;
		},
		update: function (event) {
			var _this = this;
			//var event = new Event(originalEvent.element, originalEvent);
			$.each(this.fields, function (k, v) {
				if (v.element != event.element) {
					v.field.trigger('update', event);
				}
			});
		}
	};

	function Event(element, options) {
		this.element = element;
		this.options = options;
		this.init();
	}

	$.fn[pluginName] = function (options) {
		if (!$(this).data("plugin_" + pluginName)) {
			$(this).data("plugin_" + pluginName, new Plugin(this, options));
		}

		return $(this).data('plugin_' + pluginName);
	};
})(jQuery, window, document);

(function ($, window, document, undefined) {
	/**
	 * The plugin config storage
	 *
	 * @constructor
	 */
	function Plugin() {
		this.init();
	}

	Plugin.prototype = {
		fields: {},
		init: function () {
			return this;
		},
		/**
		 * Adds a field behaviors to the config storage.
		 *
		 * @param {(string|object)} type the type of the field or the array of fields
		 * @param {object=} config
		 * @returns {*}
		 */
		add: function (type, config) {
			var _this = this;
			if (config == undefined && typeof type == 'object') {
				$.each(type, function (k, v) {
					_this.add(k, v);
				});
			}

			if (this.exists(type)) {
				return this.get(type);
			} else {
				return this.fields[type] = config;
			}
		},
		/**
		 * Returns the requested config by the type, may extend the config with the user-defined
		 * @param {(string|object)} options
		 *    string - returns the stored config for the provided type
		 *    object - have to contain the `type` field with the type of the config
		 * @returns {*}
		 */
		get: function (options) {
			if (typeof options == 'string') {
				options['type'] = options;
			}
			return new Field(this.fields[options.type]).configure(options);
		},
		/**
		 * Checks whether the requested config type is registered
		 * @param {string} type the config type of the select2 field
		 * @returns {boolean}
		 */
		exists: function (type) {
			return this.fields[type] !== undefined;
		}
	};

	function Field(config) {
		this.noextend = 1;
		this.name = null;
		this.type = null;
		this.form = null;
		this.element = null;
		this.config = null;
		this.pluginOptions = {
			onChange: function (e) {
				return $(this).data('field').form.update(e);
			}
		};
		this.events = {};
		this.configure(config);
		this.init();
	}

	Field.prototype = {
		init: function () {
			return this;
		},
		configure: function (config) {
			var field = this;
			$.each(config, function (k, v) {
				if (field[k] !== undefined) {
					if (typeof field[k] == 'object' && v.noextend === undefined) {
						$.extend(true, field[k], v);
					} else {
						field[k] = v;
					}
				} else if (k.substr(0, 2) == 'on') {
					field.events[k.substr(3)] = v;
				} else {
					throw "Trying to set unknown property " + k;
				}
			});
			return this;
		},
		/**
		 * Returns the Select2 plugin options for the type
		 * @returns {object} the Select2 plugin options for the type
		 */
		getConfig: function () {
			return this.pluginOptions;
		},
		getName: function () {
			return this.name;
		},
		getType: function () {
			return this.type;
		},
		getData: function () {
			return this.element.select2('data');
		},
		trigger: function (name, e) {
			return $.isFunction(this.events[name]) ? this.events[name](e) : true;
		}
	};

	$.fn['hiSelect2Config'] = function (type) {
		return new Plugin(type);
	};
})(jQuery, window, document);
