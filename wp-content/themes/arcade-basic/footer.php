
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
   
    
    
    <section id="layout1-prefooter">
        <div class="container">
            <div class="row">
                
                <div class="col-md-4 col-sm-12 about">
                    <a href="http://thinkthinly.com" class="footer-logo" title="instasent"><img src="<?php bloginfo('url');?>//wp-content/uploads/2016/02/ThinkThinly-logo-v4-inverse-text-retina.png" alt="logo" /></a>
                    <p>Motivation to work out harder.</p>
                    <ul class="social">
                        <li><a href="https://www.facebook.com/thinkthinly" title="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com/think_thinly" title="twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="https://www.pinterest.com/thinkthinly/" title="pinterest" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                        <!-- <li><a href="" title="instagram"><i class="fa fa-instagram"></i></a></li> -->
                    </ul>
                </div>
                
                <div class="col-md-2 col-sm-4 col-xs-6 col-md-offset-2 col-sm-offset-0 features">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="https://www.myfooddiary.com/?aID=1722" title="distributors">MyFoodDiary.com</a></li>
                        <li><a href="https://www.myfooddiary.com/?aID=1722&amp;source=lnk">Calorie Counter</a></li>
                        <li><a href="/attributions">Attributions</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 col-sm-4 col-xs-6 company">
                    <h4>ThinkThinly</h4>
                    <ul>
                        <li><a href="/signin" title="not-logged-in-jag" >Sign in</a></li>
                        <li><a href="/account-expired" title="subscribe">Get ThinkThinly</a></li>
                        <li><a href="/your-settings" title="logged-in-jag"> Settings</a></li>
                    </ul>   
                </div>
                
                <div class="col-md-2 col-sm-4 col-xs-6 company">
                    <p class="contact">
                        <i class="fa fa-envelope-o"></i><a href="mailto:thinkthinly@gmail.com">Get in touch</a>
                    </p>
                </div>
                
            </div>
            
        </div>
    </section>
    
    
    <footer id="layout1-footer">
        <div class="container">
            <div class="row bottom-footer">
                            
                <div class="col-sm-6 col-xs-12">
                    <p>© 2016 ThinkThinly.com. All rights are reserved.</p>
                </div>
                
                <div class="col-sm-6 col-xs-12">
                    <ul class="pull-right">
                        <li><a href="terms-and-conditions/">Terms and conditions</a></li>
                    </ul>
                </div>
                
            </div>
            
        </div>
    </footer>
    
    
    <div id="contact-popup" class="mfp-hide">
        
        <form action="#">               
            <a href="" class="close-btn">X</a>      
            <h2>Drop us your message</h2>
            <input type="text" class="form-control" placeholder="Your name">
            <input type="email" class="form-control" placeholder="Email Adress">
            <textarea class="form-control" placeholder="Your Message"></textarea>
            <div class="holder clearfix">
                <button type="submit" class="submit-btn pull-right">Submit <i class="fa fa-envelope-o"></i></button>
            </div>
            
        </form>
        
    </div>
    
    
    <script src="<?php bloginfo('template_directory');?>/folia/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/wow.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.nav.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.scrollto.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.easing.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.parallax-1.1.3.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/owl.carousel.min.js" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/google-maps.js" type="text/javascript"></script>
    
    <script src="<?php bloginfo('template_directory');?>/folia/js/style-options.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/main.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return}js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));
    </script>



</div><!-- #page -->

<?php wp_footer(); ?>
<?php echo $uri = str_replace('/', '', $_SERVER['REQUEST_URI']);
if ($uri == 'your-settings') {
    ?>
<!--    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
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
                var timezone_select = $('select[name="Time zone"]');
                if (timezone_select.val() === "Please select") {
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
        ;

    </script>
<?php } ?>
</body>
</html>
