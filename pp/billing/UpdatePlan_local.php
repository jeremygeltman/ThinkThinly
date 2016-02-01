<?php

// # Update a plan
//
// This sample code demonstrate how you can update a billing plan, as documented here at:
// https://developer.paypal.com/webapps/developer/docs/api/#update-a-plan
// API used:  /v1/payments/billing-plans/<Plan-Id>

// ### Making Plan Active
// This example demonstrate how you could activate the Plan.

// Retrieving the Plan object from Create Plan Sample to demonstrate the List
/** @var Plan $createdPlan */
//$plan_id = 'P-2XM09435HG6440939WL5BD4A';//regular monthly plan
//$plan_id = 'P-9HD4220805509340JP6WL4SI';//monthly plan with trial 0 local
$plan_id = 'P-5MU72957XL552813HTO4HVHA';//monthly plan with trial 0 thinkthin

require_once('../bootstrap.php');

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

try {
    $patch = new Patch();

    $value = new PayPalModel('{
	       "state":"ACTIVE"
	     }');

    $patch->setOp('replace')
          ->setPath('/')
          ->setValue($value);
    $patchRequest = new PatchRequest();
    $patchRequest->addPatch($patch);

    $createdPlan = new \PayPal\Api\Plan();
    $createdPlan = $createdPlan->get($plan_id, $apiContext);
    $createdPlan->update($patchRequest, $apiContext);

    $plan = Plan::get($createdPlan->getId(), $apiContext);

} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    ResultPrinter::printError("Updated the Plan to Active State", "Plan", null, $patchRequest, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
ResultPrinter::printResult("Updated the Plan to Active State", "Plan", $plan->getId(), $patchRequest, $plan);

return $plan;
