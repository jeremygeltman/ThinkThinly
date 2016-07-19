function auto_timezone() {
    //brian3t defaults timezone:
    var timezone_select = $('select[name="Time zone"]');

    if (user_time_zone){
        timezone_select.val(user_time_zone);//default from database
    }

    if (timezone_select.val() === "Please select" || timezone_select.val() == undefined || timezone_select.val() == null) {
        var hours_offset = -(new Date().getTimezoneOffset() / 60);
        //Brian3t assuming daylight saving time
        //In the future, create cronjob to update hours offset when daylight savings is in effect
        switch (hours_offset) {
            case -7:
                timezone_val = 'PST';
                break;
            case -6:
                timezone_val = 'MST';
                break;
            case -5:
                timezone_val = 'CST';
                break;
            case -4:
                timezone_val = 'EST';
                break;
        }
        timezone_select.val(timezone_val);
    }
    ////brian3t defaults timezone:
}
$(document).ready(function () {
        auto_timezone();
        var regx = "/[a-zA-Z()]/g";
        var word = $('#ewd-feup-register-input-14').val();
        if (typeof word !== "undefined" && word !== null) {
            word = word.replace(eval(regx), '');
            setCookie('words', word, 365);


            if ($('#ewd-feup-register-input-7').attr('rel') && ($('#ewd-feup-register-input-7').val() !== 'select'))
                $('#ewd-feup-register-input-7').val($('#ewd-feup-register-input-7').attr('rel'));

            if ($('#ewd-feup-register-input-8').attr('rel') && ($('#ewd-feup-register-input-8').val() !== 'select'))
                $('#ewd-feup-register-input-8').val($('#ewd-feup-register-input-8').attr('rel'));

            if ($('#ewd-feup-register-input-9').attr('rel') && ($('#ewd-feup-register-input-9').val() !== 'select'))
                $('#ewd-feup-register-input-9').val($('#ewd-feup-register-input-9').attr('rel'));

//                    if (document.URL.indexOf("#loaded") == -1) {
//                        if (!window.location.hash) {
//                            window.location = window.location + '#loaded';
//                            window.location.reload();
//                        }
//                        //location.reload(true);
//                    }
        }
    }
)
