<?php
// #Execute Payment Sample
// This is the second step required to complete
// PayPal checkout. Once user completes the payment, paypal
// redirects the browser to "redirectUrl" provided in the request.
// This sample will show you how to execute the payment
// that has been approved by
// the buyer by logging into paypal site.
// You can optionally update transaction
// information by passing in one or more transactions.
// API used: POST '/v1/payments/payment/<payment-id>/execute'.

$error_file_name = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR."error_log";


require __DIR__ . '/bootstrap.php';
require_once(dirname(dirname(__FILE__)) . '/wp-blog-header.php');

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

// ### Approval Status
// Determine if the user approved the payment or not
if (isset($_GET['success']) && $_GET['success'] == 'true') {

    // Get the payment Object by passing paymentId
    // payment id was previously stored in session in
    // CreatePaymentUsingPayPal.php
    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $apiContext);

    // ### Payment Execute
    // PaymentExecution object includes information necessary
    // to execute a PayPal account payment.
    // The payer_id is added to the request query parameters
    // when the user is redirected from paypal back to your site
    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    // ### Optional Changes to Amount
    // If you wish to update the amount that you wish to charge the customer,
    // based on the shipping address or any other reason, you could
    // do that by passing the transaction object with just `amount` field in it.
    // Here is the example on how we changed the shipping to $1 more than before.
    $transaction = new Transaction();
    $amount = new Amount();
    $details = new Details();

    $amount->setCurrency('USD');

    $amount->setTotal($payment->getTransactions()[0]->amount->total);
    $amount->setDetails($details);
    $transaction->setAmount($amount);

    // Add the above transaction object inside our Execution object.
    $execution->addTransaction($transaction);

    try {
        // Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        $result = $payment->execute($execution, $apiContext);

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//        ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);

        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
// 	        ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
            exit(1);
        }
    } catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
// 	    ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
        exit(1);
    }

    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    //ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);
    //get number of months (quantity) and user id
    $months = 0;
    $first_item = $payment->getTransactions()[0]->item_list->items[0];
    if ($first_item->name != "30 day membership"){
        error_log("paypal item name incorrect: ". $first_item->name, 3, $error_file_name);
        return $payment;
    }
    $months = $first_item->quantity;
    $user_id = $first_item->sku;
    //extend user expiration date here

    /** @var  $query */
    $query = "UPDATE `wp_ewd_feup_user_fields` SET `Field_Value`=DATE_ADD(`Field_Value`, INTERVAL $months MONTH) WHERE User_ID = $user_id and Field_Name='Membership Expiry Date'";
    $num_row = $wpdb->query($query);
    if ($num_row === false){
        error_log("Failed to execute query $query \n", 3, $error_file_name);
    }
    session_start();
    $_SESSION['user_updated'] = "Thank you. Your membership has been extended";

    if(!headers_sent()) header("Location: /your-settings");


    return;
//    return $payment;


} else {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    ResultPrinter::printResult("User Cancelled the Approval", null);
    exit;
}
