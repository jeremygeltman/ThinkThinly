<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Subscribe_Now_0_Dollar($atts)
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

    $output      = "";
    $output .= "<script> var \$user_id= $User->User_ID ; </script>";
    $ReturnString .= ($output);

    wp_enqueue_script(
        'your_settings',
        '/wp-content/js/subscribe_now_0_dollar.js',
        array('jquery')
    );

    return $ReturnString;
}

add_shortcode("subscribe_now_0_dollar", "Insert_Subscribe_Now_0_Dollar");
?>