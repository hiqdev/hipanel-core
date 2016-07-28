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
            this.updateRemindersListListener(event);
            // this.updateReminderListener(event);
            // this.deleteReminderListener(event);
        },
        updateRemindersListListener: function (event) {
            var _this = this;
            var _event = event;
            $(this.element).on('click', function (event) {
                _this.updateRemindersList(event);
            });
        },
        updateRemindersList: function (event) {
            var _this = this;
            var _event = event;
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
        updateListener: function (event) {
            var _this = this;
            var _event = event;
            $('.reminder-action-update', _this.element).on('click', function (event) {
                var elem = $(this);
                var id = elem.data('reminder-id');
                var action = elem.data('reminder-action');
                $.ajax({
                    url: _this.settings.listUrl,
                    data: {
                        'Reminder': {
                            'id': id,
                            'action': action,
                            'tz': _this.clientTimeZone()
                        }
                    },
                    success: function (count) {
                        _this.updateCounts(count);
                    }
                });
            });
        },
        deleteListener: function (event) {
            var _this = this;
            var _event = event;
            $('.reminder-action-delete').on('click', function (event) {
                var elem = $(this);
                var id = elem.data('reminder-id');
                event.preventDefault();
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
                    }
                });

                return false;
            });
        },
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
