<?php
/** @var $wpdb */

require_once(dirname(__FILE__) . '/wp-blog-header.php');
$error_file_name = __DIR__ . DIRECTORY_SEPARATOR . "error_log";
require_once('vendor/autoload.php');


//check magic word
if (! array_key_exists("secret_key", $_GET) || ($_GET['secret_key'] != 'e2e697afc5ebee779eb383238b95b92e')) {
    mail('someids@gmail.com', "Improper request from thinkthinly.com", "Request from " . json_encode($_SERVER) . " POST is " . json_encode($_POST));

//    echo "Improper request from thinkthinly.com", "Request from " . json_encode($_SERVER) . " GET is " . json_encode($_GET);
    return;
}
$now = (new DateTime());
$now = $now->format('m-d h:i:s');

error_log("Send mms called" . $now, 3, $error_file_name);

require "Services/Twilio.php";
$AccountSid = "ACddcce2ed6943c1bd04b0642fab6b2f3f";
$AuthToken  = "1542d1f8621777361d4d0332d1f8ec4c";
$client     = new Services_Twilio($AccountSid, $AuthToken);

$old_date_def_timezone = date_default_timezone_get();
date_default_timezone_set('UTC');

$current_time = (new DateTime())->modify('+15 minutes');

define('DEBUG_DONT_SEND_SMS', false);

if (DEBUG_DONT_SEND_SMS) {
//    $current_time = ((new DateTime())->setTimezone((new DateTimeZone('UTC')))->setTime(21, 2));
    $current_time = ((new DateTime())->setTimezone((new DateTimeZone('UTC')))->setTime(16, 0));
}

$time_cst = clone $current_time;
$time_est = clone $current_time;
$time_mst = clone $current_time;
$time_pst = clone $current_time;

$time_pst->setTimezone(new DateTimeZone('America/Los_Angeles'));
$time_mst->setTimezone(new DateTimeZone('America/Denver'));
$time_cst->setTimezone(new DateTimeZone('America/Chicago'));
$time_est->setTimezone(new DateTimeZone('America/New_York'));
array_map(function (&$v) {
    /** @var DateTime $v */
    $v = $v->format('h:ia');
}, array(&$time_cst, &$time_est, &$time_mst, &$time_pst));

$today = (new DateTime())->format('Y-m-d');

$user_expired = $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Membership Expiry Date' and uf.Field_Value < '$today'");

$user_ids_cst         = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'CST'"));
$user_ids_est         = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'EST'"));
$user_ids_mst         = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'MST'"));
$user_ids_pst         = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'PST'"));
$user_ids_cst_current = array();
if (! empty($user_ids_cst)) {
    $user_ids_cst_current = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_cst) AND Field_Value = '$time_cst'");
}
$user_ids_est_current = array();
if (! empty($user_ids_est)) {
    $user_ids_est_current = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_est) AND Field_Value = '$time_est'");
}
$user_ids_mst_current = array();
if (! empty($user_ids_mst)) {
    $user_ids_mst_current = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_mst) AND Field_Value = '$time_mst'");
}
$user_ids_pst_current = array();
if (! empty($user_ids_pst)) {
    $user_ids_pst_current = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_pst) AND Field_Value = '$time_pst'");
}
$user_ids_all_current = array_merge($user_ids_cst_current, $user_ids_est_current, $user_ids_mst_current, $user_ids_pst_current);
$user_ids_all_current = array_diff($user_ids_all_current, $user_expired);
if (empty($user_ids_all_current)) {
    return;
}
$user_ids_all_current = implode(',', $user_ids_all_current);

$users = $wpdb->get_results("SELECT Field_Value,u.User_ID FROM `wp_ewd_feup_users` as u JOIN `wp_ewd_feup_user_fields` as uf on u.User_ID = uf.User_ID where uf.Field_Name = 'Phone' and u.User_ID in ($user_ids_all_current)");

foreach ($users as $user) {
    $ok_to_sms = $wpdb->get_results("SELECT `Field_Value` FROM `wp_ewd_feup_user_fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'OK to receive texts?'");
    if (isset($ok_to_sms) && trim($ok_to_sms[0]->Field_Value) == 'No') {
        continue;
    }

    $user_id   = $user->User_ID;
    $user_info = $wpdb->get_results("SELECT `Field_Name`, `Field_Value` from `wp_ewd_feup_user_fields` WHERE `User_ID` = $user_id ");
    ah_flatten($user_info, 'Field_Name');

    foreach ($user_info as $k => $u_i) {
        $temp          = $u_i['Field_Value'];
        $user_info[$k] = $temp;
    }
    $time_zone = $user_info['Time zone'];
    switch ($time_zone) {
        case 'CST':
            $meal = array_search($time_cst, $user_info);
        case 'EST':
            $meal = array_search($time_est, $user_info);
        case 'MST':
            $meal = array_search($time_mst, $user_info);
        case 'PST':
            $meal = array_search($time_pst, $user_info);
    }
    $meal    = strtolower($meal);
    $meal[0] = strtoupper($meal[0]);

    $gender    = strtolower($user_info['Gender']);
    $gender[0] = strtoupper($gender[0]);

    $args     = array(
        'post_type' => 'mms-template',
        'post_status' => 'publish',
        'posts_per_page' => - 1,
        'caller_get_posts' => 1,
        'orderby' => 'name',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'meal',
                'field' => 'slug',
                'terms' => array($meal),
            ),
            array(
                'taxonomy' => 'meal',
                'field' => 'slug',
                'terms' => array($gender),
            ),
        ),
    );
    $my_query = new WP_Query($args);

    $meal = strtolower($meal);
    $num_of_posts = sizeof($my_query->posts);
    $post_index = -1;
    $result = $wpdb->get_row("SELECT " . $meal. " FROM wp_user_mms_sent WHERE user_id = $user_id LIMIT 1", ARRAY_A);
    if (isset($result[$meal])){
        $post_index = $result[$meal];
    }

    $post_to_send = $my_query->posts[++$post_index];
    $image        = wp_get_attachment_image_src(get_post_thumbnail_id($post_to_send->ID), 'large');
    $image[0]     = str_replace("10.0.0.116", "thinkthinly.com", $image[0]);
    $image[0]     = str_replace("10.0.0.134", "thinkthinly.com", $image[0]);
    $image[0]     = str_replace("localhost", "thinkthinly.com", $image[0]);

    //MMS
    if (DEBUG_DONT_SEND_SMS) {
        echo "\nSending this message" . $post_to_send->ID . " " . $post_to_send->post_excerpt . " " . $image[0] . " to this user:";
        var_dump($user);
    } else {
        $sms_sent = $client->account->messages->sendMessage(
            "+16194190679",
            $user_info['Phone'],
            $post_to_send->post_excerpt,
            array($image[0])
        );
    }

    wp_reset_postdata();
    if (!empty($sms_sent->sid) || DEBUG_DONT_SEND_SMS){
        echo "Message Sent By Twilio: ID- {$sms_sent->sid}";
        $wpdb->update("wp_user_mms_sent", [$meal=>$post_index], ["user_id" => $user_id] );
    }
}
date_default_timezone_set($old_date_def_timezone);
?>
