<?php
/* Template Name: sendsignupmms*/
require "Services/Twilio.php";
$AccountSid = "ACddcce2ed6943c1bd04b0642fab6b2f3f";
$AuthToken  = "1542d1f8621777361d4d0332d1f8ec4c";
$client     = new Services_Twilio($AccountSid, $AuthToken);

$error_file_name = dirname(dirname(dirname(__DIR__))).DIRECTORY_SEPARATOR."error_log";

define('DEBUG_DONT_SEND_SMS', false);

date_default_timezone_set('UTC');
$current_time = date('H:ia', strtotime("+5 minutes"));

global $uid;
$userId    = $uid;
$userPhone = $wpdb->get_results("SELECT Field_Value,User_ID FROM `wp_ewd_feup_user_fields` where Field_Name = 'Phone' and User_ID = $userId");
if (count($userPhone) != 1) {
    error_log("No user phone found", 3, $error_file_name);
    die;
}
$userPhone = $userPhone[0]->Field_Value;
$gender    = $wpdb->get_results("SELECT Field_Value,User_ID FROM `wp_ewd_feup_user_fields` where Field_Name = 'Gender' and User_ID = $userId");
if (count($gender) != 1) {
    error_log("No gender found", 3, $error_file_name);
    die;
}
$gender = $gender[0]->Field_Value;
$gender = strtolower($gender);

$msg = $wpdb->get_results("SELECT `ID`, post_excerpt  FROM `wp_posts` WHERE `post_type`= 'mms-template' and `post_name` = 'sign-up-$gender' ");
if (count($msg) != 1) {
    error_log("Missing mms template", 3, $error_file_name);
    die;
}

$postId = $msg[0]->ID;

$b = 0;
$l = 0;
$d = 0;

$image         = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'large');
$image         = str_replace("10.0.0.116", "thinkthinly.com", $image);
$image         = str_replace("10.0.0.134", "thinkthinly.com", $image);
$image         = str_replace("localhost", "thinkthinly.com", $image);
$msg_to_send   = $msg[0]->post_excerpt;
$user_password = base64_encode(substr($userPhone, 5, 6));
$msg_to_send .= ". Your password is " . $user_password;
//MMS
try {
    $sms = $client->account->messages->sendMessage(
        "+16194190679",
        $userPhone,
        $msg_to_send,
        array($image[0])
    );
    session_start();
    $_SESSION['first_sms_sent_to'] = $userPhone;
    $_SESSION['message_count']     = 2;
} catch (Services_Twilio_RestException $e) {
    error_log($e->getMessage(), 3, $error_file_name);
}

$args = array(
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field'    => 'name',
            'terms'    => 'welcome',
        ),
    ),
    'category_name' => 'expired'
);
$query = new WP_Query( $args );
$template_expired_welcome = $query->post;

/** @var WP_POST $template_expired_welcome */

//MMS
if (DEBUG_DONT_SEND_SMS) {
    error_log("\nSending above message" . json_encode($template_expired_welcome) . " to this user:", 3, $error_file_name);
    var_dump($userPhone);
} else {
    try {
        $sms = $client->account->messages->sendMessage(
            "+16194190679",
            $userPhone,
            $template_expired_welcome->post_content,
            array()
        );
    } catch (Services_Twilio_RestException $e) {
        error_log($e->getMessage(),3 ,$error_file_name);
    }
}

wp_reset_postdata();
