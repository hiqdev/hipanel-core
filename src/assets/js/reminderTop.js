(function ($, window, document, undefined) {
    var pluginName = "reminder",
        defaults = {
            'listUrl': undefined,
            'deleteUrl': undefined,
            'updateUrl': undefined
        };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function () {
            this.bindListeners();
        },
        bindListeners: function () {
            var _this = this;
        },
        query: function (url) {
            $.ajax({
                url: url,
                type: 'POST',
                data: this.prepareQueryData(),
                success: this.processQuery.bind(this)
            });
        },
        prepareQueryData: function () {

        },
        processQuery: function (data) {
            if (data.html) {
                this.element.html(data.html);
            }
        },
        getReminders: function () {

        },
        deleteRreminder: function (id) {
            
        },
        updateReminder: function (id) {
            
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
