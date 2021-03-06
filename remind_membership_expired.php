<?php
/** @var $wpdb */

define('NUM_OF_DAYS', 1);

/**
 * Send sms to expired people
 * This should trigger NUM_OF_DAYS day after membership expired.
 */

require_once(dirname(__FILE__) . '/wp-blog-header.php');
require_once('config.php');
/** @var string $bl_api_url
 * @var string $bl_token
 */
$error_file_name = __DIR__ . DIRECTORY_SEPARATOR . "error_log";

//check magic word
if (! array_key_exists("secret_key", $_GET) || ($_GET['secret_key'] != 'e2e697afc5ebee779eb383238b95b92e')) {
    mail('someids@gmail.com', "Improper request from thinkthinly.com", "Request from " . json_encode($_SERVER) . " POST is " . json_encode($_POST));
//    echo "Improper request from thinkthinly.com", "Request from " . json_encode($_SERVER) . " GET is " . json_encode($_GET);
    return;
}

require "Services/Twilio.php";
$AccountSid = "ACddcce2ed6943c1bd04b0642fab6b2f3f";
$AuthToken = "1542d1f8621777361d4d0332d1f8ec4c";
$client = new Services_Twilio($AccountSid, $AuthToken);

$old_date_def_timezone = date_default_timezone_get();
date_default_timezone_set('UTC');

//based on cronjob this should be 16:00
$current_time = (new DateTime('now'))->sub(new DateInterval('P' . NUM_OF_DAYS. 'D'));

if (strpos($_SERVER['SERVER_NAME'], 'localhost') !== false) {
    define('DEBUG_DONT_SEND_SMS', true);
} else {
    define('DEBUG_DONT_SEND_SMS', false);
}

if (DEBUG_DONT_SEND_SMS) {
//    $current_time = (new DateTime('now'))->setDate(2016, 2, 12)->sub(new DateInterval('P1D'));
    // $current_time = ((new DateTime())->setTimezone((new DateTimeZone('UTC')))->setTime(16,0));
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
    $v = $v->format('Y-m-d');
}, array(&$time_cst, &$time_est, &$time_mst, &$time_pst));

$user_ids_cst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'CST'"));
$user_ids_est = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'EST'"));
$user_ids_mst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'MST'"));
$user_ids_pst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'PST'"));
$user_ids_cst_expired = array();
if (! empty($user_ids_cst)) {
    $user_ids_cst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_cst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_cst'");
}
$user_ids_est_expired = array();
if (! empty($user_ids_est)) {
    $user_ids_est_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_est) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_est'");
}
$user_ids_mst_expired = array();
if (! empty($user_ids_mst)) {
    $user_ids_mst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_mst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_mst'");
}
$user_ids_pst_expired = array();
if (! empty($user_ids_pst)) {
    $user_ids_pst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_pst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_pst'");
}
$user_ids_all_expired = implode(",", array_merge($user_ids_cst_expired, $user_ids_est_expired, $user_ids_mst_expired, $user_ids_pst_expired));

error_log("Sending mms expired yesterday. Time pst: " . $time_pst . " All users expired: " . json_encode($user_ids_all_expired) . "\n\n", 3, $error_file_name);
if (! empty($user_ids_all_expired)) {


    $users = $wpdb->get_results("SELECT Field_Value,u.User_ID FROM `wp_ewd_feup_users` as u JOIN `wp_ewd_feup_user_fields` as uf on u.User_ID = uf.User_ID where uf.Field_Name = 'Phone' and u.User_ID in ($user_ids_all_expired)");

    $args = array(
        'posts_per_page' => - 1,
        'tax_query' => array(
            array(
                'taxonomy' => 'post_tag',
                'field' => 'name',
                'terms' => '1_day',
            ),
        ),
        'category_name' => 'expired'
    );
    $query = new WP_Query($args);
    $template_expired_1_day = $query->post;

    /** @var WP_POST $template_expired_1_day */
    foreach ($users as $user) {
        $bl_link = get_bit_ly_url($user->User_ID);
        $content_to_send = $template_expired_1_day->post_content;
        if (! empty($bl_link) && ! is_array($bl_link)) {
            $content_to_send .= $bl_link;
        } else {
            error_log("Can not get bitly link. User id: " . $user->User_ID, 3, $error_file_name);
        }

        $image = wp_get_attachment_image_src(get_post_thumbnail_id($template_expired_1_day->ID), 'large');
        $image[0] = str_replace("10.0.0.116", "thinkthinly.com", $image[0]);
        $image[0] = str_replace("10.0.0.134", "thinkthinly.com", $image[0]);
        $image[0] = str_replace("localhost", "thinkthinly.com", $image[0]);
        //MMS
        if (DEBUG_DONT_SEND_SMS) {
            echo "\nSending above message <pre>" . print_r($template_expired_1_day, true) . " </pre> content: $content_to_send to this user:
            <pre>" . print_r($user, true) . "</pre>";
        } else {
            if (! empty($image[0])) {
                $sms = $client->account->messages->sendMessage(
                    "+16194190679",
                    $user->Field_Value,
                    $content_to_send,
                    array($image[0])
                );
            } else {
                $sms = $client->account->messages->sendMessage(
                    "+16194190679",
                    $user->Field_Value,
                    $content_to_send
                );
            }

            echo "Message Sent: ID- {$sms->sid}";
        }

    }
}

/* 3 days */
// cron job should already set this to 16:00
$current_time = (new DateTime('now'))->sub(new DateInterval('P3D'));

if (DEBUG_DONT_SEND_SMS) {
    $current_time = (new DateTime('now'))->setTime(16, 0)->sub(new DateInterval('P3D'));
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
    $v = $v->format('Y-m-d');
}, array(&$time_cst, &$time_est, &$time_mst, &$time_pst));

$user_ids_cst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'CST'"));
$user_ids_est = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'EST'"));
$user_ids_mst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'MST'"));
$user_ids_pst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'PST'"));
$user_ids_cst_expired = array();
if (! empty($user_ids_cst)) {
    $user_ids_cst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_cst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_cst'");
}
$user_ids_est_expired = array();
if (! empty($user_ids_est)) {
    $user_ids_est_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_est) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_est'");
}
$user_ids_mst_expired = array();
if (! empty($user_ids_mst)) {
    $user_ids_mst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_mst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_mst'");
}
$user_ids_pst_expired = array();
if (! empty($user_ids_pst)) {
    $user_ids_pst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_pst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$time_pst'");
}
$user_ids_all_expired = implode(",", array_merge($user_ids_cst_expired, $user_ids_est_expired, $user_ids_mst_expired, $user_ids_pst_expired));
if (empty($user_ids_all_expired)) {
    return;
}
error_log("Sending mms expired 3 days ago. Time pst: " . $time_pst . " All users expired: " . json_encode($user_ids_all_expired) . "\n\n", 3, $error_file_name);
$users = $wpdb->get_results("SELECT Field_Value,u.User_ID FROM `wp_ewd_feup_users` as u JOIN `wp_ewd_feup_user_fields` as uf on u.User_ID = uf.User_ID where uf.Field_Name = 'Phone' and u.User_ID in ($user_ids_all_expired)");

$args = array(
    'posts_per_page' => - 1,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field' => 'name',
            'terms' => '3_day',
        ),
    ),
    'category_name' => 'expired'
);
$query = new WP_Query($args);
$template_expired_3_day = $query->post;
/** @var WP_POST $template_expired_3_day */
foreach ($users as $user) {
    $bl_link = get_bit_ly_url($user->User_ID);
    $content_to_send = $template_expired_3_day->post_content;
    if (! empty($bl_link) && ! is_array($bl_link)) {
        $content_to_send .= $bl_link;
    } else {
        error_log("Can not get bitly link. User id: " . $user->User_ID, 3, $error_file_name);
    }

    $image = wp_get_attachment_image_src(get_post_thumbnail_id($template_expired_3_day->ID), 'large');
    $image[0] = str_replace("10.0.0.116", "thinkthinly.com", $image[0]);
    $image[0] = str_replace("10.0.0.134", "thinkthinly.com", $image[0]);
    $image[0] = str_replace("localhost", "thinkthinly.com", $image[0]);
    //MMS
    if (DEBUG_DONT_SEND_SMS) {
        echo "\nSending above message <pre>" . print_r($template_expired_3_day, true) . "</pre> content: $content_to_send to this user:";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } else {
        if (! empty($image[0])) {
            $sms = $client->account->messages->sendMessage(
                "+16194190679",
                $user->Field_Value,
                $content_to_send,
                array($image[0])
            );
        } else {
            $sms = $client->account->messages->sendMessage(
                "+16194190679",
                $user->Field_Value,
                $content_to_send
            );

        }

        echo "Message Sent: ID- {$sms->sid}";
    }

}

wp_reset_postdata();

date_default_timezone_set($old_date_def_timezone);
