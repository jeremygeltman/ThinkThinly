/**
 * Created by tri on 6/16/15.
 */
jQuery(document).ready(function ($) {
    username = $('input[name="Username"]');
    password = $('input[name="User_Password"]');
    confirmPassword = $('input[name="Confirm_User_Password"]');
    phone = $('input[name="Phone"]');
    submit_btn = $('#ewd-feup-register-form input[type="submit"]');

    /* @var subscribe_phone jQuery */
    subscribe_phone = $('input[name="subscribe_phone"]');
    /* @var subscribe_gender jQuery */
    subscribe_gender = $(':input[name="subscribe_gender"]');
    subscribe_btn = $('#subscribe_btn');
    gender = $(':input[name="Gender"]');


    phone.keyup(function (event) {
        var pVal = phone.val();
        /* @pVal string */

        username.val(pVal);
        password.val(btoa(pVal.substring(5,10)));
        confirmPassword.val(password.val());
    });
    subscribe_phone.keyup(function(event){
       var pVal = $(this).val();
        phone.val(pVal);
        username.val(pVal);
        password.val(btoa(pVal.substring(5,10)));
        confirmPassword.val(password.val());
    });

    subscribe_gender.change(function(event){
        var gVal = $(this).val();
        gender.val(gVal);
    });
    subscribe_btn.click(function(){
        submit_btn.click();
    });

    $(":input").inputmask();

});