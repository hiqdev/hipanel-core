(function ($, window, document, undefined) {
    let pluginName = "applyToAll",
        defaults = {
            countModels: 1,
            attributes: [],
            formName: '',
            linkText: '',
        };

    function getKeysArray(countModels) {
        return [...Array(countModels).keys()];
    }

    function ApplyLink(settings, index, attribute) {
        this.index = index;
        this.attribute = attribute;
        this.settings = settings;
    }
    ApplyLink.prototype = {
        create: function ($attributeBlock) {
            const $applyAllLink = $(`<a href="#">${this.settings.linkText}</a>`);
            $applyAllLink.css('position', 'absolute');
            $applyAllLink.addClass(`apply-all-${this.index}-${this.attribute}`);

            $applyAllLink.on('click', event => {
                getKeysArray(this.settings.countModels).forEach(el => {
                    let value = $attributeBlock.children().last().clone();
                    if (value === 0) {
                        value = $('<option value></option>');
                    }
                    const iterable = $(`#${this.settings.formName}-${el}-${this.attribute}_id`);
                    iterable.empty();
                    iterable.append(value);
                });
                $(event.target).addClass('hidden');
            });

            return $applyAllLink;
        },
        getLink: function ($attributeBlock) {
            const searchedLink = $(`.apply-all-${this.index}-${this.attribute}`);
            if (searchedLink.length > 0) {
                searchedLink.removeClass('hidden');
                return searchedLink;
            }
            return this.create($attributeBlock);
        },
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
                getKeysArray(this.settings.countModels).forEach(el => this.registerItems(el))
            }
        },
        registerItems: function (index) {
            const _this = this;
            const indexAttribute = this.formName + '-' + index;
            this.attributes.forEach(attribute => {
                const itemAttribute = indexAttribute + '-' + attribute + '_id';
                const $attributeBlock = $(`#${itemAttribute}`);

                $attributeBlock.on('change', function(event) {
                    const applyLinkObject = new ApplyLink(_this.settings, index, attribute);
                    const $_applyLink = applyLinkObject.getLink($attributeBlock);
                    $_applyLink.insertAfter($(`.field-${itemAttribute}`));
                });
            });
        },
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
