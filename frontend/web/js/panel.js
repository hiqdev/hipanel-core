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

$( ".sidebar-toggle" ).on("click", function() {
    var collapsed_sidebar = 1;
    if ($('body').hasClass('sidebar-collapse')) {
        collapsed_sidebar = 0;
    }
    $.post( "/skin/collapsed-sidebar", {collapsed_sidebar: collapsed_sidebar});
});


