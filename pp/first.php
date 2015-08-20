<?php

// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
require __DIR__  . '/vendor/autoload.php';

// 2. Provide your Secret Key. Replace the given one with your app clientId, and Secret
// https://developer.paypal.com/webapps/developer/applications/myapps
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AdpL_r75o2aPiN36_q-N00bHysXd_kciEqBC1fk6wJScCooh_H_c3yiQtslCtBrdOHxcCMp1wWrEgKq3',     // ClientID
        'EJUta11-1or7CxrEmbG0JuGLRk8TFbYSA9rqrLwACUoNtHo1eo4AJlEnDQcOc2GRYs3v1vlSy7LpRuSC'      // ClientSecret
    )
);

// 3. Lets try to save a credit card to Vault using Vault API mentioned here
// https://developer.paypal.com/webapps/developer/docs/api/#store-a-credit-card
$creditCard = new \PayPal\Api\CreditCard();
$creditCard->setType("visa")
           ->setNumber("4417119669820331")
           ->setExpireMonth("11")
           ->setExpireYear("2019")
           ->setCvv2("012")
           ->setFirstName("Joe")
           ->setLastName("Shopper");

// 4. Make a Create Call and Print the Card
try {
    $creditCard->create($apiContext);
    echo $creditCard;
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception. 
    //REALLY HELPFUL FOR DEBUGGING
    echo $ex->getData();
}