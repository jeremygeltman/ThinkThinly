jQuery(document).ready(function ($) {
    phone = $('input[name="Phone"]');
    email = $('input[name="Username"]');

    phone.parent().hide();

    email.attr('placeholder','_@_');

});