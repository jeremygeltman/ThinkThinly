<?php
/** @var $wpdb */

require_once(dirname(__FILE__) . '/wp-blog-header.php');

//check magic word
if (! array_key_exists("secret_key", $_GET) || ($_GET['secret_key'] != 'e2e697afc5ebee779eb383238b95b92e')) {
    mail('someids@gmail.com', "Improper request from thinkthinly.com", "Request from " .  json_encode($_SERVER). " POST is ". json_encode($_POST));
//    echo "Improper request from thinkthinly.com", "Request from " . json_encode($_SERVER) . " GET is " . json_encode($_GET);
    return;
}

require "Services/Twilio.php";
$AccountSid = "ACddcce2ed6943c1bd04b0642fab6b2f3f";
$AuthToken  = "1542d1f8621777361d4d0332d1f8ec4c";
$client     = new Services_Twilio($AccountSid, $AuthToken);

$old_date_def_timezone = date_default_timezone_get();
date_default_timezone_set('UTC');

$current_time = (new DateTime())->modify('+15 minutes');

define('DEBUG_DONT_SEND_SMS', true);

if (DEBUG_DONT_SEND_SMS) {
//    $current_time = ((new DateTime())->setTimezone((new DateTimeZone('UTC')))->setTime(21, 2));
     $current_time = ((new DateTime())->setTimezone((new DateTimeZone('UTC')))->setTime(16,0));
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

$user_ids_cst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'CST'"));
$user_ids_est = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'EST'"));
$user_ids_mst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'MST'"));
$user_ids_pst = implode(",", $wpdb->get_col("SELECT u.User_ID FROM `wp_ewd_feup_users` as u, `wp_ewd_feup_user_fields` as uf where u.User_ID = uf.User_ID and uf.Field_Name='Time zone' and uf.Field_Value = 'PST'"));
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
if (empty($user_ids_all_current)){
    return;
}
$user_ids_all_current = implode(',',$user_ids_all_current);

$users        = $wpdb->get_results("SELECT Field_Value,u.User_ID FROM `wp_ewd_feup_users` as u JOIN `wp_ewd_feup_user_fields` as uf on u.User_ID = uf.User_ID where uf.Field_Name = 'Phone' and u.User_ID in ($user_ids_all_current)");
$mms_meal_cst = $wpdb->get_results("SELECT `Field_Name` FROM `wp_ewd_feup_user_fields` WHERE `Field_Value` = '$time_cst' and User_ID IN ($user_ids_cst) LIMIT 1");
$mms_meal_est = $wpdb->get_results("SELECT `Field_Name` FROM `wp_ewd_feup_user_fields` WHERE `Field_Value` = '$time_est' and User_ID IN ($user_ids_est) LIMIT 1");
$mms_meal_mst = $wpdb->get_results("SELECT `Field_Name` FROM `wp_ewd_feup_user_fields` WHERE `Field_Value` = '$time_mst' and User_ID IN ($user_ids_mst) LIMIT 1");
$mms_meal_pst = $wpdb->get_results("SELECT `Field_Name` FROM `wp_ewd_feup_user_fields` WHERE `Field_Value` = '$time_pst' and User_ID IN ($user_ids_pst) LIMIT 1");
$mms_meal     = array_merge($mms_meal_cst, $mms_meal_est, $mms_meal_mst, $mms_meal_pst);
//print_r($mms_meal);die;
//var_dump(get_defined_vars());

foreach ($users as $user) {
    $meal = explode(' ', strtolower($mms_meal[0]->Field_Name));

    $mealid = get_term_by('slug', $meal[0], 'meal');

    $sms = $wpdb->get_results("SELECT `Field_Value` FROM `wp_ewd_feup_user_fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'OK to receive texts?'");

    $gndr = $wpdb->get_results("SELECT `Field_Value` FROM `wp_ewd_feup_user_fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'Gender'");

    $gndrs = get_term_by('slug', strtolower($gndr[0]->Field_Value), 'meal');

    $field = 'msgid_' . $meal[0];//'msgid_breakfast'

    //echo "SELECT $field FROM `wp_message` WHERE `User_ID` = $user->User_ID";
    $msg = $wpdb->get_results("SELECT $field FROM `wp_message` WHERE `User_ID` = $user->User_ID");
    //print_r($msg);die;
    $checks = $wpdb->get_results("SELECT User_ID FROM `wp_message` WHERE `User_ID` = $user->User_ID");
    //print_r($checks);die;

    if (isset($sms) && trim($sms[0]->Field_Value) == 'Yes') {
        if (isset($msg) && ! empty($msg) && $msg[0]->$field != 0) {
            $va = $msg[0]->$field;

            if ($gndrs->slug == 'male') {
                $notslug = "female";
            } elseif ($gndrs->slug == 'female') {
                $notslug = "male";
            }

            $id = $wpdb->get_results("SELECT wp_posts.ID FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'
						AND wp_term_taxonomy.term_id = '$mealid->term_id,$gndrs->term_id' 
						AND wp_term_taxonomy.taxonomy = 'meal'
						AND wp_posts.ID > $va
						AND wp_posts.ID NOT IN 
						(SELECT wp_posts.ID FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'					
						AND wp_terms.slug  = '$notslug')
						ORDER BY `wp_posts`.`ID` ASC limit 1");

            //echo $id[0]->ID.$va;die;
            if ($id[0]->ID != $va) {
                if ($gndrs->slug == 'male') {
                    $notslug = "female";
                } elseif ($gndrs->slug == 'female') {
                    $notslug = "male";
                }

                $myposts = $wpdb->get_results("SELECT wp_posts.ID, wp_posts.post_excerpt FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'
						AND wp_term_taxonomy.term_id = '$mealid->term_id,$gndrs->term_id' 
						AND wp_term_taxonomy.taxonomy = 'meal'
						AND wp_posts.ID > $va
						AND wp_posts.ID NOT IN 
						(SELECT wp_posts.ID FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'					
						AND wp_terms.slug  = '$notslug')
						ORDER BY `wp_posts`.`ID` ASC limit 1");
            } else {

                if ($gndrs->slug == 'male') {
                    $notslug = "female";
                } elseif ($gndrs->slug == 'female') {
                    $notslug = "male";
                }


                $myposts = $wpdb->get_results("SELECT wp_posts.ID, wp_posts.post_excerpt FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'
						AND wp_term_taxonomy.term_id = '$mealid->term_id,$gndrs->term_id' 
						AND wp_term_taxonomy.taxonomy = 'meal'
						AND wp_posts.ID NOT IN 
						(SELECT wp_posts.ID FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'					
						AND wp_terms.slug  = '$notslug')
						ORDER BY `wp_posts`.`ID` ASC limit 1");
            }

            //print_r($myposts);die;

            $b = 0;
            $l = 0;
            $d = 0;

            if ($meal[0] == 'breakfast') {
                $b = $myposts[0]->ID;
                $wpdb->query("UPDATE `wp_message` SET  msgid_breakfast = $b WHERE `User_ID` = $user->User_ID");
            } elseif ($meal[0] == 'lunch') {
                $l = $myposts[0]->ID;
                $wpdb->query("UPDATE `wp_message` SET msgid_lunch = $l WHERE `User_ID` = $user->User_ID");
            } elseif ($meal[0] == 'dinner') {
                $d = $myposts[0]->ID;
                $wpdb->query("UPDATE `wp_message` SET  msgid_dinner = $d WHERE `User_ID` = $user->User_ID");
            }
        } else {

            if ($gndrs->slug == 'male') {
                $notslug = "female";
            } elseif ($gndrs->slug == 'female') {
                $notslug = "male";
            }

            $myposts = $wpdb->get_results("SELECT wp_posts.ID, wp_posts.post_excerpt FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'
						AND wp_term_taxonomy.term_id = '$mealid->term_id,$gndrs->term_id' 
						AND wp_term_taxonomy.taxonomy = 'meal'
						AND wp_posts.ID NOT IN 
						(SELECT wp_posts.ID FROM wp_posts
						LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
						LEFT JOIN wp_term_taxonomy ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
						LEFT JOIN wp_terms ON (wp_term_taxonomy.term_id = wp_terms.term_id)
						WHERE wp_posts.post_type = 'mms-template'					
						AND wp_terms.slug  = '$notslug')
						ORDER BY `wp_posts`.`ID` ASC limit 1");

            $b = 0;
            $l = 0;
            $d = 0;
            if (isset($checks) && ! empty($checks) && $checks[0]->User_ID != 0 && $checks[0]->User_ID != '') {
                if ($meal[0] == 'breakfast') {
                    $b = $myposts[0]->ID;
                    $wpdb->query("UPDATE `wp_message` SET  msgid_breakfast = $b WHERE `User_ID` = $user->User_ID");
                } elseif ($meal[0] == 'lunch') {
                    $l = $myposts[0]->ID;
                    $wpdb->query("UPDATE `wp_message` SET msgid_lunch = $l WHERE `User_ID` = $user->User_ID");
                } elseif ($meal[0] == 'dinner') {
                    $d = $myposts[0]->ID;
                    $wpdb->query("UPDATE `wp_message` SET  msgid_dinner = $d WHERE `User_ID` = $user->User_ID");
                }

            } else {
                if ($meal[0] == 'breakfast') {
                    $b = $myposts[0]->ID;
                } elseif ($meal[0] == 'lunch') {
                    $l = $myposts[0]->ID;
                } elseif ($meal[0] == 'dinner') {
                    $d = $myposts[0]->ID;
                }

                $wpdb->query("INSERT INTO `wp_message` (`User_ID`, `msgid_breakfast`, `msgid_lunch`, `msgid_dinner`) VALUES ($user->User_ID, $b, $l, $d)");
            }
        }

        //print_r($myposts);
        //die;
        //"+12517322016";

        $image    = wp_get_attachment_image_src(get_post_thumbnail_id($myposts[0]->ID), 'large');
        $image[0] = str_replace("10.0.0.116", "thinkthinly.com", $image[0]);
        $image[0] = str_replace("10.0.0.134", "thinkthinly.com", $image[0]);
        $image[0] = str_replace("localhost", "thinkthinly.com", $image[0]);
        //echo $user->Field_Value;die;
        //MMS
        if (DEBUG_DONT_SEND_SMS) {
            echo "\nSending this message" . $myposts[0]->ID . " to this user:";
            var_dump($user);
        } else {
            $sms = $client->account->messages->sendMessage(
                "+16194190679",
                $user->Field_Value,
                $myposts[0]->post_excerpt,
                array($image[0])
            );
        }

//            echo "sending to this user\n"; var_dump($user);

        wp_reset_postdata();
        echo "Message Sent: ID- {$sms->sid}";
        //mail("reetika.php@gmail.com","My subject","{$sms->sid}");
    } else {
        echo "Please enable Send MMS from settings.";
    }
}
date_default_timezone_set($old_date_def_timezone);
//die;
?>
