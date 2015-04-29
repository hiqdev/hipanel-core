/**
 * Created by tofid on 25.02.15.
 */

/**
 * Scroll to element
 * @param element
 */
function scrollTo(element, duration) {
    duration = duration || 500;
    $('html, body').animate({
        scrollTop: $(element).offset().top
    }, duration);
}


