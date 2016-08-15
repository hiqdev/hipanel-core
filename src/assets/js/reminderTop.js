(function ($, window, document, undefined) {
    var pluginName = "reminder",
        defaults = {
            'listUrl': undefined,
            'deleteUrl': undefined,
            'updateUrl': undefined,
            'getCountUrl': undefined,
            'loaderTemplate': undefined,
            'updateInterval': 3 * 1000 // 1 minute
        };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.intervalId = null;
        this.init();
    }

    Plugin.prototype = {
        init: function () {
            var _this = this;
            this.getRemindersListListener();
            this.updateReminderListener();
            this.deleteReminderListener();
            this.updateGridColumnListener();
            this.intervalId = setInterval(function () {
                _this.updateCounts();
            }, this.settings.updateInterval);
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
                method: 'POST',
                data: {
                    'offset': _this.clientUtcOffset()
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
                _this.updateReminder(id, action);

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
                        'clientTimeZone': _this.clientUtcOffset()
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
                    var reminderCounts = $('.reminder-counts');
                    if (data.count > 0) {
                        if (reminderCounts.hasClass('hidden')) {
                            reminderCounts.removeClass('hidden');
                        }
                        reminderCounts.text(data.count);
                    } else {
                        reminderCounts.addClass('hidden');
                    }
                }
            });
        },
        clientUtcOffset: function () {
            return moment().utcOffset(); // minutes
        },
        updateGridColumnListener: function () {
            var _this = this, gridTable = $('#bulk-reminder-search table');
            if (gridTable.length) {
                $(document).on('ready pjax:end', gridTable, function () {
                    _this.updateGridColumn();
                });
            } else {
                _this.updateGridColumn();
            }
        },
        updateGridColumn: function () {
            var elem = $('.reminder-next-time-modify');
            if (elem.length) {
                $(elem).filter(function () {
                    var gridCell = $(this);
                    gridCell.text(moment.utc(gridCell.text()).local().format('DD.MM.YY, HH:mm'))
                });
            }
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
