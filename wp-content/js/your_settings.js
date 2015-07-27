jQuery(document).ready(function ($) {
    phone = $('input[name="Phone"]');
    email = $('input[name="Username"]');

    phone.parent().hide();

    email.attr('placeholder','_@_');
    //email after timezone
    var $email = $('input[name="user_email"]').parent('div.pure-control-group');
    var $time_zone = $(':input[name="Time zone"]').parent('div.pure-control-group');
    $time_zone.after($email);


});