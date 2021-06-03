(function ($, window, document, undefined) {
    let pluginName = "refSearch",
        defaults = {

        };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.attributes = this.settings.attributes;

        this.init();
    }
    Plugin.prototype = {
        init: function () {
            const elemTemplate = $('div .active .row');

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
