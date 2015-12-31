<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Try_Now( $atts )
{
    // Include the required global variables, and create a few new ones
    global $wpdb, $post, $user_message, $feup_success;
    global $ewd_feup_fields_table_name;

    global $redirect_field, $redirect_array_string, $redirect_page;

    $Custom_CSS        = get_option("EWD_FEUP_Custom_CSS");
    $Salt              = get_option("EWD_FEUP_Hash_Salt");
    $Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
    $Time              = time();

    $Sql    = "SELECT * FROM $ewd_feup_fields_table_name ORDER BY Field_Order";
    $Fields = $wpdb->get_results($Sql);

    $ReturnString = "";

    if ( isset($_GET['ConfirmEmail']) ) {
        ConfirmUserEmail();
    }

    // Get the attributes passed by the shortcode, and store them in new variables for processing
    extract(shortcode_atts(array(
                               'redirect_page' => '#',
                               'redirect_field' => "",
                               'redirect_array_string' => "",
                               'submit_text' => __('Register', 'EWD_FEUP')
                           ),
                           $atts
            )
    );

    if ( isset($_GET['ConfirmEmail']) ) {
        $ConfirmationSuccess = ConfirmUserEmail();
    }

    if ( $feup_success and $redirect_field != "" ) {
        $redirect_page = Determine_Redirect_Page($redirect_field, $redirect_array_string, $redirect_page);
    }

    if ( $feup_success and $redirect_page != '#' ) {
        //brian autologin
        session_start();
        $_SESSION['Username'] = $_REQUEST['Username'];
        $_SESSION['User_Password'] = $_REQUEST['User_Password'];

        FEUPRedirect($redirect_page);
    }

    $ReturnString .= "<style type='text/css'>";
    $ReturnString .= $Custom_CSS;
    $ReturnString .= "</style>";

    if ( ! isset($ConfirmationSuccess) ) {
        $ReturnString .= "<div id='ewd-feup-register-form-div'>";
        $ReturnString .=
<<<HTML
<p class="terms_cond"><i>Get 3 days of motivational messages free!</i></p>
HTML;

        if ( isset($user_message['Message']) ) {
            $ReturnString .= "<span id='signup_error_message' class='error-message'>" . $user_message['Message'] . "</span>";
        }
        $ReturnString .= "<form action='#' name='ewd-feup-register-form' method='post' id='ewd-feup-register-form' class='pure-form pure-form-aligned' enctype='multipart/form-data' data-toggle='validator' novalidate>";
        $ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time . $Salt)) . "'>";
        $ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
        $ReturnString .= "<input type='hidden' name='ewd-feup-action' value='register'>";
        $ReturnString .= "<input type='hidden' name='ewd-feup-post-id' value='" . $post->ID . "'>";
        $ReturnString .= '
        <div class="pure-control-group">
        <label for="Phone" id="ewd-feup-register-4"
                                               class="ewd-feup-field-label">Phone: </label><input name="Phone"
                                                                                                  id="ewd-feup-register-input-4"
                                                                                                  class="ewd-feup-text-input pure-input-1-3"
                                                                                                  type="tel"
                                                                                                  placeholder="Mobile"
                                                                                                  data-inputmask="\'mask\': \'9999999999\'"
                                                                                                  required=""></div>
        <div class="pure-control-group"><label for="Gender" id="ewd-feup-register-11" class="ewd-feup-field-label" required>Gender: </label><select
                name="Gender" id="ewd-feup-register-input-11" class="ewd-feup-select pure-input-1-3" required>
                <!--<option value="">Gender</option> -->
                <option value="Female" selected>Female</option>
                <option value="Male">Male</option>
                <!--<option value="Other">Other</option>-->
            </select></div>


';
        $ReturnString .= "<div class='pure-control-group hidden'>";
        if ( $Username_Is_Email == "Yes" ) {
            $ReturnString .= "<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label'>Hidden username</label>";
            if ( isset($_POST['Username']) ) {
                $ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' required=false value='" . $_POST['Username'] . "'>";
            } else {
                $ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' required=false placeholder='" . __('Email',
                                                                                                                      'EWD_FEUP') . "...'>";
            }
        } else {
            $ReturnString .= "<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label'>Hidden username</label>";
            if ( isset($_POST['Username']) ) {
                $ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' required=false value='" . $_POST['Username'] . "'>";
            } else {
                $ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' required=false placeholder='" . __('Username',
                                                                                                                     'EWD_FEUP') . "...'>";
            }
        }
        $ReturnString .= "</div>";
        $ReturnString .= "<div class='pure-control-group hidden'>";
        $ReturnString .= "<label for='Password' id='ewd-feup-register-password-div' class='ewd-feup-field-label'>" . __('Password',
                                                                                                                        'EWD_FEUP') . ": </label>";
        if ( isset($_POST['User_Password']) ) {
            $ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password' value='" . $_POST['User_Password'] . "'>";
        } else {
            $ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password'>";
        }
        $ReturnString .= "</div>";
        $ReturnString .= "<div class='pure-control-group hidden'>";
        $ReturnString .= "<label for='Repeat Password' id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label'>" . __('Repeat Password',
                                                                                                                                       'EWD_FEUP') . ": </label>";
        if ( isset($_POST['Confirm_User_Password']) ) {
            $ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password' value='" . $_POST['Confirm_User_Password'] . "'>";
        } else {
            $ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password'>";
        }
        $ReturnString .= "</div>";

        ?>


        <?php
        $ReturnString .= "<span class='ipad_fix'></span><div class='pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit pure-button pure-button-primary' name='Register_Submit' value='" . "Try It Now" . "'></div>";
        $ReturnString .= "</form>";
        $ReturnString .= '                        <div class="terms_cond">
                            I accept the <a href="/terms-and-conditions" target="_blank">Terms and Conditions</a>
                        </div>';
        $ReturnString .= "</div>";
    } else {
        $ReturnString = "<div class='ewd-feup-email-confirmation'>";
        if ( $ConfirmationSuccess == "Yes" ) {
            $ReturnString .= __("Thanks for confirming your e-mail address!", 'EWD_FEUP');
        }
        if ( $ConfirmationSuccess == "No" ) {
            $ReturnString .= __("The confirmation number provided was incorrect. Please contact the site administrator for assistance.",
                                'EWD_FEUP');
        }
        $ReturnString .= "</div>";
    }
    wp_enqueue_script('inputmask',"/wp-content/js/inputmask.min.js","jquery",false,true);
    wp_enqueue_script('jquery_inputmask',"/wp-content/js/jquery.inputmask.min.js","inputmask",false,true);

    wp_enqueue_script(
        'trynow-script',
        '/wp-content/js/trynow.js',
        array( 'jquery' )
    );

    return $ReturnString;
}
add_shortcode("trynow", "Try_Now");

?>
