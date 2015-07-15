<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Edit_Profile( $atts )
{
    // Include the required global variables, and create a few new ones
    global $wpdb, $user_message, $feup_success;
    global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;

    $Custom_CSS = get_option("EWD_FEUP_Custom_CSS");

    $CheckCookie = CheckLoginCookie();

    $Sql      = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes'";
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

    if ( $CheckCookie['Username'] == "" ) {
        $ReturnString .= __('You must be logged in to access this page.', 'EWD_FEUP');
        if ( $login_page != "" ) {
            $ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login',
                                                                                                          'EWD_FEUP') . "</a> " . __('to continue.',
                                                                                                                                     'EWD_FEUP');
            if ( isset($_SESSION['user_name_changed']) && isset($_SESSION['message_count']) && ! empty($_SESSION['user_name_changed']) && $_SESSION['message_count'] > 0 ) {
                $ReturnString .= "<p class='text-success'>Your email has been changed. Please use your new email for logging in.</p><br/>";
                $_SESSION['message_count'] --;

            }

        }

        return $ReturnString;
    }

    if ( $feup_success and $redirect_page != '#' ) {
        FEUPRedirect($redirect_page);
    }

    $ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
    if ( isset($user_message['Message']) ) {
        $ReturnString .= $user_message['Message'];
    }
    session_start();
    if ( isset($_SESSION['first_sms_sent_to']) && ! empty($_SESSION['first_sms_sent_to']) && isset($_SESSION['message_count']) && $_SESSION['message_count'] > 0 ) {
        $ReturnString .= "<br/><p class='text-success'>Welcome to Thinkthinly. A message has been sent to your number " . $_SESSION['first_sms_sent_to'] . "</p>";
        $_SESSION['message_count'] --;
    }

    $ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='pure-form pure-form-aligned' enctype='multipart/form-data'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time . $Salt)) . "'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
    $ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-profile'>";
    $ReturnString .= "<input type='hidden' name='Omit_Fields' value='" . $omit_fields . "'>";

    $Omitted_Fields = explode(",", $omit_fields);

    //Brian added. Force new username and password if user has just signed up
    $username = $User->Username;
    //TODO B
    $user_email = $User->user_email;
    if ( strpos($username, "@foobar.com") != false ) {
        $username = "";
    }
    $ReturnString .= '<div class="pure-control-group">';
    $ReturnString .= '<label for="Username">Phone number: </label>';
    $ReturnString .= '<input type="text" class="ewd-feup-text-input" name="Username" value="' . $username . '" required>';
    $ReturnString .= '</div>';
    $ReturnString .= '<div class="pure-control-group">';
    $ReturnString .= '<label for="user_email">Email: </label>';
    $ReturnString .= '<input type="email" class="ewd-feup-text-input" name="user_email" value="' . $user_email . '" >';
    $ReturnString .= '</div>';
    if ( empty($username) ) {
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


    foreach ( $Fields as $Field ) {
        if ( ! in_array($Field->Field_Name, $Omitted_Fields) ) {
            if ( $Field->Field_Required == "Yes" ) {
                $Req_Text = "required";
            }
            $Value = "";

            if ( $Field->Field_Name == "Phone" ) {
                $Field->Field_Type = "tel";
            }

            //echo  "<pre>";
            //print_r($UserData);
            //echo "</pre>";


            foreach ( $UserData as $UserField ) {
                if ( $Field->Field_Name == $UserField->Field_Name ) {
                    $Value = $UserField->Field_Value;
                }
            }
            $ReturnString .= "<div class='pure-control-group'>";
            $ReturnString .= "<label for='" . $Field->Field_Name . "' id='ewd-feup-edit-" . $Field->Field_ID . "' class='ewd-feup-field-label'>" . __($Field->Field_Name,
                                                                                                                                                      'EWD_FEUP') . ": </label>";
            if ( $Field->Field_Type == "text" or $Field->Field_Type == "mediumint" ) {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input pure-input-1-3' type='text' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ( $Field->Field_Type == "tel" ) {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input pure-input-1-3' type='tel' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ( $Field->Field_Type == "date" ) {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input pure-input-1-3' type='date' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ( $Field->Field_Type == "datetime" ) {
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-datetime-input pure-input-1-3' type='datetime-local' value='" . $Value . "' " . $Req_Text . "/>";
            } elseif ( $Field->Field_Type == "textarea" ) {
                $ReturnString .= "<textarea name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-textarea pure-input-1-2' " . $Req_Text . ">" . $Value . "</textarea>";
            } elseif ( $Field->Field_Type == "file" ) {
                $ReturnString .= __("Current file:", 'EWD_FEUP') . " " . substr($Value, 10) . " | ";
                $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input pure-input-1-3' type='file' value='' " . $Req_Text . "/>";
            } elseif ( $Field->Field_Type == "select" ) {
                $Options = explode(",", $Field->Field_Options);
                $bkend_calculated_time     = '';


                /*echo  "<pre>";
            print_r($Field);
            echo "</pre>";

            echo $_COOKIE['words'];*/
                if ( empty($Value) ) {
                    switch ( $Field->Field_Name ) {
                        case 'Breakfast':
                            $Value = '16:00';
                            break;
                        case 'Lunch':
                            $Value = '19:00';
                            break;
                        case 'Dinner':
                            $Value = '1:00am';
                            break;
                        default:
                            break;

                    }
                }
                if ( $Field->Field_Name == 'Breakfast' || $Field->Field_Name == 'Lunch' || $Field->Field_Name == 'Dinner' )
                {
                    date_default_timezone_set('UTC');
                    $coo      = $_COOKIE['words'];
                    $coo      = trim($coo);
                    $operator = preg_replace('/[0-9]/', '', $coo);
                    //echo 'g'.$operator.'h';die;
                    if ( trim($operator) == '' ) {
                        $coo      = '+' . $coo;
                        $operator = '+';
                        //echo $operator;die;
                    }
                    $vals = preg_replace('/[-+]/', '', $coo);
                    $Valu = preg_replace('/[A-Za-z]/', '', $Value);
                    //echo $Valu;die;
                    if ( $vals < 9 ) {
                        $vals = str_replace('0', '', $vals);
                    }

                    //echo $operator;die;

                    if ( trim($operator) == '+' ) {
                        $bkend_calculated_time = date('h:ia', strtotime($Valu) + $vals * 60 * 60);
                        //echo $Valu.$brk;die;
                    } elseif ( trim($operator) == '-' ) {
                        $bkend_calculated_time = date('h:ia', strtotime($Valu) - $vals * 60 * 60);

                        //echo $Value.$brk;
                    } else {
                        $bkend_calculated_time = $Value;
                    }

                }


                $ReturnString .= "<select rel='" . $bkend_calculated_time . "' name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-select pure-input-1-3'>";

                foreach ( $Options as $Option ) {
                    //echo trim($Option)."<br>";
                    //echo trim($brk)."<br>";
                    //echo trim($Value)."<br><br>";

                    $ReturnString .= "<option value='" . $Option . "' ";
                    if ( trim($Option) == trim($Value) ) {
                        $ReturnString .= "selected='selected'";
                    }
                    $ReturnString .= ">" . $Option . "</option>";
                }
                $ReturnString .= "</select>";
            } elseif ( $Field->Field_Type == "radio" ) {
                $Counter = 0;
                $Options = explode(",", $Field->Field_Options);
                foreach ( $Options as $Option ) {
                    if ( $Counter != 0 ) {
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top'><label class='pure-radio'></label>";
                    }
                    $ReturnString .= "<input type='radio' name='" . $Field->Field_Name . "' value='" . $Option . "' class='ewd-feup-radio' " . $Req_Text . " ";
                    if ( trim($Option) == trim($Value) ) {
                        $ReturnString .= "checked";
                    }
                    $ReturnString .= ">" . $Option;
                    $Counter ++;
                }
            } elseif ( $Field->Field_Type == "checkbox" ) {
                $Counter = 0;
                $Options = explode(",", $Field->Field_Options);
                $Values  = explode(",", $Value);
                foreach ( $Options as $Option ) {
                    if ( $Counter != 0 ) {
                        $ReturnString .= "</div><div class='pure-control-group ewd-feup-negative-top'><label class='pure-radio'></label>";
                    }
                    $ReturnString .= "<input type='checkbox' name='" . $Field->Field_Name . "[]' value='" . $Option . "' class='ewd-feup-checkbox' " . $Req_Text . " ";
                    if ( in_array($Option, $Values) ) {
                        $ReturnString .= "checked";
                    }
                    $ReturnString .= ">" . $Option . "</br>";
                    $Counter ++;
                }
            }
            $ReturnString .= "</div>";
            unset($Req_Text);
        }
    }

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

    return $ReturnString;
}

add_shortcode("edit-profile", "Insert_Edit_Profile");
?>