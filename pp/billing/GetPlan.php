<?php
require __DIR__ . '/../bootstrap.php';
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;

// # Get Plan Sample
//
// This sample code demonstrate how you can get a billing plan, as documented here at:
// https://developer.paypal.com/webapps/developer/docs/api/#retrieve-a-plan
// API used: /v1/payments/billing-plans

// Retrieving the Plan object from Create Plan Sample
/** @var Plan $createdPlan */
$plan_id = 'P-2XM09435HG6440939WL5BD4A';

try {
    $plan = Plan::get($plan_id, $apiContext);
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 	ResultPrinter::printError("Retrieved a Plan", "Plan", $plan->getId(), null, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 ResultPrinter::printResult("Retrieved a Plan", "Plan", $plan->getId(), null, $plan);

return $plan;
