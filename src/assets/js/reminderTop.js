(function ($, window, document, undefined) {
    var pluginName = "reminder",
        defaults = {
            'listUrl': undefined,
            'deleteUrl': undefined,
            'updateUrl': undefined,
            'getCountUrl': undefined,
            'loaderTemplate': undefined
        };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function (event) {
            this.getRemindersListListener();
            this.updateReminderListener();
            this.deleteReminderListener();
        },

        // Get Reminders
        getRemindersListListener: function () {
            var _this = this;
            $(_this.element).one('show.bs.dropdown', function (ev) {
                _this.getRemindersList(ev);
            });
        },
        getRemindersList: function (ev) {
            var _this = this;
            var elem = $('li.reminder-body');
            $.ajax({
                url: _this.settings.listUrl,
                method: 'POST',
                data: {
                    'offset': _this.clientOffset()
                },
                beforeSend: function () {
                    elem.html(_this.settings.loaderTemplate);
                },
                success: function (data) {
                    elem.html(data);
                }
            });
        },

        // Delete reminder
        deleteReminderListener: function () {
            var _this = this;
            $(document).on('click', '.reminder-delete', function (ev) {
                ev.preventDefault();
                var elem = $(this);
                var id = elem.data('reminder-id');
                _this.deleteReminder(id);

                return false;
            });
        },
        deleteReminder: function (id) {
            var _this = this;
            $.ajax({
                url: _this.settings.deleteUrl,
                type: 'POST',
                data: {
                    'Reminder': {
                        'id': id
                    }
                },
                success: function () {
                    _this.updateCounts();
                    _this.getRemindersList();
                }
            });
        },

        // Update reminder
        updateReminderListener: function () {
            var _this = this;
            $(document).on('click', '.reminder-update', function (ev) {
                ev.preventDefault();
                var elem = $(this);
                var id = elem.data('reminder-id');
                var action = elem.data('reminder-action');
                _this.updateReminder(id, action)

                return false;
            });
        },
        updateReminder: function (id, action) {
            var _this = this;
            $.ajax({
                url: _this.settings.updateUrl,
                type: 'POST',
                data: {
                    'Reminder': {
                        'id': id,
                        'reminderChange': action,
                        'clientTimeZone': _this.clientOffset()
                    }
                },
                success: function (count) {
                    _this.updateCounts();
                    _this.getRemindersList();
                }
            });
        },

        // Other functions
        updateCounts: function () {
            $.ajax({
                url: this.settings.getCountUrl,
                dataType: 'json',
                success: function (data) {
                    $('.reminder-counts').text(data.count);
                }
            });
        },
        clientOffset: function () {
            return moment().utcOffset();
        }
    };

    $.fn[pluginName] = function (options) {
        this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
        return this;
    };
})(jQuery, window, document);
