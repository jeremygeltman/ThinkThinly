<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Edit_Profile($atts)
{
    /** @var string $redirect_page
     * @var string $login_page
     * @var string $Time
     * @var string $Salt
     * @var string $omit_fields
     */
    // Include the required global variables, and create a few new ones
    global $wpdb, $user_message, $feup_success;
    global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;

    $Custom_CSS = get_option("EWD_FEUP_Custom_CSS");

    $CheckCookie = CheckLoginCookie();

    $Sql      = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY `FIELD_ORDER` ASC";
    $Fields   = $wpdb->get_results($Sql);
    $User     = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'",
                                              $CheckCookie['Username']));
    $UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'",
                                                  $User->User_ID));

    $ReturnString = "";

    // Get the attributes passed by the shortcode, and store them in new variables for processing
    extract(shortcode_atts(array(
                               'redirect_page' => '#',
                               'login_page' => '',
                               'omit_fields' => '',
                               'submit_text' => __('Save settings', 'EWD_FEUP')
                           ),
                           $atts
            )
    );

    $ReturnString .= "<style type='text/css'>";
    $ReturnString .= $Custom_CSS;
    $ReturnString .= "</style>";

    if ($CheckCookie['Username'] == "") {
        $ReturnString .= __('You must be logged in to access this page.', 'EWD_FEUP');
        if ($login_page != "") {
            $ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login',
                                                                                                          'EWD_FEUP') . "</a> " . __('to continue.',
                                                                                                                                     'EWD_FEUP');
            if (isset($_SESSION['user_name_changed']) && isset($_SESSION['message_count']) && ! empty($_SESSION['user_name_changed']) && $_SESSION['message_count'] > 0) {
                $ReturnString .= "<p class='text-success'>Your email has been changed. Please use your new email for logging in.</p><br/>";
                $_SESSION['message_count'] --;

            }

        }

        return $ReturnString;
    }

    if ($feup_success and $redirect_page != '#') {
        FEUPRedirect($redirect_page);
    }

    $ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
    if (isset($user_message['Message'])) {
        $ReturnString .= "<div class='updated'><p>" . $user_message['Message'] . "</p></div>";
    }
    session_start();
    if (isset($_SESSION['first_sms_sent_to']) && ! empty($_SESSION['first_sms_sent_to']) && isset($_SESSION['message_count']) && $_SESSION['message_count'] > 0) {
        $ReturnString .= "<br/><p class='text-success'>Welcome to ThinkThinly. A message has been sent to your number " . $_SESSION['first_sms_sent_to'] . "</p>";
        $_SESSION['message_count'] --;
    }
    session_start();
    if (isset($_SESSION['user_updated'])) {
        $ReturnString .= "<br/><p class='updated'>" . $_SESSION['user_updated'] . "</p>";
        unset($_SESSION['user_updated']);
    }
    $ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='pure-form pure-form-aligned' enctype='multipart/form-data'>";

    //fields
    //5: First Name; 0: Breakfast; 1: Lunch; 2: Dinner; 3: I need the most help...; 4: Select your time zone; 5: First Name; 6:Last Name; 7: Gender; 8: OK to receive texts?; 9: Phone;

    $ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time . $Salt)) . "'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-profile'>";
    $ReturnString .= "<input type='hidden' name='Omit_Fields' value='" . $omit_fields . "'>";

    $Omitted_Fields = explode(",", $omit_fields);

    //Brian added. Force new username and password if user has just signed up
    $username   = $User->Username;
    $user_email = $User->user_email;
    if (strpos($username, "@foobar.com") != false) {
        $username = "";
    }
    $ReturnString .= '<div class="pure-control-group hidden">';
    $ReturnString .= '<label for="Username">Phone number: </label>';
    $ReturnString .= '<input type="text" class="ewd-feup-text-input" name="Username" value="' . $username . '" required>';
    $ReturnString .= '</div>';

    if (empty($username)) {
        $ReturnString .= '<div class="pure-control-group">';
        $ReturnString .= '<label for ="User_Password">Password: </label>';
        $ReturnString .= '<input type = "password" class="ewd-feup-text-input" name = "User_Password" value = "" required>';
        $ReturnString .= '</div>';
        $ReturnString .= '<div class="pure-control-group">';
        $ReturnString .= '<label for="Confirm_User_Password" > Repeat Password: </label>';
        $ReturnString .= '<input type = "password" class="ewd-feup-text-input" name = "Confirm_User_Password" value = "" >';
        $ReturnString .= '</div>';
    }
    ////Brian added force new username
    //fixing data before display
    //5: First Name; 0: Breakfast; 1: Lunch; 2: Dinner; 3: I need the most help...; 4: Select your time zone; 5: First Name; 6:Last Name; 7: Gender; 8: OK to receive texts?; 9: Phone;

    $print_field = function ($Field) use ($Omitted_Fields, $UserData, &$ReturnString) {
        $display_label = $Field->Field_Name;
        if ($display_label == "Breakfast") {
            $display_label = "Time 1";
        }
        if ($display_label == "Lunch") {
            $display_label = "Time 2";
        }
        if ($display_label == "Dinner") {
            $display_label = "Time 3";
        }
        if ($display_label == "Select your time zone") {
            $display_label = "Time Zone";
        }

        if (! in_array($Field->Field_Name, $Omitted_Fields)) {
            if ($Field->Field_Required == "Yes") {
                $Req_Text = "required";
            }
            $Value = "";

            if ($Field->Field_Name == "Phone") {
                $Field->Field_Type = "tel";
            }

            foreach ($UserData as $UserField) {
                if ($Field->Field_Name == $UserField->Field_Name) {
                    $Value = $UserField->Field_Value;
                }
            }
            if ($Field->Field_Name == "I need the most help...") {
                $ReturnString .= '<hr/>';
            }
            $ReturnString .= "<div class='pure-control-group'>";
            $ReturnString .= "<label for='" . $Field->Field_Name . "' id='ewd-feup-edit-" . $Field->Field_ID . "' class='ewd-feup-field-label'>" . __($display_label,
                                                                                                                                                      'EWD_FEUP') . ": </label>";
            if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input pure-input-1-3' type='text' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "tel") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input pure-input-1-3' type='tel' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "date") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input pure-input-1-3' type='date' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "datetime") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-datetime-input pure-input-1-3' type='datetime-local' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "textarea") {
                $ReturnString .= "<textarea name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-textarea pure-input-1-2' " . $Req_Text . ">" . $Value . "</textarea>";
            } elseif ($Field->Field_Type == "file") {
                $ReturnString .= __("Current file:", 'EWD_FEUP') . " " . substr($Value, 10) . " | ";
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input pure-input-1-3' type='file' value='' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "select") {
                $Options               = explode(",", $Field->Field_Options);
                $bkend_calculated_time = '';


                if (empty($Value)) {
                    switch ($Field->Field_Name) {
                        case 'Breakfast':
                            $Value = '12:00pm';
                            break;
                        case 'Lunch':
                            $Value = 'None set';
                            break;
                        case 'Dinner':
                            $Value = 'None set';
                            break;
                        default:
                            break;

                    }
                }

                $ReturnString .= "<select rel='" . $bkend_calculated_time . "' name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-select pure-input-1-3'>";

                foreach ($Options as $Option) {

                    $ReturnString .= "<option value='" . $Option . "' ";
                    if (trim($Option) == trim($Value)) {
                        $ReturnString .= "selected='selected'";
                    }
                    $ReturnString .= ">" . $Option . "</option>";
                }
                $ReturnString .= "</select>";
            } elseif ($Field->Field_Type == "radio") {
                $Counter = 0;
                $Options = explode(",", $Field->Field_Options);
                foreach ($Options as $Option) {
                    if ($Counter != 0) {
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top'><label class='pure-radio'></label>";
                    }
                    $ReturnString .= "<input type='radio' name='" . $Field->Field_Name . "' value='" . $Option . "' class='ewd-feup-radio' " . $Req_Text . " ";
                    if (trim($Option) == trim($Value)) {
                        $ReturnString .= "checked";
                    }
                    $ReturnString .= ">" . $Option;
                    $Counter ++;
                }
            } elseif ($Field->Field_Type == "checkbox") {
                $Counter = 0;
                $Options = explode(",", $Field->Field_Options);
                $Values  = explode(",", $Value);
                foreach ($Options as $Option) {
                    if ($Counter != 0) {
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top'><label class='pure-radio'></label>";
                    }
                    $ReturnString .= "<input type='checkbox' name='" . $Field->Field_Name . "[]' value='" . $Option . "' class='ewd-feup-checkbox' " . $Req_Text . " ";
                    if (in_array($Option, $Values)) {
                        $ReturnString .= "checked";
                    }
                    $ReturnString .= ">" . $Option . "</br>";
                    $Counter ++;
                }
            }
            $ReturnString .= "</div>";
            unset($Req_Text);

            if ($Field->Field_Name == "I need the most help...") {
                $ReturnString .= '<hr/>';
            }

        }
    };

    //5: First Name; 0: Breakfast; 1: Lunch; 2: Dinner; 3: I need the most help...; 4: Select your time zone; 5: First Name; 6:Last Name; 7: Gender; 8: OK to receive texts?; 9: Phone;
    $ReturnString .= '<div id="your_settings">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#tab_one" data-toggle="tab">Account</a></li>
        <li><a href="#tab_two" data-toggle="tab">Reminder times</a></li>
        <li '. (($User->subscription == "active")?' class="hidden" ':'') . '><a href="#tab_three" data-toggle="tab">Subscription</a></li>
        <li><a href="#tab_four" data-toggle="tab">Summary</a></li>
    </ul>
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="tab_one">';
    $ReturnString .= $print_field($Fields[5]);
    $ReturnString .= $print_field($Fields[6]);
    $ReturnString .= '<div class="pure-control-group">';
    $ReturnString .= '<label for="user_email">Email: </label>';
    $ReturnString .= '<input type="email" class="ewd-feup-text-input pure-input-1-3" name="user_email" value="' . $user_email . '" >';
    $ReturnString .= '</div>';

    $ReturnString .= '<div class="pure-control-group">';
    $ReturnString .= '<label for ="User_Password">Set new password (leave this field empty if you don\'t want to change your password): </label>';
    $ReturnString .= '<input type = "password" class="ewd-feup-text-input" name = "User_Password" value = "" >';
    $ReturnString .= '</div>';
    $ReturnString .= '<div class="pure-control-group">';
    $ReturnString .= '<label for="Confirm_User_Password" > Repeat Password: </label>';
    $ReturnString .= '<input type = "password" class="ewd-feup-text-input" name = "Confirm_User_Password" value = "" >';
    $ReturnString .= '</div>';
    $ReturnString .= '
        </div>
        <div class="tab-pane" id="tab_two">
            Tell us what times you need motivational boost and we\'ll text you then.
            <br/>Pick up to three times.  (Hint: You can choose meal times, workout times, late afternoon cravings or any time you need encouragement.)';
    $ReturnString .= $print_field($Fields[0]);
    $ReturnString .= $print_field($Fields[1]);
    $ReturnString .= $print_field($Fields[2]);
    $ReturnString .= $print_field($Fields[4]);

    $ReturnString .= '
        </div>
        <div class="tab-pane" id="tab_three">
            <h3>Lasting change starts here.</h3>
Get motivational texts every day up to three times a day daily for just $4.99 a month.<br/>
That\'s the cost of one cup of coffee.
                              Cancel any time.<br/>
                              <br/>
                              <button id="add_membership" type="button"> Yes, please send me messages! </button>
        </div>
        <div class="tab-pane" id="tab_four">
            <h3>You did it!</h3>
            The new you is on its way, one motivational message at a time.
            <h4>Settings</h4>
            ';

    $ReturnString .= $print_field($Fields[0]);
    $ReturnString .= $print_field($Fields[1]);
    $ReturnString .= $print_field($Fields[2]);
    $ReturnString .= $print_field($Fields[4]);
    $ReturnString .= '<div class="pure-control-group">';
    $ReturnString .= '<label for="Username">Phone number: </label>';
    $ReturnString .= '<input type="text" disabled="disabled" class="ewd-feup-text-input" name="Username" value="' . $username . '" required>';
    $ReturnString .= '</div>';
            $ReturnString .= '</div>        ';

//    print_field($Field, $Omitted_Fields, $UserData, $ReturnString);

    //brian3t extract $FIELDS here
    //insert form for paypal
    $userdata_ = array();
    foreach ($UserData as $user_data) {
        $user_data_[$user_data->Field_Name] = $user_data;
    }
    $expiry_date = $user_data_["Membership Expiry Date"]->Field_Value;
    $output      = "";
//    $output      = '<hr/>';
//    $output .= <<<HTML
//    <div class="pure-control-group"><label for="Membership Expiry Date" id="ewd-feup-edit-16" class="ewd-feup-field-label">Expires:
//        </label><span class="form_text">$expiry_date
//        </span><input name="Membership Expiry Date" value="$expiry_date" id="ewd-feup-register-input-16" type="hidden">
//        <label>
//        </label><span class="form_text">Extend membership by <input name="qty" size=1 value=1> months
//        </span>
//        <label>
//        </label><button type="button" id="add_membership">Go</button>
//
//    </div>
//HTML;
//    $output .= '<hr/>';
    $output .= "<script> var \$user_id= $User->User_ID ; </script>";
    $ReturnString .= ($output);

    ////brian3t extract form field add paypal

    /** @var string $submit_text */
    $ReturnString .= "<div class='pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit pure-button pure-button-primary' name='Edit_Profile_Submit' value='" . $submit_text . "'></div>";


    $ReturnString .= "</form>";
    $ReturnString .= "</div>";


    wp_enqueue_script('inputmask', "/wp-content/js/inputmask.min.js", "jquery", false, true);
    wp_enqueue_script('jquery_inputmask', "/wp-content/js/jquery.inputmask.min.js", "inputmask", false, true);
    wp_enqueue_script(
        'your_settings',
        '/wp-content/js/your_settings.js',
        array('jquery')
    );
    wp_enqueue_script('timezone', "/wp-content/js/jstz-1.0.4.min.js", "jquery", false, true);

    $ReturnString .= '    </div>
</div>';
    //determine tab
    $tab = 2;
    //0: Breakfast; 1: Lunch; 2: Dinner; 3: I need the most help...; 4: Select your time zone; 5: First Name; 6:Last Name; 7: Gender; 8: OK to receive texts?; 9: Phone;
    if (empty($UserData[10]->Field_Value)){
        $tab = 1;
    }
    if ($User->subscription == "active"){
        $tab = 4;
    }
    if (!empty($UserData[0]->Field_Value)){
        if (strtotime($UserData[0]->Field_Value) < time()){
            $tab = 3;
        }
    }
    $ReturnString.= "<script>var \$tab = '$tab';</script>";

    return $ReturnString;
}

add_shortcode("edit-profile", "Insert_Edit_Profile");
?>