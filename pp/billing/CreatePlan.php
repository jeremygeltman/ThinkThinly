<?php

// # Create Plan Sample
//
// This sample code demonstrate how you can create a billing plan, as documented here at:
// https://developer.paypal.com/webapps/developer/docs/api/#create-a-plan
// API used: /v1/payments/billing-plans

require __DIR__ . '/../bootstrap.php';
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;

// Create a new instance of Plan object
$plan = new Plan();

// # Basic Information
// Fill up the basic information that is required for the plan
$plan->setName('ThinkThinly monthly subscription 0 dollar')
     ->setDescription('ThinkThinly monthly subscription 0 dollar')
     ->setType('fixed');

// # Payment definitions for this billing plan.
$paymentDefinition = new PaymentDefinition();

// The possible values for such setters are mentioned in the setter method documentation.
// Just open the class file. e.g. lib/PayPal/Api/PaymentDefinition.php and look for setFrequency method.
// You should be able to see the acceptable values in the comments.
$paymentDefinition->setName('Regular Payments')
                  ->setType('REGULAR')
                  ->setFrequency('Month')
                  ->setFrequencyInterval("1")
                  ->setCycles("12")
                  ->setAmount(new Currency(array('value' => 4.99, 'currency' => 'USD')));

// # Trial Payment definitions for this billing plan.
$trialPaymentDefinition = new PaymentDefinition();

// The possible values for such setters are mentioned in the setter method documentation.
// Just open the class file. e.g. lib/PayPal/Api/PaymentDefinition.php and look for setFrequency method.
// You should be able to see the acceptable values in the comments.
$trialPaymentDefinition->setName('Regular Payments')
                  ->setType('TRIAL')
                  ->setFrequency('Month')
                  ->setFrequencyInterval("1")
                  ->setCycles("1200")
                  ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));

// Charge Models
//$chargeModel = new ChargeModel();
//$chargeModel->setType('SHIPPING')
//            ->setAmount(new Currency(array('value' => 10, 'currency' => 'USD')));

//$paymentDefinition->setChargeModels(array($chargeModel));

$merchantPreferences = new MerchantPreferences();
$baseUrl = getBaseUrl();
// ReturnURL and CancelURL are not required and used when creating billing agreement with payment_method as "credit_card".
// However, it is generally a good idea to set these values, in case you plan to create billing agreements which accepts "paypal" as payment_method.
// This will keep your plan compatible with both the possible scenarios on how it is being used in agreement.
$merchantPreferences->setReturnUrl("$baseUrl/ExecuteAgreement.php?success=true")
                    ->setCancelUrl("$baseUrl/ExecuteAgreement.php?success=false")
                    ->setAutoBillAmount("yes")
                    ->setInitialFailAmountAction("CONTINUE")
                    ->setMaxFailAttempts("0");
//                    ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));

//$baseUrl = getBaseUrl();
//$redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true")
//             ->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");


$plan->setPaymentDefinitions(array($trialPaymentDefinition, $paymentDefinition));
$plan->setMerchantPreferences($merchantPreferences);

// For Sample Purposes Only.
$request = clone $plan;

// ### Create Plan
try {
    $output = $plan->create($apiContext);
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    ResultPrinter::printError("Created Plan", "Plan", null, $request, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
ResultPrinter::printResult("Created Plan", "Plan", $output->getId(), $request, $output);

return $output;
