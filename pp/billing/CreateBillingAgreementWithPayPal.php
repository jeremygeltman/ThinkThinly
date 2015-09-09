<?php

// # Create Billing Agreement with PayPal as Payment Source
//
// This sample code demonstrate how you can create a billing agreement, as documented here at:
// https://developer.paypal.com/webapps/developer/docs/api/#create-an-agreement
// API used: /v1/payments/billing-agreements

// Retrieving the Plan from the Create Update Sample. This would be used to
// define Plan information to create an agreement. Make sure the plan you are using is in active state.
/** @var Plan $createdPlan */

require_once '../bootstrap.php';

$plan_id = 'P-57V38520G74040029VY7DG6A';

use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;

extract($_GET);
/** @var int $user_id */
$agreement = new Agreement();

$now = new DateTime();
$now->add(DateInterval::createFromDateString('3 minute'));
$agreement->setName('Base Agreement')
    ->setDescription('ThinkThinly monthly subscription')
    ->setStartDate($now->format('Y-m-d'). 'T' . $now->format('G:i:s'). 'Z');
//    ->setStartDate('2019-06-17T9:45:04Z');

// Add Plan ID
// Please note that the plan Id should be only set in this case.
$plan = new Plan();
$plan->setId('P-3S275011LM709860PV3M2VQA');
$agreement->setPlan($plan);

// Add Payer
$payer = new Payer();
$payer->setPaymentMethod('paypal');
$agreement->setPayer($payer);

$agreement->setDescription("ThinkThinly monthly subscription. ID: $user_id");

// Add Shipping Address
//$shippingAddress = new ShippingAddress();
//$shippingAddress->setLine1('111 First Street')
//    ->setCity('Saratoga')
//    ->setState('CA')
//    ->setPostalCode('95070')
//    ->setCountryCode('US');
//$agreement->setShippingAddress($shippingAddress);

//$merchant_override_pref = new \PayPal\Api\MerchantPreferences();
//$merchant_override_pref->setReturnUrl(getBaseUrl(). "pp/billing/UpdateBillingAgreement.php?user_id=$user_id");
//$agreement->setOverrideMerchantPreferences($merchant_override_pref);

// For Sample Purposes Only.
$request = clone $agreement;

// ### Create Agreement
try {
    // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
    $agreement = $agreement->create($apiContext);

    // ### Get redirect url
    // The API response provides the url that you must redirect
    // the buyer to. Retrieve the url from the $agreement->getApprovalLink()
    // method
    $approvalUrl = $agreement->getApprovalLink();
    header('Content-Type: application/json');
    echo $agreement->toJSON(128);


} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    ResultPrinter::printError("Created Billing Agreement.", "Agreement", null, $request, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
// ResultPrinter::printResult("Created Billing Agreement. Please visit the URL to Approve.", "Agreement", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $agreement);
