<?php
require_once dirname(__FILE__) . '/../../../../vendor/braintree/braintree_php/lib/Braintree.php';
Braintree\Configuration::environment('production');
Braintree\Configuration::merchantId('n548vrvhgb7ff3jm');
Braintree\Configuration::publicKey('t639rjppfmt8ncmn');
Braintree\Configuration::privateKey('0b1344f7ceb652389173603af7b682c1');

/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Subscribe_Now($atts) {
    /** @var string $redirect_page
     * @var string $login_page
     * @var string $Time
     * @var string $Salt
     * @var string $omit_fields
     */
    // Include the required global variables, and create a few new ones
    global $wpdb, $user_message, $feup_success;
    global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;

    $CheckCookie = CheckLoginCookie();
    
    $Sql = "SELECT * FROM wp_ewd_feup_fields WHERE Field_Show_In_Front_End='Yes' ORDER BY `Field_Order` ASC";
    $Fields = $wpdb->get_results($Sql);
    $User = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_ewd_feup_users WHERE Username='%s'",
        $CheckCookie['Username']));
    $UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_ewd_feup_user_fields WHERE User_ID='%d'",
        $User->User_ID));
    
    $user_id= $User->User_ID;
    
    $output = '';
    if (isset($_POST["payment_method_nonce"])) {
        $nonce = $_POST["payment_method_nonce"];
        $result = Braintree\Transaction::sale([
            'amount' => '5',
            'paymentMethodNonce' => $nonce,
            'options' => ['submitForSettlement' => true]
        ]);
        if ($result->success) {
            $query = "UPDATE `wp_ewd_feup_users` SET `subscription`='active' WHERE User_ID = $user_id;";
            $num_row = $wpdb->query($query);
            if ($num_row === false){
                error_log("Failed to execute query $query \n", 3);
            }

            $query = "UPDATE `wp_ewd_feup_user_fields` SET `Field_Value`=null WHERE `Field_Name` = 'Membership Expiry Date' AND User_ID = $user_id;";

            $num_row = $wpdb->query($query);
            if ($num_row === false){
                error_log("Failed to execute query $query \n", 3);
            }

            session_start();
            $_SESSION['user_updated'] = "Your subscription is now active.";

            if(!headers_sent()) header("Location: /you-did-it");
            error_log("success!: " . $result->transaction->id);
        } else if ($result->transaction) {
            $output .= print_r("Error processing transaction:", true);
            $output .= print_r("\n  code: " . $result->transaction->processorResponseCode, true);
            $output .= print_r("\n  text: " . $result->transaction->processorResponseText, true);
        } else {
            $output .= print_r("Validation errors: \n", true);
            $output .= print_r($result->errors->deepAll(), true);
        }

        return $output;
    }
    //else, render payment form
    $clientToken = \Braintree\ClientToken::generate();

    $output = '<form id="checkout" method="post" action="/subscribe-now">
                  <div id="payment-form"></div>
                  <input type="submit" value="Pay $5 Now">
                </form>

<script src="https://js.braintreegateway.com/js/braintree-2.22.2.min.js"></script>
<script>

var clientToken = "' . $clientToken . '";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});
</script>
';


    echo $output;

    return;

    $Custom_CSS = get_option("EWD_FEUP_Custom_CSS");


    $Sql = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY `FIELD_ORDER` ASC";
    $Fields = $wpdb->get_results($Sql);
    $User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'",
        $CheckCookie['Username']));
    $UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'",
        $User->User_ID));

    $ReturnString = "";

    $output = "";
    $output .= "<script> var \$user_id= $User->User_ID ; </script>";
    $ReturnString .= ($output);

    wp_enqueue_script(
        'your_settings',
        '/wp-content/js/subscribe_now.js',
        array('jquery')
    );

    return $ReturnString;
}

add_shortcode("subscribe_now", "Insert_Subscribe_Now");
?>