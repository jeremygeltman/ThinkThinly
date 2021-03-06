<?php
/** @var $wpdb */

require_once(dirname(__FILE__) . '/wp-blog-header.php');
$error_file_name = __DIR__ . DIRECTORY_SEPARATOR . "error_log";
require_once('vendor/autoload.php');
require_once('config.php');

$error_file_name = __DIR__ . DIRECTORY_SEPARATOR . "error_log";


//check magic word
if (!array_key_exists("secret_key", $_GET) || ($_GET['secret_key'] != 'e2e697afc5ebee779eb383238b95b92e')) {
    mail('someids@gmail.com', "Improper request from thinkthinly.com", "Request from " . json_encode($_SERVER) . " POST is " . json_encode($_POST));
//    echo "Improper request from thinkthinly.com", "Request from " . json_encode($_SERVER) . " GET is " . json_encode($_GET);
    return;
}
$now = (new DateTime());
$now = $now->format('m-d h:i:s');

require "Services/Twilio.php";
$AccountSid = "ACddcce2ed6943c1bd04b0642fab6b2f3f";
$AuthToken = "1542d1f8621777361d4d0332d1f8ec4c";
$client = new Services_Twilio($AccountSid, $AuthToken);

$old_date_def_timezone = date_default_timezone_get();
date_default_timezone_set('UTC');

$current_time = (new DateTime())->modify('-30 minutes');

if (strpos($_SERVER['SERVER_NAME'], 'thinkthinlocal') !== false) {
    define('DEBUG_DONT_SEND_SMS', true);
} else {
    define('DEBUG_DONT_SEND_SMS', false);
}

if (DEBUG_DONT_SEND_SMS) {
    $current_time->setTime(16, 0);
//    $current_time = ((new DateTime())->setTimezone((new DateTimeZone('UTC')))->setTime(16, 0));
}

function max_date($a, $b, $c)
{
    array_map(function (&$v) {
        $v = new DateTime($v);
    }, [&$a, &$b, &$c]);
    return max($a, $b, $c)->format('h:ia');
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

$today = (new DateTime());
$today_cst = clone $today;
$today_est = clone $today;
$today_mst = clone $today;
$today_pst = clone $today;

$today_pst->setTimezone(new DateTimeZone('America/Los_Angeles'));
$today_mst->setTimezone(new DateTimeZone('America/Denver'));
$today_cst->setTimezone(new DateTimeZone('America/Chicago'));
$today_est->setTimezone(new DateTimeZone('America/New_York'));
array_map(function (&$v) {
    /** @var DateTime $v */
    $v = $v->format('Y-m-d');
}, array(&$today_cst, &$today_est, &$today_mst, &$today_pst));

$user_ids_cst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'CST'"));
$user_ids_est = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'EST'"));
$user_ids_mst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'MST'"));
$user_ids_pst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'PST'"));

$user_ids_cst_expired = array();
if (!empty($user_ids_cst)) {
    $user_ids_cst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_cst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$today_cst'");
}
$user_ids_est_expired = array();
if (!empty($user_ids_est)) {
    $user_ids_est_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_est) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$today_est'");
}
$user_ids_mst_expired = array();
if (!empty($user_ids_mst)) {
    $user_ids_mst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_mst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$today_mst'");
}
$user_ids_pst_expired = array();
if (!empty($user_ids_pst)) {
    $user_ids_pst_expired = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_pst) AND Field_Name = 'Membership Expiry Date' AND Field_Value = '$today_pst'");
}
$user_ids_all_expired = array_merge($user_ids_cst_expired, $user_ids_est_expired, $user_ids_mst_expired, $user_ids_pst_expired);


$user_ids_cst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'CST'"));
$user_ids_est = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'EST'"));
$user_ids_mst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'MST'"));
$user_ids_pst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` AS u, `wp_ewd_feup_user_fields` AS uf WHERE u.User_ID = uf.User_ID AND uf.Field_Name='Time zone' AND uf.Field_Value = 'PST'"));
$user_ids_cst_30_from_last_sms = array();
if (!empty($user_ids_cst)) {
    $user_ids_cst_30_from_last_sms = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_cst) AND Field_Value = '$time_cst'");
}
$user_ids_est_30_from_last_sms = array();
if (!empty($user_ids_est)) {
    $user_ids_est_30_from_last_sms = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_est) AND Field_Value = '$time_est'");
}
$user_ids_mst_30_from_last_sms = array();
if (!empty($user_ids_mst)) {
    $user_ids_mst_30_from_last_sms = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_mst) AND Field_Value = '$time_mst'");
}
$user_ids_pst_30_from_last_sms = array();
if (!empty($user_ids_pst)) {
    $user_ids_pst_30_from_last_sms = $wpdb->get_col("SELECT User_ID FROM `wp_ewd_feup_user_fields` WHERE User_ID IN ($user_ids_pst) AND Field_Value = '$time_pst'");
}
$user_ids_all_30_from_last_sms = array_merge($user_ids_cst_30_from_last_sms, $user_ids_est_30_from_last_sms, $user_ids_mst_30_from_last_sms, $user_ids_pst_30_from_last_sms);
$user_ids_all_30_from_last_sms_expired = array_intersect($user_ids_all_expired, $user_ids_all_30_from_last_sms);

error_log("Sending mms expired 30 mins ago. Time pst: " . $time_pst . "All users expired: " . json_encode($user_ids_all_expired) . " All users expired 30 mins ago: " . json_encode($user_ids_all_30_from_last_sms_expired) . "\n\n", 0, $error_file_name);
if (empty($user_ids_all_30_from_last_sms_expired)) {
    return;
}

//get details of all users expired. Filter this list to remove users that has meal time not the greatest. Remember: we only send mms to people having their last meal of the day his subscription expires
$users_expired_to_filter = $wpdb->get_results("SELECT User_ID, Field_Name, Field_Value FROM `wp_ewd_feup_user_fields` WHERE User_ID IN (" . implode(",", (array)$user_ids_all_30_from_last_sms_expired) . ") AND Field_Name IN ('Breakfast', 'Lunch', 'Dinner', 'Time zone') ORDER BY User_ID DESC", ARRAY_A);

$users_expired_to_filter_max_time = [];
foreach ($users_expired_to_filter as $u) {
    if ($u['Field_Name'] == "Time zone") {
        $users_expired_to_filter_max_time[$u['User_ID']]['time_zone'] = $u['Field_Value'];
    } else {
        $users_expired_to_filter_max_time[$u['User_ID']]['times'][] = $u['Field_Value'];
    }
}
$users_expired_to_filter = [];
foreach ($users_expired_to_filter_max_time as $id => $u) {
    $users_expired_to_filter[$id] = ['time_zone' => $u['time_zone'],
        'max_date' => max_date($u['times'][0], $u['times'][1], $u['times'][2])];
}

foreach ($users_expired_to_filter as $id => $u) {
    $is_not_max = false;
    switch ($u['time_zone']) {
        case 'PST':
            if ($u['max_date'] !== $time_pst) {
                $is_not_max = true;
            }
            break;
        case 'EST':
            if ($u['max_date'] !== $time_est) {
                $is_not_max = true;
            }
            break;
        case 'MST':
            if ($u['max_date'] !== $time_mst) {
                $is_not_max = true;
            }
            break;
        case 'CST':
            if ($u['max_date'] !== $time_cst) {
                $is_not_max = true;
            }
            break;
        default:
            break;
    }
    if ($is_not_max) {
        $user_ids_all_30_from_last_sms_expired = array_diff($user_ids_all_30_from_last_sms_expired, [$id]);
    }
}

$users = $wpdb->get_results("SELECT Field_Value,u.User_ID FROM `wp_ewd_feup_users` AS u
JOIN `wp_ewd_feup_user_fields` AS uf ON u.User_ID = uf.User_ID WHERE uf.Field_Name = 'Phone' AND u.User_ID IN (" . implode(",", (array)$user_ids_all_30_from_last_sms_expired) . ") ; ");

$args = array(
    'posts_per_page' => -1,
    'category_name' => 'expired'
);
$query = new WP_Query($args);
$template_expire_soon = $query->post;


foreach ($users as $user) {
    $ok_to_sms = $wpdb->get_results("SELECT `Field_Value` FROM `wp_ewd_feup_user_fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'OK to receive texts?'");
    if (isset($ok_to_sms) && trim($ok_to_sms[0]->Field_Value) == 'No') {
        continue;
    }
    
    $user_id = $user->User_ID;
    $user_info = $wpdb->get_results("SELECT `Field_Name`, `Field_Value` from `wp_ewd_feup_user_fields` WHERE `User_ID` = $user_id ");
    ah_flatten($user_info, 'Field_Name');
    
    foreach ($user_info as $k => $u_i) {
        $temp = $u_i['Field_Value'];
        $user_info[$k] = $temp;
    }
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($template_expire_soon->ID), 'large');
    $image[0] = str_replace("10.0.0.116", "thinkthinly.com", $image[0]);
    $image[0] = str_replace("10.0.0.134", "thinkthinly.com", $image[0]);
    $image[0] = str_replace("localhost", "thinkthinly.com", $image[0]);
    
    $bl_link = get_bit_ly_url($user->User_ID);
    $content_to_send = $template_expire_soon->post_content;
    if (!empty($bl_link) && !is_array($bl_link)) {
        $content_to_send .= $bl_link;
    } else {
        error_log("Can not get bitly link. User id: " . $user->User_ID, 0, $error_file_name);
    }
    
    //MMS
    if (DEBUG_DONT_SEND_SMS) {
        echo "\nSending this message" . $template_expire_soon->ID . " " . $content_to_send . " " . $image[0] . " to this user:";
        var_dump($user);
    } else {
        if (!empty($image[0])) {
            try {
                $sms_sent = $client->account->messages->sendMessage(
                    "+16194190679",
                    $user_info['Phone'],
                    $content_to_send,
                    array($image[0])
                );
            } catch (Exception $e) {
                error_log("\nError sending mms through Twilio: " . $e->getMessage() . "\n");
            }
            
        } else {
            try {
                $sms_sent = $client->account->messages->sendMessage(
                    "+16194190679",
                    $user_info['Phone'],
                    $content_to_send
                );
            } catch (Exception $e) {
                error_log("\nError sending mms through Twilio: " . $e->getMessage() . "\n");
            }
            
        }
    }
    
    wp_reset_postdata();
    if (!empty($sms_sent->sid)) {
        echo "Message Sent By Twilio: ID- {$sms_sent->sid}";
    }
}
date_default_timezone_set($old_date_def_timezone);
?>
