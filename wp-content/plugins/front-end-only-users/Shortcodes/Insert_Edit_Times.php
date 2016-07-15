<?php
function Insert_Edit_Times($atts)
{
    global $wpdb, $user_message, $feup_success;
    global $ewd_feup_user_table_name;
    global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
    global $login_page, $redirect_page, $Time, $Salt;
    /** @var wpdb $wpdb */
/** @var string $login_page
     * @var string $Time
     * @var string $Salt
     * @var string $omit_fields
*/
    $Omitted_Fields = explode(",", $omit_fields);
    $CheckCookie = CheckLoginCookie();
    $Sql      = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY `FIELD_ORDER` ASC";
    $Fields   = $wpdb->get_results($Sql);
    $User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
    $UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'",
                                                  $User->User_ID));
    
    $Sql      = "SELECT Field_Value FROM wp_ewd_feup_user_fields WHERE Field_Name='Time zone' and User_ID = '{$User->User_ID}'";
    $time_zone   = $wpdb->get_var($Sql);
    if ($time_zone){
        echo "<script>var user_time_zone = '$time_zone';</script>";
    }
    
    
    $print_field = function ($Field, $hidden = false) use ($Omitted_Fields, $UserData, &$ReturnString) {
        $display_label = $Field->Field_Name;
        if ($display_label == "Breakfast") {
            $display_label = "What time do you like to work out?";
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
//                $ReturnString .= '<hr/>';
            }
            $ReturnString .= "<div class='row-num-" . $Field->Field_ID . " pure-control-group" . ($hidden?" hidden":"") . "'>";
            $ReturnString .= "<label for='" . $Field->Field_Name . "' id='ewd-feup-edit-" . $Field->Field_ID . "' class='ewd-feup-field-label'>" . __($display_label,
                                                                                                                                                      'EWD_FEUP') . "</label>";
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
                            $Value = '06:00pm';
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
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top  " . ($hidden?" hidden":"") . "'><label class='pure-radio'></label>";
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
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top  " . ($hidden?" hidden":"") . "'><label class='pure-radio'></label>";
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
//                $ReturnString .= '<hr/>';
            }
        }
    };
    $Custom_CSS        = get_option("EWD_FEUP_Custom_CSS");
    $Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
    $CheckCookie = CheckLoginCookie();
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
        $ReturnString .= $user_message['Message'];
    }
    $ReturnString .= "<form novalidate action='#' method='post' id='ewd-feup-edit-profile-form' class='pure-form pure-form-aligned' enctype='multipart/form-data'>";
  //  $ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time . $Salt)) . "'>";
  //  $ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit_your_settings'>";
    //UserData: 0: Time zone; 1: OK to receive texts?; 2: Dinner; 3: Lunch; 4: Breakfast; 5: Phone; 6:Gender; 7: Last Name; 8 First Name: ; 9: I need the most help...; 10: Membership Expiry Date
    //Fields: 5: First Name; 0: Breakfast; 1: Lunch; 2: Dinner; 3: I need the most help...; 4: Select your time zone; 5: First Name; 6:Last Name; 7: Gender; 8: OK to receive texts?; 9: Phone;
//    $times = array($UserData[4]->Field_Value, $UserData[3]->Field_Value, $UserData[2]->Field_Value);
    $ReturnString .= '<div><br/></div>';
    $ReturnString .= $print_field($Fields[0]);
    $ReturnString .= $print_field($Fields[1]);
    $ReturnString .= $print_field($Fields[2]);
    $ReturnString .= $print_field($Fields[3], true);
    $ReturnString .= $print_field($Fields[4], true);
    $ReturnString .= $print_field($Fields[5], true);
    $ReturnString .= $print_field($Fields[6], true);
    $ReturnString .= $print_field($Fields[7], true);
    $ReturnString .= $print_field($Fields[8], true);
    $ReturnString .= $print_field($Fields[9], true);
    $ReturnString .= $print_field($Fields[10], true);
    $ReturnString .=
<<<HTML
<!--set default value here-->
<div><br/></div>
<div class="pure-control-group center">
    <select rel="" name="Time zone" id="ewd-feup-register-input-14" class="ewd-feup-select">
    <option value="Please select">Please select</option><option value="PST">Pacific Time Zone</option>
    <option value="EST">Eastern Standard Timezone</option><option value="CST">Central Standard Timezone</option><option value="MST">Mountain Standard Timezone</option></select>
</div>
<script src="/wp-content/js/autotimezone.js"></script>
HTML;
    /** @var string $submit_text */
    $ReturnString .= "<input type='submit' class='ewd-feup-submit pure-button pure-button-primary' name='Edit_Profile_Submit' value='Save'>";
    $ReturnString .= "</form>";
    $ReturnString .= "</div>";
    return $ReturnString;
}
add_shortcode("account-times", "Insert_Edit_Times");
?>