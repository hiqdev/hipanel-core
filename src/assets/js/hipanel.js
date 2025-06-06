window.hipanel = (function () {
  var locale = null;

  var notify = {
    create: function (options) {
      options = $.extend({
        buttons: {
          sticker: false,
        },
        icon: false,
        styling: "bootstrap3",
      }, options, true);

      return new PNotify(options);
    },
    error: function (text) {
      return notify.create({
        type: "error",
        text: text,
      });
    },
    success: function (text) {
      return notify.create({
        type: "success",
        text: text,
      });
    },
  };

  var publicMethods = {
    updateCart: function (callback) {
      $(".dropdown.notifications-cart a.dropdown-toggle").html("<i class=\"fa fa-refresh fa-spin fa-lg\"></i>");
      fetch("/cart/cart/topcart")
        .then(response => response.text())
        .then(html => {
          $("li.dropdown.notifications-menu.notifications-cart").html(html);
        }).then(callback);
    },
    loadingBar: function (options) {
      options = $.extend({}, options, true);

      return "<div class=\"progress\">" +
        "<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\">" +
        "<span class=\"sr-only\">" + options.text + "</span>" +
        "</div>" +
        "</div>";
    },
    notify: notify,
    form: {
      preventSubmitWithEnter: function (formId) {
        $(formId).find("input").on("keyup keypress", function (e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
            e.preventDefault();
            e.stopPropagation();
            $(e.target).blur();
          }
        });
      },
    },
    spinner: {
      small: function () {
        return $("<i class=\"fa fa-refresh fa-spin fa-fw\"></i>");
      },
    },
    locale: {
      get: function () {
        return locale;
      },
      set: function (newLocale) {
        locale = newLocale;
      },
    },
    googleAnalytics: function ($element, options) {
      $element.on("click", () => {
        ga("send", "pageview", "/virtual/" + options.category + "/" + options.action);
      });
    },
    progress: function (url, withCallback) {
      if (!window.EventSource || !url) {
        console.error("EVENTSOURCE ERROR");
        return;
      }
      const eventSource = new EventSource(url);
      if (withCallback) {
        withCallback(eventSource);
      }

      return {
        onMessage: function (callback) {
          eventSource.onmessage = function (event) {
            callback(event, eventSource);
          };
        },
        onError: function (callback) {
          eventSource.onerror = function (event) {
            callback(event, eventSource);
          };
        }
      };
    },
    runProcess: function (url, data = {}, onBeforeSend, onAfterSend, timeout = 2000) {
      $.ajax({
        url: url,
        method: "POST",
        data: data,
        beforeSend: function (xhr) {
          if (onBeforeSend) {
            onBeforeSend(xhr);
          }
        },
        complete: function (jqXHR, textStatus) {
          if (textStatus === 'success' && onAfterSend) {
            setTimeout(onAfterSend, timeout);
          }
        },
      });
    }
  };

  return publicMethods;
})();
