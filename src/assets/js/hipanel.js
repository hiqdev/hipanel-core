var Hipanel = (function () {

    var publicMethods = {
        updateCart: function (callback) {
            $('.dropdown.notifications-cart a.dropdown-toggle').html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
            $.get("/cart/cart/topcart", function(data) {
                $("li.dropdown.notifications-menu.notifications-cart").html( data );
            }).done(callback());
        }
    };

    return publicMethods;

})();