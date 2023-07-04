(function ($, window, document, undefined) {
  let pluginName = "applyToAll",
    defaults = {
      countModels: 1,
      attributes: [],
      formName: "",
      linkText: "",
    };

  function ApplyLink(settings, index, attribute) {
    this.index = index;
    this.attribute = attribute;
    this.settings = settings;
  }

  ApplyLink.prototype = {
    create: function ($attributeBlock) {
      const $applyAllLink = $(`<a href="#">${this.settings.linkText}</a>`);
      // $applyAllLink.css('position', 'absolute');
      $applyAllLink.addClass(`apply-all-${this.index}-${this.attribute}`);

      $applyAllLink.on("click", event => {
        event.preventDefault();
        $(`#${this.settings.formId} .item`).each((index, el) => {
          let value = $attributeBlock.children().last().clone();
          if (value === 0) {
            value = $("<option value></option>");
          }
          const iterable = $(`#${this.settings.formName}-${index}-${this.attribute}_id`);
          iterable.empty();
          iterable.append(value);
        });
        $(event.target).addClass("hidden");
      });

      return $applyAllLink;
    },
    getLink: function ($attributeBlock) {
      const searchedLink = $(`.apply-all-${this.index}-${this.attribute}`);
      if (searchedLink.length > 0) {
        searchedLink.removeClass("hidden");
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
    this.formId = this.settings.formId;
    this.formName = this.settings.formName;
    this.linkText = this.settings.linkText;
    this.init();
  }

  Plugin.prototype = {
    init: function () {
      let formBlocks = $(`#${this.formId} .item`);
      if (formBlocks.length > 1) {
        formBlocks.each((index, el) => this.registerItems(index));
      }
    },
    registerItems: function (index) {
      const indexAttribute = this.formName + "-" + index;
      this.attributes.forEach(attribute => {
        const itemAttribute = indexAttribute + "-" + attribute + "_id";
        const $attributeBlock = $(`#${itemAttribute}`);

        $attributeBlock.on("change", event => {
          this.hideAttributeLinks(attribute);
          const applyLinkObject = new ApplyLink(this.settings, index, attribute);
          const $_applyLink = applyLinkObject.getLink($attributeBlock);
          $_applyLink.insertAfter($(`.field-${itemAttribute}`));
        });
      });
    },
    hideAttributeLinks: function (attribute) {
      $(`a[class*="apply-all-"][class*="-${attribute}"]`).addClass("hidden");
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
