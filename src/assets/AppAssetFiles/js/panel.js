/**
 * Scroll to element
 * @param element
 *
 * This global declaration shadows window.scrollTo, so third-party code calling scrollTo(x, y) or
 * scrollTo({top}) lands here too (e.g. the Deep Chat widget) — ignore arguments that aren't elements.
 */
function scrollTo(element, duration) {
    duration = duration || 500;
    if (!element) return false;
    var elem = $(element).offset();
    if (elem && elem.top) {
        $('html, body').animate({
            scrollTop: elem.top
        }, duration);
    }
}

(function () {
    try {
        var tooltipElementsWhitelist = $.fn.tooltip.Constructor.DEFAULTS.whiteList
        tooltipElementsWhitelist.table = []
        tooltipElementsWhitelist.thead = []
        tooltipElementsWhitelist.tbody = []
        tooltipElementsWhitelist.tr = []
        tooltipElementsWhitelist.td = []
        tooltipElementsWhitelist.kbd = []
    } catch (e) {}
})();
