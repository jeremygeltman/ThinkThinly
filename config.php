<?php

// bitly
define('BL_TOKEN', 'd8f9f816ad1af15728039d881c4e1f356452858b');//http://dev.bitly.com/
define('BL_API_URL', 'https://api-ssl.bitly.com');
//$bl_shorten_url = '/v3/shorten';//?access_token=ACCESS_TOKEN & longUrl=http%3A%2F%2Fgoogle.com%2F & format=txt
// // bitly

function get_bit_ly_url($user_id){
    $bl_link = false;
    $ch = curl_init();

// set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "http://thinkthinly.com/pp/billing/CreateBillingAgreementWithPayPal.php?user_id=" . $user_id);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
    $result = curl_exec($ch);

// close cURL resource, and free up system resources
    curl_close($ch);
    $result = json_decode($result, ARRAY_A);
    $paypal_link = null;
    if ($result['plan']['state'] == "ACTIVE"){
        $paypal_link = $result['links'][0]['href'];
        $ch = curl_init();

// set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, BL_API_URL . "/v3/shorten" . "?" . http_build_query(['access_token' => BL_TOKEN , 'longUrl' => $paypal_link, 'format' => 'txt']));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $bl_link = curl_exec($ch);
        curl_close($ch);
    }

    return $bl_link;
}