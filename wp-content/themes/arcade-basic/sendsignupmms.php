<?php
/* Template Name: sendsignupmms*/
require "Services/Twilio.php";
$AccountSid = "ACddcce2ed6943c1bd04b0642fab6b2f3f";
//$AccountSid = "AC34a5f15925ad36bbbc27982fd4b9deba";
$AuthToken = "1542d1f8621777361d4d0332d1f8ec4c";
//$AuthToken = "3342df8735f959b64c61ec3135f8ba2b";
//mail("reetika.php@gmail.com","My subject","test");
$client = new Services_Twilio($AccountSid, $AuthToken);

date_default_timezone_set('UTC');
$current_time = date('H:ia', strtotime("+5 minutes"));
//$current_time='01:00am';
//$userId = - 1;
//if ( isset($_REQUEST['uid']) ) {
//    $userId = $_REQUEST['uid'];
//}

global $uid;
$userId = $uid;
//$wpdb->query("INSERT INTO `wp_message` (`User_ID`, `msgid_breakfast`, `msgid_lunch`, `msgid_dinner`) VALUES (11,2, 1, 2)");die;
$userPhone = $wpdb->get_results("SELECT Field_Value,User_ID FROM `wp_EWD_FEUP_User_Fields` where Field_Name = 'Phone' and User_ID = $userId");
if ( count($userPhone) != 1 ) {
    echo "No user phone found";
    die;
}
$userPhone = $userPhone[0]->Field_Value;
$gender    = $wpdb->get_results("SELECT Field_Value,User_ID FROM `wp_EWD_FEUP_User_Fields` where Field_Name = 'Gender' and User_ID = $userId");
if ( count($gender) != 1 ) {
    echo "No gender found";
    die;
}
$gender = $gender[0]->Field_Value;
$gender = strtolower($gender);

//	print_r($users);die;

//$mms_meal = $wpdb->get_results("SELECT `Field_Name` FROM `wp_EWD_FEUP_User_Fields` WHERE `Field_Value` = '$current_time' LIMIT 1");
//print_r($mms_meal);die;

//foreach ( $users as $user ) {
//    $meal = explode(' ', strtolower($mms_meal[0]->Field_Name));

//    $mealid = get_term_by('slug', $meal[0], 'meal');

//    $sms = $wpdb->get_results("SELECT `Field_Value` FROM `wp_EWD_FEUP_User_Fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'Send MMS'");

//    $gndr = $wpdb->get_results("SELECT `Field_Value` FROM `wp_EWD_FEUP_User_Fields` WHERE `User_ID` = $user->User_ID and Field_Name = 'Gender'");

//    $gndrs = get_term_by('slug', strtolower($gndr[0]->Field_Value), 'meal');

//    $field = 'msgid_' . $meal[0];

//echo "SELECT $field FROM `wp_message` WHERE `User_ID` = $user->User_ID";
$msg = $wpdb->get_results("SELECT `ID`, post_excerpt  FROM `wp_posts` WHERE `post_type`= 'mms-template' and `post_name` = 'sign-up-$gender' ");
if ( count($msg) != 1 ) {
    echo "Missing mms template";
    die;
}

$postId = $msg[0]->ID;

$b = 0;
$l = 0;
$d = 0;

//print_r($myposts);
//die;
//"+12517322016";

$image = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'large');
$image = str_replace("10.0.0.116","thinkthinly.com",$image);
$image = str_replace("localhost","thinkthinly.com",$image);
$msg_to_send = $msg[0]->post_excerpt;
$user_password = base64_encode(substr($userPhone,5,6));
$msg_to_send.=". Your password is " . $user_password;
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
    $_SESSION['message_count'] = 2;
//    global $user_message;
//    $user_message['Message'] .= "<br/><p class='text-success'>A message has been sent to your number $userPhone . Please enter our email and set a password for your account.</p>";
}
catch (Services_Twilio_RestException $e) {
//    echo $e->getMessage();
}

wp_reset_postdata();
//echo "Message Sent: ID- {$sms->sid}";
//mail("reetika.php@gmail.com","My subject","{$sms->sid}");
//}
//die;
?>
