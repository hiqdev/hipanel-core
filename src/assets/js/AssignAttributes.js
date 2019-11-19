(function ($, window, document, undefined) {
    var pluginName = "assignHubs",
        defaults = {
            countModels: 1,
            attributes: [],
            formName: '',
            linkText: '',
        };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.attributes = this.settings.attributes;
        this.formName = this.settings.formName;
        this.linkText = this.settings.linkText;
        this.init();
    }

    Plugin.prototype = {
        init: function () {
            if (this.settings.countModels > 1) {
                this.getKeysArray().forEach(el => this.registerItems(el))
            }
        },
        registerItems: function (index) {
            const indexAttribute = this.formName + '-' + index;
            this.attributes.forEach(attribute => {
                const itemAttribute = indexAttribute + '-' + attribute + '_id';
                const $attributeBlock = $(`#${itemAttribute}`);

                const $applyAllLink = this.getApplyLink(index, attribute);
                $applyAllLink.on('click', event => {
                    this.getKeysArray().forEach(el => {
                        const value = $attributeBlock.children().last().clone();
                        const iterable = $(`#${this.formName}-${el}-${attribute}_id`);
                        iterable.empty();
                        iterable.append(value);
                    });
                    $(event.target).remove();
                });

                $attributeBlock.on('change', function(event) {
                    $applyAllLink.insertAfter($(`.field-${itemAttribute}`));
                });
            });
        },
        getApplyLink: function (index, attribute) {
            const $applyAllLink = $(`<a href="#">${this.linkText}</a>`);
            $applyAllLink.css('position', 'absolute');
            $applyAllLink.addClass(`apply-all-${index}-${attribute}`);

            return $applyAllLink;
        },
        getKeysArray: function () {
            const _countModels = this.settings.countModels;
            return [...Array(_countModels).keys()];
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
