<?php
/* Template Name: sms*/
require "Services/Twilio.php";
$AccountSid = "ACddcce2ed6943c1bd04b0642fab6b2f3f";
//$AccountSid = "AC34a5f15925ad36bbbc27982fd4b9deba";
$AuthToken = "1542d1f8621777361d4d0332d1f8ec4c";
//$AuthToken = "3342df8735f959b64c61ec3135f8ba2b";
//mail("reetika.php@gmail.com","My subject","test");
$client = new Services_Twilio($AccountSid, $AuthToken);

date_default_timezone_set('UTC');
$current_time = date('H:ia', strtotime("+5 minutes"));
//echo "time is \n $current_time"; die;
//for debugging:
define('DEBUG_DONT_SEND_SMS',false);

if (DEBUG_DONT_SEND_SMS){
    $current_time = '17:30pm';
}


//$wpdb->query("INSERT INTO `wp_message` (`User_ID`, `msgid_breakfast`, `msgid_lunch`, `msgid_dinner`) VALUES (11,2, 1, 2)");die;
$users = $wpdb->get_results("SELECT Field_Value,User_ID FROM `wp_EWD_FEUP_User_Fields` where Field_Name = 'Phone' and User_ID in (SELECT u.User_ID FROM `wp_EWD_FEUP_Users` as u, `wp_EWD_FEUP_User_Fields` as uf where u.User_ID = uf.User_ID and uf.Field_Value = '$current_time')");

$mms_meal = $wpdb->get_results("SELECT `Field_Name` FROM `wp_EWD_FEUP_User_Fields` WHERE `Field_Value` = '$current_time' LIMIT 1");
//print_r($mms_meal);die;

foreach ( $users as $user ) {
    $meal = explode(' ', strtolower($mms_meal[0]->Field_Name));

    $mealid = get_term_by('slug', $meal[0], 'meal');

    $sms = $wpdb->get_results("SELECT `Field_Value` FROM `wp_EWD_FEUP_User_Fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'OK to receive texts?'");

    $gndr = $wpdb->get_results("SELECT `Field_Value` FROM `wp_EWD_FEUP_User_Fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'Gender'");

    $gndrs = get_term_by('slug', strtolower($gndr[0]->Field_Value), 'meal');

    $field = 'msgid_' . $meal[0];//'msgid_breakfast'

    //echo "SELECT $field FROM `wp_message` WHERE `User_ID` = $user->User_ID";
    $msg = $wpdb->get_results("SELECT $field FROM `wp_message` WHERE `User_ID` = $user->User_ID");
    //print_r($msg);die;
    $checks = $wpdb->get_results("SELECT User_ID FROM `wp_message` WHERE `User_ID` = $user->User_ID");
    //print_r($checks);die;

    if ( isset($sms) && trim($sms[0]->Field_Value) == 'Yes' ) {
        if ( isset($msg) && ! empty($msg) && $msg[0]->$field != 0 ) {
            $va = $msg[0]->$field;

            if ( $gndrs->slug == 'male' ) {
                $notslug = "female";
            } elseif ( $gndrs->slug == 'female' ) {
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
            if ( $id[0]->ID != $va ) {
                if ( $gndrs->slug == 'male' ) {
                    $notslug = "female";
                } elseif ( $gndrs->slug == 'female' ) {
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

                if ( $gndrs->slug == 'male' ) {
                    $notslug = "female";
                } elseif ( $gndrs->slug == 'female' ) {
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

            if ( $meal[0] == 'breakfast' ) {
                $b = $myposts[0]->ID;
                $wpdb->query("UPDATE `wp_message` SET  msgid_breakfast = $b WHERE `User_ID` = $user->User_ID");
            } elseif ( $meal[0] == 'lunch' ) {
                $l = $myposts[0]->ID;
                $wpdb->query("UPDATE `wp_message` SET msgid_lunch = $l WHERE `User_ID` = $user->User_ID");
            } elseif ( $meal[0] == 'dinner' ) {
                $d = $myposts[0]->ID;
                $wpdb->query("UPDATE `wp_message` SET  msgid_dinner = $d WHERE `User_ID` = $user->User_ID");
            }
        } else {

            if ( $gndrs->slug == 'male' ) {
                $notslug = "female";
            } elseif ( $gndrs->slug == 'female' ) {
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
            if ( isset($checks) && ! empty($checks) && $checks[0]->User_ID != 0 && $checks[0]->User_ID != '' ) {
                if ( $meal[0] == 'breakfast' ) {
                    $b = $myposts[0]->ID;
                    $wpdb->query("UPDATE `wp_message` SET  msgid_breakfast = $b WHERE `User_ID` = $user->User_ID");
                } elseif ( $meal[0] == 'lunch' ) {
                    $l = $myposts[0]->ID;
                    $wpdb->query("UPDATE `wp_message` SET msgid_lunch = $l WHERE `User_ID` = $user->User_ID");
                } elseif ( $meal[0] == 'dinner' ) {
                    $d = $myposts[0]->ID;
                    $wpdb->query("UPDATE `wp_message` SET  msgid_dinner = $d WHERE `User_ID` = $user->User_ID");
                }

            } else {
                if ( $meal[0] == 'breakfast' ) {
                    $b = $myposts[0]->ID;
                } elseif ( $meal[0] == 'lunch' ) {
                    $l = $myposts[0]->ID;
                } elseif ( $meal[0] == 'dinner' ) {
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
        //echo $user->Field_Value;die;
        //MMS
        if ( DEBUG_DONT_SEND_SMS ) {
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
//die;
?>
