<?php
// Include the composer Autoloader
// The location of your project's vendor autoloader.
$composerAutoload = dirname(dirname(dirname(__DIR__))) . '/autoload.php';
if (! file_exists($composerAutoload)) {
    //If the project is used as its own project, it would use rest-api-sdk-php composer autoloader.
    $composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';

    if (! file_exists($composerAutoload)) {
        echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
        exit(1);
    }
}
require $composerAutoload;
require __DIR__ . '/common.php';

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

error_reporting(E_ALL);
ini_set('display_errors', '1');

//CONST
define('IS_SANDBOX', false);

$mode = 'live';
if (IS_SANDBOX) {
    $mode         = 'sandbox';
    $clientId     = 'AdpL_r75o2aPiN36_q-N00bHysXd_kciEqBC1fk6wJScCooh_H_c3yiQtslCtBrdOHxcCMp1wWrEgKq3';
    $clientSecret = 'EJUta11-1or7CxrEmbG0JuGLRk8TFbYSA9rqrLwACUoNtHo1eo4AJlEnDQcOc2GRYs3v1vlSy7LpRuSC';
} else {
// Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/webapps/developer/applications/myapps
//live cred
    $clientId     = 'AY7rStyj9hsTxKmRaNF33qil4RFvDzLD_ovULHhqGrOWOWuy9b-kkNNGfWg5eT4YsuEgZh7V4vSluypE';
    $clientSecret = 'EJSZ8J9mjaRJaob77W4Fs5rhSiyI-c2nAP2_lYLZFmxwT_im0y-mXRwCE_NQBSe7sdkw2ZPPh9Qi-pgk';
////live cred
}


/** @var \Paypal\Rest\ApiContext $apiContext */
$apiContext = getApiContext($clientId, $clientSecret, $mode);

return $apiContext;
/**
 * Helper method for getting an APIContext for all calls
 *
 * @param string $clientId Client ID
 * @param string $clientSecret Client Secret
 *
 * @return PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret, $mode)
{

    // #### SDK configuration
    // Register the sdk_config.ini file in current directory
    // as the configuration source.
    /*
    if(!defined("PP_CONFIG_PATH")) {
        define("PP_CONFIG_PATH", __DIR__);
    }
    */


    // ### Api context
    // Use an ApiContext object to authenticate
    // API calls. The clientId and clientSecret for the
    // OAuthTokenCredential class can be retrieved from
    // developer.paypal.com

    $apiContext = new ApiContext(
        new OAuthTokenCredential(
            $clientId,
            $clientSecret
        )
    );

    // Comment this line out and uncomment the PP_CONFIG_PATH
    // 'define' block if you want to use static file
    // based configuration

    $apiContext->setConfig(
        array(
            'mode' => $mode,
            'log.LogEnabled' => true,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'DEBUG', // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
            'cache.enabled' => true,
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
            // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
        )
    );

    // Partner Attribution Id
    // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
    // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
    // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

    return $apiContext;
}
