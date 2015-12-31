<?php
function Insert_Edit_Account_Form($atts)
{
    global $wpdb, $user_message, $feup_success;
    global $ewd_feup_user_table_name;
    global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
    global $login_page, $redirect_page, $Time, $Salt;

    $Custom_CSS        = get_option("EWD_FEUP_Custom_CSS");
    $Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

    $CheckCookie = CheckLoginCookie();
    $Sql      = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY `FIELD_ORDER` ASC";
    $Fields   = $wpdb->get_results($Sql);

    $User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
    $UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'",
                                                  $User->User_ID));
    /* @var string $omit_fields */

    $Omitted_Fields = explode(",", $omit_fields);

    $print_field =function ($Field, $hidden = false) use ($Omitted_Fields, $UserData, &$ReturnString) {
        $display_label = $Field->Field_Name;
        if ($display_label == "Breakfast") {
            $display_label = "Reminder 1";
        }
        if ($display_label == "Lunch") {
            $display_label = "Reminder 2";
        }
        if ($display_label == "Dinner") {
            $display_label = "Reminder 3";
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
            $ReturnString .= "<div class='pure-control-group " . ($hidden?"hidden":"") . "'>";
            $ReturnString .= "<label for='" . $Field->Field_Name . "' id='ewd-feup-edit-" . $Field->Field_ID . "' class='ewd-feup-field-label'>" . __($display_label,
                                                                                                                                                      'EWD_FEUP') . ": </label>";
            if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input  ' type='text' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "tel") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input  ' type='tel' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "date") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input  ' type='date' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "datetime") {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-datetime-input  ' type='datetime-local' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ($Field->Field_Type == "textarea") {
                $ReturnString .= "<textarea name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-textarea ' " . $Req_Text . ">" . $Value . "</textarea>";
            } elseif ($Field->Field_Type == "file") {
                $ReturnString .= __("Current file:", 'EWD_FEUP') . " " . substr($Value, 10) . " | ";
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input  ' type='file' value='' " . $Req_Text . "/>";
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

                $ReturnString .= "<select rel='" . $bkend_calculated_time . "' name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-select '>";

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
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top  " . ($hidden?"hidden":"") . "'><label class='pure-radio'></label>";
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
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top  " . ($hidden?"hidden":"") . "'><label class='pure-radio'></label>";
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

    $ReturnString = "";

    // Get the attributes passed by the shortcode, and store them in new variables for processing
    extract(shortcode_atts(array(
                               'redirect_page' => '#',
                               'login_page' => '',
                               'submit_text' => __('Update Account', 'EWD_FEUP')),
                           $atts
            )
    );

    $ReturnString .= "<style type='text/css'>";
    $ReturnString .= $Custom_CSS;
    $ReturnString .= "</style>";

    if ($CheckCookie['Username'] == "") {
        $ReturnString .= __('You must be logged in to access this page.', 'EWD_FEUP');
        if ($login_page != "") {
            $ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');
        }

        return $ReturnString;
    }

    if ($feup_success and $redirect_page != '#') {
        FEUPRedirect($redirect_page);
    }

    $ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
    if (isset($user_message['Message'])) {
        $ReturnString .= "<div class='error-message'>" . $user_message['Message'] . "</div>";
    }
    $ReturnString .= "<form novalidate action='#' method='post' id='ewd-feup-edit-profile-form' class='pure-form pure-form-aligned'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time . $Salt)) . "'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit_account_info'>";
    //UserDate: 0: Time zone; 1: OK to receive texts?; 2: Dinner; 3: Lunch; 4: Breakfast; 5: Phone; 6:Gender; 7: Last Name; 8 First Name: ; 9: I need the most help...; 10: Membership Expiry Date
    $first_name = $UserData[10]->Field_Value;
    $last_name = $UserData[9]->Field_Value;
    $email = $User->user_email;
    $ReturnString .=
<<<HTML
<div class="pure-control-group"><label for="First Name" id="ewd-feup-edit-1" class="ewd-feup-field-label">First Name: </label><input name="First Name" id="ewd-feup-register-input-1" class="ewd-feup-text-input pure-input-1-3" type="text" value="$first_name" required=""></div>
<div class="pure-control-group"><label for="Last Name" id="ewd-feup-edit-2" class="ewd-feup-field-label">Last Name: </label><input name="Last Name" id="ewd-feup-register-input-2" class="ewd-feup-text-input pure-input-1-3" type="text" value="$last_name" required=""></div>
<div class="pure-control-group"><label for="user_email">Email: </label><input type="email" class="ewd-feup-text-input pure-input-1-3" name="user_email" value="$email"></div>
HTML;

    $ReturnString .= "<div class='pure-control-group'>
    <label class='ewd-feup-field-label'>" . __('Create Password', 'EWD_FEUP') . ": </label>";
    $ReturnString .= "<input type='password' class='ewd-feup-text-input pure-input-1-3' name='User_Password' value=''>
    </div>";
    $ReturnString .= "<div class='pure-control-group'>
    <label class='ewd-feup-field-label'>" . __('Retype Password', 'EWD_FEUP') . ": </label>";
    $ReturnString .= "<input type='password' class='ewd-feup-text-input pure-input-1-3' name='Confirm_User_Password' value=''>
    </div>";
    /** @var string $submit_text */
    $ReturnString .= "<input type='submit' class='ewd-feup-submit pure-button pure-button-primary' name='Edit_Profile_Submit' value='Save'>";

    $ReturnString .= $print_field($Fields[0], true);

    $ReturnString .= "</form>";
    $ReturnString .= "</div>";

    return $ReturnString;
}

add_shortcode("account-details", "Insert_Edit_Account_Form");
?>
