<?php

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . DIRECTORY_SEPARATOR . "vendor/autoload.php");

function call_send_signup_mms($uid)
{
    error_log("uid: $uid, file: " . __FILE__ . "line: " . __LINE__ . "function: " . __FUNCTION__);
    exec("nohup php-cli -f /home/saramy/public_html/Thinkthinly.com/wp-content/themes/arcade-basic/sendsignupmms.php $uid >/dev/null 2>/dev/null &");
}

function Generate_Password($plainPassword = null)
{
    if (!$plainPassword) {
        return false;
    }
    $intermediateSalt = bin2hex(openssl_random_pseudo_bytes(30));
    $intermediateSalt = substr($intermediateSalt, 0, 22);
    $finalSalt = '$2y$13$' . $intermediateSalt . '$';
    $hashedPassword = crypt($plainPassword, $finalSalt);

    return $hashedPassword;
}

/* Prepare the data to add or edit a single product */
function Add_Edit_User()
{

    global $wpdb, $feup_success, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
    $Salt = get_option("EWD_FEUP_Hash_Salt");
    $Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
    $Default_User_Level = get_option("EWD_Default_User_Level");
    $Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
    $Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
    $Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
    $tt_settings = $wpdb->get_results("SELECT * FROM saramy_wrdp4.tt_settings");
    $res = ah_flatten($tt_settings, 'name');

    $Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
    $Fields = $wpdb->get_results($Sql);

    $date = date("Y-m-d H:i:s");

    $UserCookie = CheckLoginCookie();

    if (!isset($_POST['Admin_Approved'])) {
        $_POST['Admin_Approved'] = null;
    }
    if (!isset($_POST['action'])) {
        $_POST['action'] = null;
    }
    if (!isset($_POST['ewd-feup-action'])) {
        $_POST['ewd-feup-action'] = null;
    }

    $User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'",
        $UserCookie['Username']));
    if (is_object($User)) {
        $User_ID = $User->User_ID;
    }
    if (is_admin()) {
        $User_ID = $_POST['User_ID'];
    }

    if (isset($_POST['Omit_Fields'])) {
        $Omitted_Fields = explode(",", $_POST['Omit_Fields']);
    } else {
        $Omitted_Fields = array();
    }

    if (isset($_POST['Username'])) {
        $User_Fields['Username'] = $_POST['Username'];
    }
    if (isset($_POST['user_email'])) {
        $User_Fields['user_email'] = $_POST['user_email'];
    }
    if (isset($_POST['subscription'])) {
        $User_Fields['subscription'] = $_POST['subscription'];
    }

    if (isset($_POST['allow_0_dollar_subscription'])) {
        $User_Fields['allow_0_dollar_subscription'] = $_POST['allow_0_dollar_subscription'];
    }

    // check if the password is empty - so we won't try to update it if it is empty
    if (empty($_POST['User_Password'])) {
        unset($_POST['User_Password']);
    }

    if ($Use_Crypt == "Yes") {
        if (isset($_POST['User_Password'])) {
            $User_Fields['User_Password'] = Generate_Password($_POST['User_Password']);
        }
    } else {
        if (isset($_POST['User_Password'])) {
            $User_Fields['User_Password'] = sha1(md5($_POST['User_Password'] . $Salt));
        }
    }
    if (isset($_POST['Level_ID'])) {
        $User_Fields['Level_ID'] = $_POST['Level_ID'];
    } else {
        $User_Fields['Level_ID'] = $Default_User_Level;
    }
    if ($_POST['Admin_Approved'] == "Yes") {
        $User_Fields['User_Admin_Approved'] = "Yes";
    }
    if ($_POST['Admin_Approved'] == "No") {
        $User_Fields['User_Admin_Approved'] = "No";
    }
    if ($_POST['User_Password'] != $_POST['Confirm_User_Password']) {
        $user_update = array(
            "Message_Type" => "Error",
            "Message" => __("The passwords you entered did not match.", "EWD_FEUP")
        );

        return $user_update;
    }
    if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {
        if (empty($_POST['User_Password'])) {
            $user_update = array(
                "Message_Type" => "Error",
                "Message" => __("Please enter your 10-digit phone number.", "EWD_FEUP")
            );

            return $user_update;
        }
        $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'",
            $_POST['Username']));
        if ($wpdb->num_rows > 0) {
            $user_update = array(
                "Message_Type" => "Error",
                "Message" => __("There is already a user with that phone number. Please try a different one.",
                    "EWD_FEUP")
            );

            return $user_update;
        }
        //remove placeholder text
        $newUsername = str_replace("_", "", $_POST['Username']);
        if (strlen($newUsername) < 10) {
            $user_update = array(
                "Message_Type" => "Error",
                "Message" => __("Your phone number must be 10 numbers.", "EWD_FEUP")
            );

            return $user_update;
        }
    }

    if ($_POST['ewd-feup-action'] != "edit-account") {
        foreach ($Fields as $Field) {
            if (!in_array($Field->Field_Name, $Omitted_Fields)) {
                $Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
                $Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
                $Field_Name = str_replace(" ", "_", $Field->Field_Name);
                if ($Field->Field_Type == "file") {
                    $File_Upload_Return = Handle_File_Upload($Field_Name);
                    if ($File_Upload_Return['Success'] == "No") {
                        return $File_Upload_Return['Data'];
                    } elseif ($File_Upload_Return['Success'] == "N/A") {
                        unset($Additional_Fields_Array[$Field->Field_Name]);
                    } else {
                        $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $File_Upload_Return['Data'];
                    }
                } elseif (is_array($_POST[$Field_Name])) {
                    $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep(implode(",",
                        $_POST[str_replace("...", "___", $Field_Name)]));
                } else {
                    $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep($_POST[str_replace("...", "___", $Field_Name)]);
                }
            }
        }
    }

    if (!isset($error)) {
        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */
        if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {

            /*** Time converted to 24 hr format ***/
            /*echo  "<pre>";
            print_r($Additional_Fields_Array);
            echo "</pre>"; */
            $bf_date1 = $Additional_Fields_Array['Breakfast']['Field_Value'];
            $bf_date = date('H:ia ', strtotime($bf_date1));
            //echo $bf_date;

            $lnh_date1 = $Additional_Fields_Array['Lunch']['Field_Value'];
            $lnh_date = date('H:ia ', strtotime($lnh_date1));
            //echo $lnh_date;

            $dnr_date1 = $Additional_Fields_Array['Dinner']['Field_Value'];
            $dnr_date = date('H:ia ', strtotime($dnr_date1));
            //echo $dnr_date;


            if ($User->User_ID != "") {
                $user_update = __("There is already an account with that Username. Please select a different one.",
                    "EWD_FEUP");

                return $user_update;
            }
            if (!isset($User_Fields['User_Admin_Approved'])) {
                $User_Fields['User_Admin_Approved'] = "No";
            }
            if (!isset($User_Fields['User_Email_Confirmed'])) {
                $User_Fields['User_Email_Confirmed'] = "No";
            }
            $User_Fields['User_Date_Created'] = $date;
            $User_Fields['User_Last_Login'] = $date;

            $res = ah_flatten($tt_settings, 'name');

            $expiry_date = (new DateTime());
            $dti = new DateInterval('P' . $tt_settings['default_trial_period']['value'] . 'D');
            $expiry_date->add($dti);
            // $expiry_date->sub(new DateInterval('P7D'));
            $expiry_date = $expiry_date->format('Y-m-d');

            $Additional_Fields_Array['Membership Expiry Date']['Field_Value'] = $expiry_date;

            // if (!$Additional_Fields_Array['Time zone']['Field_Value']){
            //     $Additional_Fields_Array['Time zone']['Field_Value'] = 'PST';
            // };
            $user_update = Add_EWD_FEUP_User($User_Fields);
            $User_ID = $wpdb->insert_id;

            //Custom code
            if ($Additional_Fields_Array['Time zone']) {
                $offset = preg_replace('/[a-zA-Z()]/', '', $Additional_Fields_Array['Time zone']['Field_Value']);
                $operator = preg_replace('/[0-9]/', '', $offset);
                $vals = preg_replace('/[-+]/', '', $offset);

                $b = preg_replace('/[A-Za-z]/', '', $bf_date);
                $l = preg_replace('/[A-Za-z]/', '', $lnh_date);
                $d = preg_replace('/[A-Za-z]/', '', $dnr_date);


                //echo $operator."<br>";
                //echo $vals;


                if ($offset) {
                    //print_r($bf_date);die;
                    date_default_timezone_set('UTC');
                    if ($vals < 9) {
                        $vals = str_replace('0', '', $vals);
                    }


                    if (trim($operator) == '-') {
                        $brk = date('H:ia', strtotime($b) + $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) + $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) + $vals * 60 * 60);

                    } elseif (trim($operator) == '+') {
                        $brk = date('H:ia', strtotime($b) - $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) - $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) - $vals * 60 * 60);

                    } else {
                        $brk = $bf_date;
                        $lunch = $lnh_date;
                        $dinner = $dnr_date;
                    }

                    $Additional_Fields_Array['Breakfast']['Field_Value'] = $brk;
                    $Additional_Fields_Array['Lunch']['Field_Value'] = $lunch;
                    $Additional_Fields_Array['Dinner']['Field_Value'] = $dinner;
                }
            }


            /*echo  "<br><br><br><pre>";
            print_r($Additional_Fields_Array);
            echo "</pre>";  */
            //die();

            //Custom code end
            foreach ($Additional_Fields_Array as $Field) {
                $user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                    $Field['Field_Value'], $date);
            }

            if ($_POST['ewd-feup-action'] == "register") {
                $wpdb->replace("wp_user_mms_sent", ["user_id" => $User_ID]);
                $user_update = __("Your account has been succesfully created.", "EWD_FEUP");
                if ($Sign_Up_Email == "Yes") {
                    EWD_FEUP_Send_Email($User_Fields, $Additional_Fields_Array);
                }
                if ($Email_Confirmation != "Yes" and $Admin_Approval != "Yes") {
                    CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
                    $feup_success = true;
                }
            }
        } /* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the user */
        else {

            $bf_date1 = $Additional_Fields_Array['Breakfast']['Field_Value'];
            $bf_date = date('H:ia ', strtotime($bf_date1));
            //echo $bf_date;

            $lnh_date1 = $Additional_Fields_Array['Lunch']['Field_Value'];
            $lnh_date = date('H:ia ', strtotime($lnh_date1));
            //echo $lnh_date;

            $dnr_date1 = $Additional_Fields_Array['Dinner']['Field_Value'];
            $dnr_date = date('H:ia ', strtotime($dnr_date1));
            //echo $dnr_date;

            if (isset($User_Fields)) {
                $user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);
            }
            if (is_array($Additional_Fields_Array)) {
                //print_r($Additional_Fields_Array);die;
                //Custom code
                if ($Additional_Fields_Array['Time zone']) {
                    $offset = preg_replace('/[a-zA-Z()]/', '', $Additional_Fields_Array['Time zone']['Field_Value']);
                    $operator = preg_replace('/[0-9]/', '', $offset);
                    $vals = preg_replace('/[-+]/', '', $offset);

                    $b = preg_replace('/[A-Za-z]/', '', $bf_date);
                    $l = preg_replace('/[A-Za-z]/', '', $lnh_date);
                    $d = preg_replace('/[A-Za-z]/', '', $dnr_date);
                    //echo $vals;die;
                    if ($offset) {
                        //print_r($bf_date);die;
                        date_default_timezone_set('UTC');
                        if ($vals < 9) {
                            $vals = str_replace('0', '', $vals);
                        }


                        if (trim($operator) == '-') {
                            $brk = date('H:ia', strtotime($b) + $vals * 60 * 60);
                            $lunch = date('H:ia', strtotime($l) + $vals * 60 * 60);
                            $dinner = date('H:ia', strtotime($d) + $vals * 60 * 60);
                        } elseif (trim($operator) == '+') {
                            $brk = date('H:ia', strtotime($b) - $vals * 60 * 60);
                            $lunch = date('H:ia', strtotime($l) - $vals * 60 * 60);
                            $dinner = date('H:ia', strtotime($d) - $vals * 60 * 60);
                        } else {
                            $brk = $bf_date;
                            $lunch = $lnh_date;
                            $dinner = $dnr_date;
                        }

                        $Additional_Fields_Array['Breakfast']['Field_Value'] = $brk;
                        $Additional_Fields_Array['Lunch']['Field_Value'] = $lunch;
                        $Additional_Fields_Array['Dinner']['Field_Value'] = $dinner;
                    }
                }
                //Custom code end
                foreach ($Additional_Fields_Array as $Field) {
                    $CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'",
                        $Field['Field_ID'], $User_ID));
                    if ($CurrentField->User_Field_ID != "") {
                        $user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                            $Field['Field_Value']);
                    } else {
                        $user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                            $Field['Field_Value'], $date);
                    }
                }
            }
            if ($_POST['ewd-feup-action'] == "edit-account") {
                CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
            }
        }
        $user_update = array("Message_Type" => "Update", "Message" => $user_update);
        $feup_success = true;
        //        Brian3T sends sms here
        //find uid
        if ($_POST['ewd-feup-action'] == "register") {
            $sql = "SELECT `User_ID` FROM `wp_ewd_feup_users` ORDER BY User_ID DESC LIMIT 1";
            $result = $wpdb->get_results($sql);

            if (isset($result[0]->User_ID)) {
                global $uid;
                $uid = $result[0]->User_ID;
                call_send_signup_mms($uid);
            }
        } else {
            session_start();
            unset ($_SESSION['first_sms_sent_to']);
        }

        return $user_update;
    } /* Return any error that might have occurred */
    else {
        $output_error = array("Message_Type" => "Error", "Message" => $error);

        return $output_error;
    }
}

function Edit_Reminder_Times_User()
{
    global $wpdb, $feup_success, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
    $Salt = get_option("EWD_FEUP_Hash_Salt");
    $Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
    $Default_User_Level = get_option("EWD_Default_User_Level");
    $Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
    $Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
    $Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
    $tt_settings = $wpdb->get_results("SELECT * FROM tt_settings");
    $res = ah_flatten($tt_settings, 'name');

    $Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
    $Fields = $wpdb->get_results($Sql);

    $date = date("Y-m-d H:i:s");

    $UserCookie = CheckLoginCookie();

    if (!isset($_POST['Admin_Approved'])) {
        $_POST['Admin_Approved'] = null;
    }
    if (!isset($_POST['action'])) {
        $_POST['action'] = null;
    }
    if (!isset($_POST['ewd-feup-action'])) {
        $_POST['ewd-feup-action'] = null;
    }

    $User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'",
        $UserCookie['Username']));
    if (is_object($User)) {
        $User_ID = $User->User_ID;
    }
    if (is_admin()) {
        $User_ID = $_POST['User_ID'];
    }

    if (isset($_POST['Omit_Fields'])) {
        $Omitted_Fields = explode(",", $_POST['Omit_Fields']);
    } else {
        $Omitted_Fields = array();
    }

    if (isset($_POST['Username'])) {
        $User_Fields['Username'] = $_POST['Username'];
    }
    if (isset($_POST['user_email'])) {
        $User_Fields['user_email'] = $_POST['user_email'];
    }
    if (isset($_POST['subscription'])) {
        $User_Fields['subscription'] = $_POST['subscription'];
    }


    if (isset($_POST['Level_ID'])) {
        $User_Fields['Level_ID'] = $_POST['Level_ID'];
    } else {
        $User_Fields['Level_ID'] = $Default_User_Level;
    }
    if ($_POST['Admin_Approved'] == "Yes") {
        $User_Fields['User_Admin_Approved'] = "Yes";
    }
    if ($_POST['Admin_Approved'] == "No") {
        $User_Fields['User_Admin_Approved'] = "No";
    }
    if ($_POST['User_Password'] != $_POST['Confirm_User_Password']) {
        $user_update = array(
            "Message_Type" => "Error",
            "Message" => __("The passwords you entered did not match.", "EWD_FEUP")
        );

        return $user_update;
    }

    foreach ($Fields as $Field) {
        if (!in_array($Field->Field_Name, $Omitted_Fields)) {
            $Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
            $Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
            $Field_Name = str_replace(" ", "_", $Field->Field_Name);
            if ($Field->Field_Type == "file") {
                $File_Upload_Return = Handle_File_Upload($Field_Name);
                if ($File_Upload_Return['Success'] == "No") {
                    return $File_Upload_Return['Data'];
                } elseif ($File_Upload_Return['Success'] == "N/A") {
                    unset($Additional_Fields_Array[$Field->Field_Name]);
                } else {
                    $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $File_Upload_Return['Data'];
                }
            } elseif (is_array($_POST[$Field_Name])) {
                $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep(implode(",",
                    $_POST[str_replace("...", "___", $Field_Name)]));
            } else {
                $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep($_POST[str_replace("...", "___", $Field_Name)]);
            }
        }
    }

    if (!isset($error)) {
        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */

        $bf_date1 = $Additional_Fields_Array['Breakfast']['Field_Value'];
        $bf_date = date('H:ia ', strtotime($bf_date1));
        //echo $bf_date;

        $lnh_date1 = $Additional_Fields_Array['Lunch']['Field_Value'];
        $lnh_date = date('H:ia ', strtotime($lnh_date1));
        //echo $lnh_date;

        $dnr_date1 = $Additional_Fields_Array['Dinner']['Field_Value'];
        $dnr_date = date('H:ia ', strtotime($dnr_date1));
        //echo $dnr_date;

        if (isset($User_Fields)) {
            $user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);
        }
        if (is_array($Additional_Fields_Array)) {
            //print_r($Additional_Fields_Array);die;
            //Custom code
            if ($Additional_Fields_Array['Time zone']) {
                $offset = preg_replace('/[a-zA-Z()]/', '', $Additional_Fields_Array['Time zone']['Field_Value']);
                $operator = preg_replace('/[0-9]/', '', $offset);
                $vals = preg_replace('/[-+]/', '', $offset);

                $b = preg_replace('/[A-Za-z]/', '', $bf_date);
                $l = preg_replace('/[A-Za-z]/', '', $lnh_date);
                $d = preg_replace('/[A-Za-z]/', '', $dnr_date);
                //echo $vals;die;
                if ($offset) {
                    //print_r($bf_date);die;
                    date_default_timezone_set('UTC');
                    if ($vals < 9) {
                        $vals = str_replace('0', '', $vals);
                    }


                    if (trim($operator) == '-') {
                        $brk = date('H:ia', strtotime($b) + $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) + $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) + $vals * 60 * 60);
                    } elseif (trim($operator) == '+') {
                        $brk = date('H:ia', strtotime($b) - $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) - $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) - $vals * 60 * 60);
                    } else {
                        $brk = $bf_date;
                        $lunch = $lnh_date;
                        $dinner = $dnr_date;
                    }

                    $Additional_Fields_Array['Breakfast']['Field_Value'] = $brk;
                    $Additional_Fields_Array['Lunch']['Field_Value'] = $lunch;
                    $Additional_Fields_Array['Dinner']['Field_Value'] = $dinner;
                }
            }
            //Custom code end

            //only update reminder times and timezone:
            $Additional_Fields_Array = array_intersect_key($Additional_Fields_Array, array_flip(array('Breakfast', 'Lunch', 'Dinner', 'Time zone')));

            foreach ($Additional_Fields_Array as $Field) {
                $CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'",
                    $Field['Field_ID'], $User_ID));
                if ($CurrentField->User_Field_ID != "") {
                    $user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                        $Field['Field_Value']);
                } else {
                    $user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                        $Field['Field_Value'], $date);
                }
            }
        }
        if ($_POST['ewd-feup-action'] == "edit-account") {
            CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
        }

        $user_update = array("Message_Type" => "Update", "Message" => $user_update);
        $feup_success = true;
        //        Brian3T sends sms here
        //find uid
        if ($_POST['ewd-feup-action'] == "register") {
            $sql = "SELECT `User_ID` FROM `wp_ewd_feup_users` ORDER BY User_ID DESC LIMIT 1";
            $result = $wpdb->get_results($sql);

            if (isset($result[0]->User_ID)) {
                global $uid;
                $uid = $result[0]->User_ID;
                call_send_signup_mms($uid);
            }
        } else {
            session_start();
            unset ($_SESSION['first_sms_sent_to']);
        }

        return $user_update;
    } /* Return any error that might have occurred */
    else {
        $output_error = array("Message_Type" => "Error", "Message" => $error);

        return $output_error;
    }
}

function Edit_Your_Settings_User()
{
    global $wpdb, $feup_success, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
    $Salt = get_option("EWD_FEUP_Hash_Salt");
    $Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
    $Default_User_Level = get_option("EWD_Default_User_Level");
    $Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
    $Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
    $Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
    $tt_settings = $wpdb->get_results("SELECT * FROM tt_settings");
    $res = ah_flatten($tt_settings, 'name');

    $Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
    $Fields = $wpdb->get_results($Sql);

    $date = date("Y-m-d H:i:s");

    $UserCookie = CheckLoginCookie();

    if (!isset($_POST['Admin_Approved'])) {
        $_POST['Admin_Approved'] = null;
    }
    if (!isset($_POST['action'])) {
        $_POST['action'] = null;
    }
    if (!isset($_POST['ewd-feup-action'])) {
        $_POST['ewd-feup-action'] = null;
    }

    $User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'",
        $UserCookie['Username']));
    if (is_object($User)) {
        $User_ID = $User->User_ID;
    }
    if (is_admin()) {
        $User_ID = $_POST['User_ID'];
    }

    if (isset($_POST['Omit_Fields'])) {
        $Omitted_Fields = explode(",", $_POST['Omit_Fields']);
    } else {
        $Omitted_Fields = array();
    }

    if (isset($_POST['Username'])) {
        $User_Fields['Username'] = $_POST['Username'];
    }
    if (isset($_POST['user_email'])) {
        $User_Fields['user_email'] = $_POST['user_email'];
    }
    if (isset($_POST['subscription'])) {
        $User_Fields['subscription'] = $_POST['subscription'];
    }


    if (isset($_POST['Level_ID'])) {
        $User_Fields['Level_ID'] = $_POST['Level_ID'];
    } else {
        $User_Fields['Level_ID'] = $Default_User_Level;
    }
    if ($_POST['Admin_Approved'] == "Yes") {
        $User_Fields['User_Admin_Approved'] = "Yes";
    }
    if ($_POST['Admin_Approved'] == "No") {
        $User_Fields['User_Admin_Approved'] = "No";
    }
    if ($_POST['User_Password'] != $_POST['Confirm_User_Password']) {
        $user_update = array(
            "Message_Type" => "Error",
            "Message" => __("The passwords you entered did not match.", "EWD_FEUP")
        );

        return $user_update;
    }
    // check if the password is empty - so we won't try to update it if it is empty
    if (empty($_POST['User_Password'])) {
        unset($_POST['User_Password']);
    }

    if ($Use_Crypt == "Yes") {
        if (isset($_POST['User_Password'])) {
            $User_Fields['User_Password'] = Generate_Password($_POST['User_Password']);
        }
    } else {
        if (isset($_POST['User_Password'])) {
            $User_Fields['User_Password'] = sha1(md5($_POST['User_Password'] . $Salt));
        }
    }


    foreach ($Fields as $Field) {
        if (!in_array($Field->Field_Name, $Omitted_Fields)) {
            $Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
            $Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
            $Field_Name = str_replace(" ", "_", $Field->Field_Name);
            if ($Field->Field_Type == "file") {
                $File_Upload_Return = Handle_File_Upload($Field_Name);
                if ($File_Upload_Return['Success'] == "No") {
                    return $File_Upload_Return['Data'];
                } elseif ($File_Upload_Return['Success'] == "N/A") {
                    unset($Additional_Fields_Array[$Field->Field_Name]);
                } else {
                    $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $File_Upload_Return['Data'];
                }
            } elseif (is_array($_POST[$Field_Name])) {
                $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep(implode(",",
                    $_POST[str_replace("...", "___", $Field_Name)]));
            } else {
                $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep($_POST[str_replace("...", "___", $Field_Name)]);
            }
        }
    }

    if (!isset($error)) {
        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */

        $bf_date1 = $Additional_Fields_Array['Breakfast']['Field_Value'];
        $bf_date = date('H:ia ', strtotime($bf_date1));
        //echo $bf_date;

        $lnh_date1 = $Additional_Fields_Array['Lunch']['Field_Value'];
        $lnh_date = date('H:ia ', strtotime($lnh_date1));
        //echo $lnh_date;

        $dnr_date1 = $Additional_Fields_Array['Dinner']['Field_Value'];
        $dnr_date = date('H:ia ', strtotime($dnr_date1));
        //echo $dnr_date;

        if (isset($User_Fields)) {
            $user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);
        }
        if (is_array($Additional_Fields_Array)) {
            //print_r($Additional_Fields_Array);die;
            //Custom code
            if ($Additional_Fields_Array['Time zone']) {
                $offset = preg_replace('/[a-zA-Z()]/', '', $Additional_Fields_Array['Time zone']['Field_Value']);
                $operator = preg_replace('/[0-9]/', '', $offset);
                $vals = preg_replace('/[-+]/', '', $offset);

                $b = preg_replace('/[A-Za-z]/', '', $bf_date);
                $l = preg_replace('/[A-Za-z]/', '', $lnh_date);
                $d = preg_replace('/[A-Za-z]/', '', $dnr_date);
                //echo $vals;die;
                if ($offset) {
                    //print_r($bf_date);die;
                    date_default_timezone_set('UTC');
                    if ($vals < 9) {
                        $vals = str_replace('0', '', $vals);
                    }


                    if (trim($operator) == '-') {
                        $brk = date('H:ia', strtotime($b) + $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) + $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) + $vals * 60 * 60);
                    } elseif (trim($operator) == '+') {
                        $brk = date('H:ia', strtotime($b) - $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) - $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) - $vals * 60 * 60);
                    } else {
                        $brk = $bf_date;
                        $lunch = $lnh_date;
                        $dinner = $dnr_date;
                    }

                    $Additional_Fields_Array['Breakfast']['Field_Value'] = $brk;
                    $Additional_Fields_Array['Lunch']['Field_Value'] = $lunch;
                    $Additional_Fields_Array['Dinner']['Field_Value'] = $dinner;
                }
            }
            //Custom code end

            //don't allow user to update:
            foreach (array('Membership Expiry Date', 'OK to receive texts?', 'Gender', 'First Name', 'Last Name', 'Phone') as $key) {
                unset($Additional_Fields_Array[$key]);
            }
            // $Additional_Fields_Array = array_intersect_key($Additional_Fields_Array, array_flip(array('Breakfast', 'Lunch', 'Dinner', 'Time zone', 'First Name', 'Last Name')));

            foreach ($Additional_Fields_Array as $Field) {
                $CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'",
                    $Field['Field_ID'], $User_ID));
                if ($CurrentField->User_Field_ID != "") {
                    $user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                        $Field['Field_Value']);
                } else {
                    $user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                        $Field['Field_Value'], $date);
                }
            }
        }
        if ($_POST['ewd-feup-action'] == "edit-account") {
            CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
        }

        $user_update = array("Message_Type" => "Update", "Message" => $user_update);
        $feup_success = true;
        //        Brian3T sends sms here
        //find uid
        if ($_POST['ewd-feup-action'] == "register") {
            $sql = "SELECT `User_ID` FROM `wp_ewd_feup_users` ORDER BY User_ID DESC LIMIT 1";
            $result = $wpdb->get_results($sql);

            if (isset($result[0]->User_ID)) {
                global $uid;
                $uid = $result[0]->User_ID;
                call_send_signup_mms($uid);
            }
        } else {
            session_start();
            unset ($_SESSION['first_sms_sent_to']);
        }

        return $user_update;
    } /* Return any error that might have occurred */
    else {
        $output_error = array("Message_Type" => "Error", "Message" => $error);

        return $output_error;
    }
}


function Edit_Account_Info_User()
{
    global $wpdb, $feup_success, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
    $Salt = get_option("EWD_FEUP_Hash_Salt");
    $Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
    $Default_User_Level = get_option("EWD_Default_User_Level");
    $Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
    $Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
    $Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
    $tt_settings = $wpdb->get_results("SELECT * FROM tt_settings");
    $res = ah_flatten($tt_settings, 'name');

    $Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
    $Fields = $wpdb->get_results($Sql);

    $date = date("Y-m-d H:i:s");

    $UserCookie = CheckLoginCookie();

    if (!isset($_POST['Admin_Approved'])) {
        $_POST['Admin_Approved'] = null;
    }
    if (!isset($_POST['action'])) {
        $_POST['action'] = null;
    }
    if (!isset($_POST['ewd-feup-action'])) {
        $_POST['ewd-feup-action'] = null;
    }

    $User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'",
        $UserCookie['Username']));
    if (is_object($User)) {
        $User_ID = $User->User_ID;
    }
    if (is_admin()) {
        $User_ID = $_POST['User_ID'];
    }

    if (isset($_POST['Omit_Fields'])) {
        $Omitted_Fields = explode(",", $_POST['Omit_Fields']);
    } else {
        $Omitted_Fields = array();
    }

    if (isset($_POST['Username'])) {
        $User_Fields['Username'] = $_POST['Username'];
    }
    if (isset($_POST['user_email'])) {
        $User_Fields['user_email'] = $_POST['user_email'];
    }
    if (isset($_POST['subscription'])) {
        $User_Fields['subscription'] = $_POST['subscription'];
    }


    // check if the password is empty - so we won't try to update it if it is empty
    if (empty($_POST['User_Password'])) {
        unset($_POST['User_Password']);
    }

    if ($Use_Crypt == "Yes") {
        if (isset($_POST['User_Password'])) {
            $User_Fields['User_Password'] = Generate_Password($_POST['User_Password']);
        }
    } else {
        if (isset($_POST['User_Password'])) {
            $User_Fields['User_Password'] = sha1(md5($_POST['User_Password'] . $Salt));
        }
    }
    if (isset($_POST['Level_ID'])) {
        $User_Fields['Level_ID'] = $_POST['Level_ID'];
    } else {
        $User_Fields['Level_ID'] = $Default_User_Level;
    }
    if ($_POST['Admin_Approved'] == "Yes") {
        $User_Fields['User_Admin_Approved'] = "Yes";
    }
    if ($_POST['Admin_Approved'] == "No") {
        $User_Fields['User_Admin_Approved'] = "No";
    }
    if ($_POST['User_Password'] != $_POST['Confirm_User_Password']) {
        $user_update = array(
            "Message_Type" => "Error",
            "Message" => __("The passwords you entered did not match.", "EWD_FEUP")
        );

        return $user_update;
    }
    if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {
        if (empty($_POST['User_Password'])) {
            $user_update = array(
                "Message_Type" => "Error",
                "Message" => __("The phone number must be your 10 digit number.", "EWD_FEUP")
            );

            return $user_update;
        }
        $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'",
            $_POST['Username']));
        if ($wpdb->num_rows > 0) {
            $user_update = array(
                "Message_Type" => "Error",
                "Message" => __("Doppelgänger alert! There is already a user with that phone number. Please try a different one.",
                    "EWD_FEUP")
            );

            return $user_update;
        }
        //remove placeholder text
        $newUsername = str_replace("_", "", $_POST['Username']);
        if (strlen($newUsername) < 10) {
            $user_update = array(
                "Message_Type" => "Error",
                "Message" => __("Phone must be at least 10 characters.", "EWD_FEUP")
            );

            return $user_update;
        }
    }

    if ($_POST['ewd-feup-action'] != "edit-account") {
        foreach ($Fields as $Field) {
            if (!in_array($Field->Field_Name, $Omitted_Fields)) {
                $Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
                $Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
                $Field_Name = str_replace(" ", "_", $Field->Field_Name);
                if ($Field->Field_Type == "file") {
                    $File_Upload_Return = Handle_File_Upload($Field_Name);
                    if ($File_Upload_Return['Success'] == "No") {
                        return $File_Upload_Return['Data'];
                    } elseif ($File_Upload_Return['Success'] == "N/A") {
                        unset($Additional_Fields_Array[$Field->Field_Name]);
                    } else {
                        $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $File_Upload_Return['Data'];
                    }
                } elseif (is_array($_POST[$Field_Name])) {
                    $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep(implode(",",
                        $_POST[str_replace("...", "___", $Field_Name)]));
                } else {
                    $Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep($_POST[str_replace("...", "___", $Field_Name)]);
                }
            }
        }
    }

    if (!isset($error)) {
        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */
        if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {

            /*** Time converted to 24 hr format ***/
            /*echo  "<pre>";
            print_r($Additional_Fields_Array);
            echo "</pre>"; */
            $bf_date1 = $Additional_Fields_Array['Breakfast']['Field_Value'];
            $bf_date = date('H:ia ', strtotime($bf_date1));
            //echo $bf_date;

            $lnh_date1 = $Additional_Fields_Array['Lunch']['Field_Value'];
            $lnh_date = date('H:ia ', strtotime($lnh_date1));
            //echo $lnh_date;

            $dnr_date1 = $Additional_Fields_Array['Dinner']['Field_Value'];
            $dnr_date = date('H:ia ', strtotime($dnr_date1));
            //echo $dnr_date;


            if ($User->User_ID != "") {
                $user_update = __("There is already an account with that Username. Please select a different one.",
                    "EWD_FEUP");

                return $user_update;
            }
            if (!isset($User_Fields['User_Admin_Approved'])) {
                $User_Fields['User_Admin_Approved'] = "No";
            }
            if (!isset($User_Fields['User_Email_Confirmed'])) {
                $User_Fields['User_Email_Confirmed'] = "No";
            }
            $User_Fields['User_Date_Created'] = $date;
            $User_Fields['User_Last_Login'] = $date;

            $res = ah_flatten($tt_settings, 'name');

            $expiry_date = (new DateTime());
            $dti = new DateInterval('P' . $tt_settings['default_trial_period']['value'] . 'D');
            $expiry_date->add($dti);
            $expiry_date = $expiry_date->format('Y-m-d');

            $Additional_Fields_Array['Membership Expiry Date']['Field_Value'] = $expiry_date;


            $user_update = Add_EWD_FEUP_User($User_Fields);
            $User_ID = $wpdb->insert_id;

            //Custom code
            if ($Additional_Fields_Array['Time zone']) {
                $offset = preg_replace('/[a-zA-Z()]/', '', $Additional_Fields_Array['Time zone']['Field_Value']);
                $operator = preg_replace('/[0-9]/', '', $offset);
                $vals = preg_replace('/[-+]/', '', $offset);

                $b = preg_replace('/[A-Za-z]/', '', $bf_date);
                $l = preg_replace('/[A-Za-z]/', '', $lnh_date);
                $d = preg_replace('/[A-Za-z]/', '', $dnr_date);


                //echo $operator."<br>";
                //echo $vals;


                if ($offset) {
                    //print_r($bf_date);die;
                    date_default_timezone_set('UTC');
                    if ($vals < 9) {
                        $vals = str_replace('0', '', $vals);
                    }


                    if (trim($operator) == '-') {
                        $brk = date('H:ia', strtotime($b) + $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) + $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) + $vals * 60 * 60);

                    } elseif (trim($operator) == '+') {
                        $brk = date('H:ia', strtotime($b) - $vals * 60 * 60);
                        $lunch = date('H:ia', strtotime($l) - $vals * 60 * 60);
                        $dinner = date('H:ia', strtotime($d) - $vals * 60 * 60);

                    } else {
                        $brk = $bf_date;
                        $lunch = $lnh_date;
                        $dinner = $dnr_date;
                    }

                    $Additional_Fields_Array['Breakfast']['Field_Value'] = $brk;
                    $Additional_Fields_Array['Lunch']['Field_Value'] = $lunch;
                    $Additional_Fields_Array['Dinner']['Field_Value'] = $dinner;
                }
            }


            /*echo  "<br><br><br><pre>";
            print_r($Additional_Fields_Array);
            echo "</pre>";  */
            //die();

            //Custom code end
            foreach ($Additional_Fields_Array as $Field) {
                $user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                    $Field['Field_Value'], $date);
            }

            if ($_POST['ewd-feup-action'] == "register") {
                $user_update = __("Your account has been succesfully created.", "EWD_FEUP");
                if ($Sign_Up_Email == "Yes") {
                    EWD_FEUP_Send_Email($User_Fields, $Additional_Fields_Array);
                }
                if ($Email_Confirmation != "Yes" and $Admin_Approval != "Yes") {
                    CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
                    $feup_success = true;
                }
            }
        } /* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the user */
        else {

            $bf_date1 = $Additional_Fields_Array['Breakfast']['Field_Value'];
            $bf_date = date('H:ia ', strtotime($bf_date1));
            //echo $bf_date;

            $lnh_date1 = $Additional_Fields_Array['Lunch']['Field_Value'];
            $lnh_date = date('H:ia ', strtotime($lnh_date1));
            //echo $lnh_date;

            $dnr_date1 = $Additional_Fields_Array['Dinner']['Field_Value'];
            $dnr_date = date('H:ia ', strtotime($dnr_date1));
            //echo $dnr_date;

            if (isset($User_Fields)) {
                $user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);
            }
            if (is_array($Additional_Fields_Array)) {
                //print_r($Additional_Fields_Array);die;
                //Custom code
                if ($Additional_Fields_Array['Time zone']) {
                    $offset = preg_replace('/[a-zA-Z()]/', '', $Additional_Fields_Array['Time zone']['Field_Value']);
                    $operator = preg_replace('/[0-9]/', '', $offset);
                    $vals = preg_replace('/[-+]/', '', $offset);

                    $b = preg_replace('/[A-Za-z]/', '', $bf_date);
                    $l = preg_replace('/[A-Za-z]/', '', $lnh_date);
                    $d = preg_replace('/[A-Za-z]/', '', $dnr_date);
                    //echo $vals;die;
                    if ($offset) {
                        //print_r($bf_date);die;
                        date_default_timezone_set('UTC');
                        if ($vals < 9) {
                            $vals = str_replace('0', '', $vals);
                        }


                        if (trim($operator) == '-') {
                            $brk = date('H:ia', strtotime($b) + $vals * 60 * 60);
                            $lunch = date('H:ia', strtotime($l) + $vals * 60 * 60);
                            $dinner = date('H:ia', strtotime($d) + $vals * 60 * 60);
                        } elseif (trim($operator) == '+') {
                            $brk = date('H:ia', strtotime($b) - $vals * 60 * 60);
                            $lunch = date('H:ia', strtotime($l) - $vals * 60 * 60);
                            $dinner = date('H:ia', strtotime($d) - $vals * 60 * 60);
                        } else {
                            $brk = $bf_date;
                            $lunch = $lnh_date;
                            $dinner = $dnr_date;
                        }

                        $Additional_Fields_Array['Breakfast']['Field_Value'] = $brk;
                        $Additional_Fields_Array['Lunch']['Field_Value'] = $lunch;
                        $Additional_Fields_Array['Dinner']['Field_Value'] = $dinner;
                    }
                }
                //Custom code end

                //only update First name Last name
                $Additional_Fields_Array = array_intersect_key($Additional_Fields_Array, array_flip(array('First Name', 'Last Name')));

                foreach ($Additional_Fields_Array as $Field) {
                    $CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'",
                        $Field['Field_ID'], $User_ID));
                    if ($CurrentField->User_Field_ID != "") {
                        $user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                            $Field['Field_Value']);
                    } else {
                        $user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'],
                            $Field['Field_Value'], $date);
                    }
                }
            }
            if ($_POST['ewd-feup-action'] == "edit-account") {
                CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
            }
        }
        $user_update = array("Message_Type" => "Update", "Message" => $user_update);
        $feup_success = true;
        //        Brian3T sends sms here
        //find uid
        if ($_POST['ewd-feup-action'] == "register") {
            $sql = "SELECT `User_ID` FROM `wp_ewd_feup_users` ORDER BY User_ID DESC LIMIT 1";
            $result = $wpdb->get_results($sql);

            if (isset($result[0]->User_ID)) {
                global $uid;
                $uid = $result[0]->User_ID;
                call_send_signup_mms($uid);
            }
        } else {
            session_start();
            unset ($_SESSION['first_sms_sent_to']);
        }

        return $user_update;
    } /* Return any error that might have occurred */
    else {
        $output_error = array("Message_Type" => "Error", "Message" => $error);

        return $output_error;
    }
}


function EWD_FEUP_Send_Email($User_Fields, $Additional_Fields_Array, $User_ID = 0)
{
    global $wpdb, $ewd_feup_user_table_name;
    $Admin_Email = get_option("EWD_FEUP_Admin_Email");
    $Email_Subject = get_option("EWD_FEUP_Email_Subject");
    $Encrypted_Admin_Password = get_option("EWD_FEUP_Admin_Password");
    $SMTP_Mail_Server = get_option("EWD_FEUP_SMTP_Mail_Server");
    $SMTP_Username = get_option("EWD_FEUP_SMTP_Username", "");
    $Message_Body = get_option("EWD_FEUP_Message_Body");
    $Email_Field = get_option("EWD_FEUP_Email_Field");
    $Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

    $Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");

    $key = 'EWD_FEUP';
    $Admin_Password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($Encrypted_Admin_Password),
        MCRYPT_MODE_CBC, md5(md5($key))), "\0");

    if ($Email_Confirmation == "Yes") {
        $ConfirmationCode = EWD_FEUP_RandomString();
        $PageLink = get_permalink($_POST['ewd-feup-post-id']);
        if (strpos($PageLink, "?") !== false) {
            $ConfirmationLink = $PageLink . "&User_ID=" . $User_ID . "&ConfirmationCode=" . $ConfirmationCode;
        } else {
            $ConfirmationLink = $PageLink . "?User_ID=" . $User_ID . "&ConfirmationCode=" . $ConfirmationCode;
        }
        $wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Confirmation_Code=%s",
            $ConfirmationCode));
    }

    $Message_Body = str_replace("[username]", $User_Fields['Username'], $Message_Body);
    $Message_Body = str_replace("[password]", $_POST['User_Password'], $Message_Body);
    $Message_Body = str_replace("[join-date]", $User_Fields['User_Date_Created'], $Message_Body);
    if ($Email_Confirmation == "Yes") {
        $Message_Body = str_replace("[confirmation-link]", $ConfirmationLink, $Message_Body);
    }

    $Email_Field = str_replace(" ", "_", $Email_Field);
    if ($Username_Is_Email == "Yes") {
        $User_Email = $User_Fields['Username'];
    } else {
        $User_Email = $Additional_Fields_Array[$Email_Field]['Field_Value'];
    }
    //brian3t skips foobar
    if (strpos("foobar.com", $User_Email) == false) {
        return;
    }

    if ($SMTP_Mail_Server != "") {
        require_once(EWD_FEUP_CD_PLUGIN_PATH . '/PHPMailer/class.phpmailer.php');
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            $mail->IsSMTP();
            $mail->Host = $SMTP_Mail_Server;
            $mail->SMTPAuth = true;
            $mail->Username = $SMTP_Username == "" ? $Admin_Email : $SMTP_Username;
            $mail->Password = $Admin_Password;
            $mail->WordWrap = 0;
            $mail->AddCustomHeader('X-Mailer: EWD_FEUP v1.0');
            $mail->SetFrom($Admin_Email);
            $mail->AddAddress($User_Email);
            $mail->Subject = $Email_Subject;
            $mail->Body = $Message_Body;
            $mail->isHTML(true);
            //$mail->AltBody = $Text;
            if (!$mail->Send()) {
                //echo "Email not sent.<br>";
                //echo $mail->ErrorInfo;
            } else {
                //echo "Email sent.<br>";
            }
        } catch (phpmailerException $e) {
            //echo "FAIL:\n";
            //echo $e->errorMessage(); // from PHPMailer
        } catch (Exception $e) {
            //echo "FAIL:\n";
            //echo $e->getMessage(); // from anything else!
        }
    } else {
        $headers = 'From: ' . $Admin_Email . "\r\n" .
            'Reply-To: ' . $Admin_Email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $Mail_Success = mail($User_Email, $Email_Subject, $Message_Body, $headers);
    }
}

function Handle_File_Upload($Field_Name)
{
    $error = '';
    $msg = '';

    /* Test if there is an error with the uploaded file and return that error if there is */
    if (!empty($_FILES[$Field_Name]['error'])) {
        switch ($_FILES[$Field_Name]['error']) {
            case '1':
                $error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'EWD_FEUP');
                break;
            case '2':
                $error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                    'EWD_FEUP');
                break;
            case '3':
                $error = __('The uploaded file was only partially uploaded', 'EWD_FEUP');
                break;
            case '4':
                $error = __('No file was uploaded.', 'EWD_FEUP');
                break;

            case '6':
                $error = __('Missing a temporary folder', 'EWD_FEUP');
                break;
            case '7':
                $error = __('Failed to write file to disk', 'EWD_FEUP');
                break;
            case '8':
                $error = __('File upload stopped by extension', 'EWD_FEUP');
                break;
            case '999':
            default:
                $error = __('No error code avaiable', 'EWD_FEUP');
        }
    } /* Make sure that the file exists */
    elseif (empty($_FILES[$Field_Name]['tmp_name']) || $_FILES[$Field_Name]['tmp_name'] == 'none') {
        $error = __('No file was uploaded here..', 'EWD_FEUP');
    } /* Move the file and store the URL to pass it onwards*/
    else {
        $msg .= $_FILES[$Field_Name]['name'];
        //for security reason, we force to remove all uploaded file
        $target_path = ABSPATH . 'wp-content/uploads/ewd-feup-user-uploads/';

        //create the uploads directory if it doesn't exist
        if (!file_exists($target_path)) {
            mkdir($target_path, 0777, true);
        }

        $Random = EWD_FEUP_RandomString();
        $target_path = $target_path . $Random . basename($_FILES[$Field_Name]['name']);

        if (!move_uploaded_file($_FILES[$Field_Name]['tmp_name'], $target_path)) {
            //if (!$upload = wp_upload_bits($_FILES["Item_Image"]["name"], null, file_get_contents($_FILES["Item_Image"]["tmp_name"]))) {
            $error .= "There was an error uploading the file, please try again!";
        } else {
            $User_Upload_File_Name = $Random . basename($_FILES[$Field_Name]['name']);
        }
    }

    /* Return the file name, or the error that was generated. */
    if (isset($error) and $error == __('No file was uploaded.', 'EWD_FEUP')) {
        $Return['Success'] = "N/A";
        $Return['Data'] = __('No file was uploaded.', 'EWD_FEUP');
    } elseif (!isset($error)) {
        $Return['Success'] = "Yes";
        $Return['Data'] = $User_Upload_File_Name;
    } else {
        $Return['Success'] = "No";
        $Return['Data'] = $error;
    }

    return $Return;
}

function EWD_FEUP_RandomString($CharLength = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0;$i < $CharLength;$i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }

    return $randstring;
}

function EWD_FEUP_Mass_User_Action()
{
    if ($_POST['action'] == "delete") {
        $update = Mass_Delete_EWD_FEUP_Users();
    } elseif ($_POST['action'] == "approve") {
        $update = Mass_Approve_EWD_FEUP_Users();
    } elseif ($_POST['action'] == "-1") {
    } else {
        $update = Mass_Assign_Levels_EWD_FEUP_Users();
    }

    return $update;
}

function Mass_Delete_EWD_FEUP_Users()
{
    $Users = $_POST['Users_Bulk'];

    if (is_array($Users)) {
        foreach ($Users as $User) {
            if ($User != "") {
                Delete_EWD_FEUP_User($User);
            }
        }
    }

    $update = __("Users have been successfully deleted.", 'EWD_FEUP');
    $user_update = array("Message_Type" => "Update", "Message" => $update);

    return $user_update;
}

function Mass_Approve_EWD_FEUP_Users()
{
    global $wpdb, $ewd_feup_user_table_name;
    $Users = $_POST['Users_Bulk'];

    if (is_array($Users)) {
        foreach ($Users as $User) {
            $Users = $wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Admin_Approved='Yes' WHERE User_ID=%d",
                $User));
        }
    }

    $update = __("Users have been successfully approved.", 'EWD_FEUP');
    $user_update = array("Message_Type" => "Update", "Message" => $update);

    return $user_update;
}

function Mass_Assign_Levels_EWD_FEUP_Users()
{
    global $wpdb, $ewd_feup_user_table_name;
    $Users = $_POST['Users_Bulk'];

    if (is_array($Users)) {
        foreach ($Users as $User) {
            $Users = $wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET Level_ID=%d WHERE User_ID=%d",
                $_POST['action'], $User));
        }
    }

    $update = __("User levels have been successfully updated.", 'EWD_FEUP');
    $user_update = array("Message_Type" => "Update", "Message" => $update);

    return $user_update;
}

function Delete_All_EWD_FEUP_Users()
{
    global $wpdb, $ewd_feup_user_table_name;
    $Users = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");

    if (is_array($Users)) {
        foreach ($Users as $User) {
            if ($User->User_ID != "") {
                Delete_EWD_FEUP_User($User->User_ID);
            }
        }
    }

    $update = __("Users have been successfully deleted.", 'EWD_OTP');
    $user_update = array("Message_Type" => "Update", "Message" => $update);

    return $user_update;
}

function Add_Edit_Field()
{
    global $wpdb, $ewd_feup_fields_table_name;

    $Field_ID = stripslashes_deep($_POST['Field_ID']);
    $Field_Name = stripslashes_deep($_POST['Field_Name']);
    $Field_Type = stripslashes_deep($_POST['Field_Type']);
    $Field_Description = stripslashes_deep($_POST['Field_Description']);
    $Field_Options = stripslashes_deep($_POST['Field_Options']);
    $Field_Show_In_Admin = stripslashes_deep($_POST['Field_Show_In_Admin']);
    $Field_Show_In_Front_End = stripslashes_deep($_POST['Field_Show_In_Front_End']);
    $Field_Required = stripslashes_deep($_POST['Field_Required']);

    $Field_Date_Created = date("Y-m-d H:i:s");

    if (!isset($error)) {
        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
        if ($_POST['action'] == "Add_Field") {
            $user_update = Add_EWD_FEUP_Field($Field_Name, $Field_Type, $Field_Description, $Field_Options,
                $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required,
                $Field_Date_Created);
        } /* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
        else {
            $user_update = Edit_EWD_FEUP_Field($Field_ID, $Field_Name, $Field_Type, $Field_Description, $Field_Options,
                $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required);
        }
        $user_update = array("Message_Type" => "Update", "Message" => $user_update);

        return $user_update;
    } /* Return any error that might have occurred */
    else {
        $output_error = array("Message_Type" => "Error", "Message" => $error);

        return $output_error;
    }
}

function Mass_Delete_EWD_FEUP_Fields()
{
    $Fields = $_POST['Fields_Bulk'];

    if (is_array($Fields)) {
        foreach ($Fields as $Field) {
            if ($Field != "") {
                Delete_EWD_FEUP_Field($Field);
            }
        }
    }

    $update = __("Fields have been successfully deleted.", 'EWD_FEUP');
    $user_update = array("Message_Type" => "Update", "Message" => $update);

    return $user_update;
}

function Add_Edit_Level()
{
    $Level_ID = $_POST['Level_ID'];
    $Level_Name = $_POST['Level_Name'];
    $Level_Privilege = $_POST['Level_Privilege'];

    $Level_Date_Created = date("Y-m-d H:i:s");

    if (!isset($error)) {
        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
        if ($_POST['action'] == "Add_Level") {
            $user_update = Add_EWD_FEUP_Level($Level_Name, $Level_Privilege, $Level_Date_Created);
        } /* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
        else {
            $user_update = Edit_EWD_FEUP_Level($Level_ID, $Level_Name, $Level_Privilege, $Level_Date_Created);
        }
        $user_update = array("Message_Type" => "Update", "Message" => $user_update);

        return $user_update;
    } /* Return any error that might have occurred */
    else {
        $output_error = array("Message_Type" => "Error", "Message" => $error);

        return $output_error;
    }
}

function Mass_Delete_EWD_FEUP_Levels()
{
    $Levels = $_POST['Levels_Bulk'];

    if (is_array($Levels)) {
        foreach ($Levels as $Level) {
            if ($Level != "") {
                Delete_EWD_FEUP_Level($Level);
            }
        }
    }

    $update = __("Fields have been successfully deleted.", 'EWD_FEUP');
    $user_update = array("Message_Type" => "Update", "Message" => $update);

    return $user_update;
}

?>
