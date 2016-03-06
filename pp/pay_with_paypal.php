<?php
require_once dirname(__DIR__) . "/config.php";
$url = get_pay_with_paypal_url($_GET['user_id']);

if ($url) {
    header("Location: $url");
} else {
    header("Location: http://thinkthinly.com");
}
