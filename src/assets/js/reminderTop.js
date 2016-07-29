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
            // this.updateReminderListener();
            this.deleteReminderListener();
        },

        // Get Reminders
        getRemindersListListener: function () {
            var _this = this;
            $(_this.element).on('show.bs.dropdown', function (ev) {
                _this.getRemindersList(ev);
            });
        },
        getRemindersList: function (ev) {
            var _this = this;
            var elem = $('li.reminder-body');
            $.ajax({
                url: _this.settings.listUrl,
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
            var _event = event;
            $('.reminder-action-update', _this.element).on('click', function (event) {
                var elem = $(this);
                var id = elem.data('reminder-id');
                var action = elem.data('reminder-action');
                _this.updateReminder(id, action)
            });
        },
        updateReminder: function (id, action) {
            $.ajax({
                url: this.settings.listUrl,
                data: {
                    'Reminder': {
                        'id': id,
                        'action': action,
                        'tz': this.clientTimeZone()
                    }
                },
                success: function (count) {
                    this.updateCounts(count);
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
        clientTimeZone: function () {
            return '';
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
