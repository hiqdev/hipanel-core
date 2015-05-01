(function ($, window, document, undefined) {
	var pluginName = "combo2",
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
		 * Registers the element in the combo2 form handler
		 *
		 * @param {object} element the element's selector or the jQuery object with the element
		 * @param {string} type the type the field (according to the config storage)
		 * @param {object=} [options={}] the additional options that will be passed directly to the select2 config
		 * @returns {Plugin}
		 * @see $.fn.Combo2Config
		 */
		register: function (element, type, options) {
			options = options !== undefined ? options : {};
			if (typeof element == 'string') {
				element = this.form.find(element);
			}
			var field = $.fn.combo2Config().get({
				'type': type,
				'form': this,
				'pluginOptions': options
			});
			element.data('field', field);
			field.setElement(element).attachListeners();
			this.fields.push({
				type: type,
				field: field,
				element: element
			});
			this.update(new Event(element, {force: 1}));
			return this;
		},
		setValue: function (type, data) {
			return this.getField(type).setValue(data);
		},
		getData: function (type) {
			var field = this.getField(type);
			if ($.isEmptyObject(field)) return {};

			return $.extend(true, {}, this.getField(type).getData());
		},
		getId: function (type) {
			var data = this.getData(type);
			return !$.isEmptyObject(data) ? data.id : '';
		},
		getValue: function (type) {
			var data = this.getData(type);
			return !$.isEmptyObject(data) ? data.text : '';
		},
		getListeners: function (type) {
			var listeners = [];
			$.each(this.fields, function (k, v) {
				if (v.type == type) {
					listeners.push(v);
				}
			});
			return listeners;
		},
		isSet: function (type) {
			return this.getId(type).length > 0;
		},
		disable: function (type, clear) {
			if (clear) this.clear(type);
			return this.getField(type).disable();
		},
		enable: function (type, clear) {
			if (clear) this.clear(type);
			return this.getField(type).enable();
		},
		isEnabled: function (type) {
			return this.getField(type).isEnabled();
		},
		clear: function (type) {
			return this.getField(type).clear();
		},
		isEmpty: function (type) {
			return this.getValue(type) == '';
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
		hasField: function (type) {
			return !$.isEmptyObject(this.getField(type));
		},
		areSet: function (names) {
			var isSet = true;
			var _this = this;
			if (typeof names === 'string') {
				names = [names];
			}
			$.each(names, function (k, v) {
				if (!isSet) {
					return false;
				}
				if ($.isFunction(v)) {
					isSet = v(_this);
				} else {
					isSet = _this.isSet(v);
				}
			});
			return isSet;
		},
		updateAffected: function (event) {
			var _this = this;
			var updated_field = event.element.data('field');
			var data = $.extend(true, {}, event.added);
			if (!updated_field.affects || $.isEmptyObject(data)) return this;

			$.each(updated_field.affects, function (k, v) {
				var field = _this.getField(k);
				if ($.isEmptyObject(field)) return true;
				var keys = {};

				if (typeof v == 'string') {
					keys = {id: v + '_' + field.getPk(), value: v};
				} else if ($.isFunction(v)) {
					keys = v(updated_field);
				} else {
					keys = v;
				}

				var id = data[keys.id];
				var value = data[keys.value];
				field.setData({id: id, value: value});
			});
			return this;
		},
		/**
		 * Updates select2 states, trigger 'update' trigger for each field
		 * @param event
		 */
		update: function (event) {
			var _this = this;
			var element = event.element;
			var reUpdate = false;

			if (!event.noAffect && (event.added || event.removed)) {
				this.updateAffected(event);
			}

			$.each(this.fields, function (k, v) {
				if (reUpdate) return false;
				var field = v.field;
				var isActive = true;
				var needsClear = false;

				if (v.element[0] == element[0] && !event.force) return true;

				if (field.activeWhen) {
					if ($.isFunction(field.activeWhen)) {
						isActive = field.activeWhen(field.name, _this);
					} else {
						isActive = _this.areSet(field.activeWhen);
					}
				}

				if (field.clearWhen && !event.noAffect) {
					if ($.isFunction(field.clearWhen)) {
						needsClear = field.clearWhen(field.name, _this);
					} else {
						needsClear = !_this.areSet(field.clearWhen);
					}
					needsClear = needsClear || field.clearWhen.indexOf(element.data('field').type) >= 0;
				}

				if (isActive != _this.isEnabled(field.type)) {
					reUpdate = true;
				}
				if (needsClear && !_this.isEmpty(field.type)) {
					reUpdate = true;
				}

				if (isActive) {
					_this.enable(field.type);
				} else {
					_this.disable(field.type, true);
				}

				if (needsClear) {
					_this.clear(field.type);
				}

				field.trigger('update', event);
			});

			if (reUpdate) return this.update(event);
		}
	}
	;

	function Event(element, options) {
		this.element = element;
		this.options = $.extend(true, {}, options);
		this.force = options.force;
	}

	$.fn[pluginName] = function (options) {
		if (!$(this).data("plugin_" + pluginName)) {
			$(this).data("plugin_" + pluginName, new Plugin(this, options));
		}

		return $(this).data('plugin_' + pluginName);
	};
})
(jQuery, window, document);

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
		 * @param {string} type the type of the field
		 * @param {object=} config
		 * @returns {*}
		 */
		add: function (type, config) {
			return this.fields[type] = config;
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
		this.activeWhen = null;
		/**
		 * The array of fields, cleaning of which makes this field cleared too.
		 * @type {array}
		 */
		this.clearWhen = null;
		/**
		 * The object-array of fields, that may be affected after the current field update
		 * The key is the type of the field to be affected
		 * For example:
		 *
		 * ```
		 *   {
		 *      affects: {
		 *          'client': 'client',
		 *          'server': function (field) {
		 *              return {id: field.id, value: field.text};
		 *          }
		 *      }
		 *   }
		 * ```
		 * @type {object}
		 */
		this.affects = null;

		/**
		 * Whether the field has an ID. Used by [[initSelection]]
		 * @type {boolean|string}
		 */
		this.hasId = true;

		this.pluginOptions = {
			placeholder: 'Enter a value',
			allowClear: true,
			initSelection: function (element, callback) {
				var field = element.data('field');
				var callback_trigger = function (data) {
					var oldData = field.getData();
					callback(data);
					field.triggerChange({
						added: data,
						removed: oldData,
						noAffect: true
					});
				};

				if (field.hasId && element.data('init-text')) {
					var text = element.data('init-text');
					element.removeData('init-text');
					callback_trigger({id: element.val(), text: text});
				} else if (field.hasId) {
					var requestData = {};
					requestData[field.getPk()] = {format: element.val()};
					requestData = field.createFilter(requestData);

					$.ajax({
						url: field.pluginOptions.ajax.url,
						method: 'post',
						data: requestData,
						success: function (data) {
							callback_trigger(data[0]);
						}
					});
				} else {
					text = element.val();
					callback_trigger({id: text, text: text});
				}
			},
			ajax: {
				dataType: 'json',
				quietMillis: 400,
				results: function (data) {
					var ret = [];
					$.each(data, function (k, v) {
						ret.push(v);
					});
					return {results: ret};
				}
			},
			onChange: function (e) {
				e.element = $(this);
				if (e.noAffect) {
					e.stopPropagation();
				}

				return $(this).data('field').form.update(e);
			},
			'onSelect2-selecting': function (event) {
				var field = $(event.target).data('field');
				var data = event.object;
				if (field.getPk()) {
					data.id = data[field.getPk()];
				} else {
					data.id = data.text;
				}
			}
		}
		;
		this.events = {};
		this.configure(config);
		this.init();
	}

	Field.prototype = {
		init: function () {
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
		 *  {'login_like': 'hosting/account', 'type': 'type'}
		 * ```
		 *
		 * With custom format
		 * ```
		 *  {
		 *      'server_ids': {
		 *          field: 'server/server',
		 *          format: function (id, text, field) { return id; }
		 *      },
		 *      'client_ids': {
		 *          field: 'client/client',
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
	     *      },
	     *      'return': ['id', 'value']
		 *  }
		 * ```
		 *
		 * @returns {{}} the object of generated filters
		 */
		createFilter: function (fields) {
			var form = this.form;
			var filters = {};

			if (!fields.return) fields['return'] = this.pluginOptions.ajax.return;
			if (!fields.rename) fields['rename'] = this.pluginOptions.ajax.rename;
			if (this.pluginOptions.ajax.filter) {
				$.extend(true, fields, this.pluginOptions.ajax.filter);
			}

			$.each(fields, function (k, v) {
				if (isNaN(parseInt(k)) === false) {
					k = v;
				}
				if (typeof v === 'string') {
					v = {field: v};
				} else if ($.isArray(v) || (typeof v === 'object' && v.format === undefined)) {
					v = {format: v};
				}

				if (v.format === 'id') {
					v.format = function (id, text) {
						return id;
					};
				} else if (typeof v.field !== 'string' && (typeof v.format === 'string' || $.isArray(v.format) || typeof v.format === 'object')) {
					/// If the result is a static value - just set and skip all below
					filters[k] = v.format;
					return true;
				} else if ($.isFunction(v.format) == false) {
					v.format = function (id, text) {
						return text;
					};
				}

				var field = form.getField(v.field);
				if ($.isEmptyObject(field)) return true;
				var data = field.getData();
				if (!data) return true;

				filters[k] = v.format(data['id'], data['text'], field);
			});
			return filters;
		},
		configure: function (config) {
			var field = this;
			$.each(config, function (k, v) {
				if (field[k] !== undefined) {
					if (typeof field[k] == 'object' && v.noextend === undefined && field[k] !== null) {
						$.extend(true, field[k], v);
					} else {
						field[k] = v;
					}
				} else if (k.substr(0, 2) == 'on') {
					field.events[k.substr(2)] = v;
				} else {
					throw "Trying to set unknown property " + k;
				}
			});
			return this;
		},
		setElement: function (element) {
			this.element = element;
			element.select2(this.getConfig());
			return this;
		},
		attachListeners: function () {
			var element = this.element;
			$.each(this.getConfig(), function (k, v) {
				if (k.substr(0, 2) == 'on') {
					element.on(k.substr(2).toLowerCase(), v);
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
		setData: function (data, triggerChange) {
			var setValue;
			if (typeof data !== 'string') {
				this.element.data('init-text', data.value);
				setValue = data.id ? data.id : data.value;
			} else {
				setValue = data;
			}

			this.setValue(setValue, triggerChange);

			return this;
		},
		setValue: function (data, triggerChange) {
			return this.element.select2('val', data, triggerChange);
		},
		getValue: function () {
			return this.element.select2('val');
		},
		trigger: function (name, e) {
			return $.isFunction(this.events[name]) ? this.events[name](e) : true;
		},
		disable: function () {
			return this.element.select2('enable', false);
		},
		enable: function () {
			return this.element.select2('enable', true);
		},
		isEnabled: function () {
			return this.element.data('select2').isInterfaceEnabled();
		},
		clear: function () {
			return this.setValue('');
		},
		isEmpty: function () {
			return this.getValue() === '';
		},
		getPk: function () {
			if (this.hasId === true) {
				return 'id';
			} else if (this.hasId === 'string') {
				return this.hasId;
			} else {
				return false;
			}
		},
		triggerChange: function (options) {
			var data = $.extend(true, {
				'added': this.getData()
			}, options);
			return this.element.data('select2').triggerChange(data);
		}
	};

	$.fn['combo2Config'] = function (type) {
		return new Plugin(type);
	};
})
(jQuery, window, document);
