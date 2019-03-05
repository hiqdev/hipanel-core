/**
 * Scroll to element
 * @param element
 */
function scrollTo(element, duration) {
    duration = duration || 500;
    if (!element) return false;
    var elem = $(element).offset();
    if (elem.top) {
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
