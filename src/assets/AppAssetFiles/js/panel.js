/**
 * Created by tofid on 25.02.15.
 */

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