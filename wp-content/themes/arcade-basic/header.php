<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main>
 * and the left sidebar conditional
 *
 * @since 1.0.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">




<!-- folia includes start -->
	
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind:400,300,500,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,700,900,400italic,300italic,700italic,900italic' rel='stylesheet' type='text/css'>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/fonts/flaticon.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/magnific-popup.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/owl.carousel.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/owl.theme.css" type="text/css" media="all">
<!--    <link rel="stylesheet" href="--><?php //bloginfo('template_directory');?><!--/folia/css/bootstrap-select.min.css" type="text/css" media="all">-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/animate.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/style-switch.css" type="text/css" media="all">
<!--    <link rel="stylesheet" id="css-color" href="--><?php //bloginfo('url');?><!--/folia/css/skins/orange.css"/>-->
<!-- folia includes end -->



	<!--[if IE]><script src="<?php echo BAVOTASAN_THEME_URL; ?>/library/js/html5.js"></script><![endif]-->
	<?php
    wp_head(); ?>


</head>
<?php
$bavotasan_theme_options = bavotasan_theme_options();
$space_class = '';
?>
<body <?php body_class(); ?>>

	<div id="page">



		<main>
            <?php
//            global $shortcode_tags;
//            echo "<pre>"; print_r($shortcode_tags); echo "</pre>";
            ?>
