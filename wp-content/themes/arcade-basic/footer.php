<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the main and #page div elements.
 *
 * @since 1.0.0
 */
$bavotasan_theme_options = bavotasan_theme_options();
?>
</main><!-- main -->


</div><!-- #page -->

<?php wp_footer(); ?>
<?php echo $uri = str_replace('/', '', $_SERVER['REQUEST_URI']);
if ( $uri == 'your-settings' ) {
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        function setCookie(c_name, value, exdays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value = escape(value) +
                ((exdays == null) ? "" : ("; expires=" + exdate.toUTCString()));
            document.cookie = c_name + "=" + c_value;
        }

        function getCookie(c_name) {
            var i, x, y, ARRcookies = document.cookie.split(";");
            for (i = 0; i < ARRcookies.length; i++) {
                x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
                y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
                x = x.replace(/^\s+|\s+$/g, "");
                if (x == c_name) {
                    return unescape(y);
                }
            }
        }

        $(document).ready(function () {
                //brian3t defaults timezone:
                var hours_offset = -(new Date().getTimezoneOffset() / 60);
                var timezone_select = $('select[name="Time zone"]');
                var timezone_val = 'Please select';
                //,,,,,(MST) Mountain Standard Time UTC-07,,
                switch (hours_offset) {
                    case -8:
                        timezone_val = '(PST) Pacific Standard Time UTC-08';
                        break;
                    case -4:
                        timezone_val = '(EDT) Eastern Daylight Time UTC-04';
                        break;
                    case -5:
                        timezone_val = '(EST) Eastern Standard Time  UTC-05';
                        break;
                    case -6:
                        timezone_val = '(CST) Central Standard Time  UTC-06';
                        break;
                    case -7:
                        timezone_val = '(PDT) Pacific Daylight Time UTC-07';
                        break;
                    case -11:
                        timezone_val = '(SST) Samoa Standard Time UTC-11';
                        break;
                }
                timezone_select.val(timezone_val);
                ////brian3t defaults timezone:
                var regx = "/[a-zA-Z()]/g";
                var word = $('#ewd-feup-register-input-14').val();
                if (typeof word !== "undefined") {
                    word = word.replace(eval(regx), '');
                    setCookie('words', word, 365);


                    if ($('#ewd-feup-register-input-7').attr('rel') && ($('#ewd-feup-register-input-7').val() !== 'select'))
                        $('#ewd-feup-register-input-7').val($('#ewd-feup-register-input-7').attr('rel'));

                    if ($('#ewd-feup-register-input-8').attr('rel') && ($('#ewd-feup-register-input-8').val() !== 'select'))
                        $('#ewd-feup-register-input-8').val($('#ewd-feup-register-input-8').attr('rel'));

                    if ($('#ewd-feup-register-input-9').attr('rel') && ($('#ewd-feup-register-input-9').val() !== 'select'))
                        $('#ewd-feup-register-input-9').val($('#ewd-feup-register-input-9').attr('rel'));

                    if (document.URL.indexOf("#loaded") == -1) {
                        if (!window.location.hash) {
                            window.location = window.location + '#loaded';
                            window.location.reload();
                        }
                        //location.reload(true);
                    }
                }
            }
        )
        ;

    </script>
<?php } ?>
</body>
</html>
