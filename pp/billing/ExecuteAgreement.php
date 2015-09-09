<?php

// #Execute Agreement
// This is the second part of CreateAgreement Sample.
// Use this call to execute an agreement after the buyer approves it
require __DIR__ . '/../bootstrap.php';
require_once dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'wp-blog-header.php';

// ## Approval Status
// Determine if the user accepted or denied the request
if (isset($_GET['success']) && $_GET['success'] == 'true') {

    $token = $_GET['token'];
    $agreement = new \PayPal\Api\Agreement();
    try {
        // ## Execute Agreement
        // Execute the agreement by passing in the token
        $agreement->execute($token, $apiContext);
    } catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 	    ResultPrinter::printError("Executed an Agreement", "Agreement", $agreement->getId(), $_GET['token'], $ex);
        exit(1);
    }

    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//    ResultPrinter::printResult("Executed an Agreement", "Agreement", $agreement->getId(), $_GET['token'], $agreement);

    // ## Get Agreement
    // Make a get call to retrieve the executed agreement details
    try {
        $agreement = \PayPal\Api\Agreement::get($agreement->getId(), $apiContext);
        $desc = $agreement->getDescription();
        //ThinkThinly monthly subscription. ID: 245
        preg_match('/^[^\d]*(\d+)$/', $desc, $matches);
        $user_id = $matches[1];

        /** @var  $query */
        $query = "UPDATE `wp_ewd_feup_users` SET `subscription`='active' WHERE User_ID = $user_id;";
        $num_row = $wpdb->query($query);
        if ($num_row === false){
            error_log("Failed to execute query $query \n", 3, $error_file_name);
        }
        session_start();
        $_SESSION['user_updated'] = "Thank you. Your subscription is active";

        if(!headers_sent()) header("Location: /your-settings");

    } catch (Exception $ex) {
        json_encode(array('status'=>'error', 'agreement'=>$agreement->toJSON(128)));
        exit(1);
    }

    //update user table



} else {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    echo json_encode(array('status'=>'error', '_GET'=>$_GET));
}
if(!headers_sent()) {
    $baseUrl = getBaseUrl();
    header("Location: $baseUrl/your-settings");
}
