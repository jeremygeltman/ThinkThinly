<?php

//Brian added to check for 0 dollar subscription
global $wpdb;
$login = CheckLoginCookie();
$is_allowed_0_dollar_subscription = 0;
$is_subscription_active = 0;
if (isset($login['Username'])) {
    $result = $wpdb->get_row("SELECT `allow_0_dollar_subscription`, `subscription` FROM `wp_ewd_feup_users` WHERE Username = " . $login['Username'], ARRAY_A);
    if (isset($result['allow_0_dollar_subscription'])) {
        $is_allowed_0_dollar_subscription = $result['allow_0_dollar_subscription'];
    }
    if (isset($result['subscription'])) {
        $is_subscription_active = ($result['subscription'] == "active");
    }
}
////end Brian added to check for 0 dollar subscription




/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main>
 * and the left sidebar conditional
 *
 * @since 1.0.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <meta name="p:domain_verify" content="f8b68011e74053dac999bc87931a87da"/>

    <!-- folia includes start -->

    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind:400,300,500,600,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900,400italic,300italic,700italic,900italic'
          rel='stylesheet' type='text/css'>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/bootstrap.min.css" type="text/css"
          media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/fonts/flaticon.css" type="text/css"
          media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/magnific-popup.css" type="text/css"
          media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/owl.carousel.css" type="text/css"
          media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/owl.theme.css" type="text/css"
          media="all">
    <!--    <link rel="stylesheet" href="-->
    <?php //bloginfo('template_directory');?><!--/folia/css/bootstrap-select.min.css" type="text/css" media="all">-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css"
          type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/animate.css" type="text/css"
          media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/style.css" type="text/css"
          media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/folia/css/style-switch.css" type="text/css"
          media="all">
    <!--    <link rel="stylesheet" id="css-color" href="-->
    <?php //bloginfo('url');?><!--/folia/css/skins/orange.css"/>-->
    <!-- folia includes end -->


    <!--[if IE]>
    <script src="<?php echo BAVOTASAN_THEME_URL; ?>/library/js/html5.js"></script><![endif]-->
    <?php
    wp_head(); ?>


</head>
<?php
$bavotasan_theme_options = bavotasan_theme_options();
$space_class = '';
?>
<body <?php body_class(); ?>>

<!-- Google Tag Manager -->
<noscript>
    <iframe src="//www.googletagmanager.com/ns.html?id=GTM-NQBB2F"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<script>(function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(), event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
                '//www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-NQBB2F');</script>
<!-- End Google Tag Manager -->

<div id="page">

    <header id="header">

        <header id="layout1-header">
            <nav class="navbar main-navigation-header top-nav navbar-fixed-top">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->

                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#header-navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="<?php bloginfo('url'); ?>" class="navbar-brand logo"><img
                                    src="<?php bloginfo('url'); ?>/wp-content/uploads/2016/02/ThinkThinlyLogo-retina.png"
                                    alt="ThinkThinly Logo"></a>
                    </div><!-- Collect the nav links, forms, and other content for toggling -->

                    <div class="collapse navbar-collapse" id="header-navigation">
                        <ul class="nav navbar-nav main-navigation navbar-right">
                            <?php
                            if ($login == false):?>
                                <div class="menu-footer-container">
                                    <li id="menu-item-9997"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9997">
                                        <a href="/signin">Sign In </a></li>
                                </div>
                            <?php else:
                                if ($is_subscription_active):?>
                                    <div class="menu-footer-container">
                                        <li id="menu-item-9998"
                                            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9998">
                                            <a href="/logout">Logout &gt;</a></li>
                                    </div>
                                <?php else:
                                    if ($is_allowed_0_dollar_subscription): ?>
                                        <div class="menu-footer-container">
                                            <li id="menu-item-9999"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9999">
                                                <a href="/subscribe-with-0-dollar-monthly">Go Premium For Free &gt;</a>
                                            </li>
                                        </div>
                                    <?php else:
                                        wp_nav_menu(array('items_wrap' => '%3$s'));
                                    endif;
                                endif;
                            endif; ?>


                        </ul>

                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </header><!-- Navigation -->


        <!-- start header for arcade basic -->
        <div class="title-card-wrapper">
            <div class="title-card">
                <div id="site-meta">
                    <h1 id="site-title">
                        <a href="<?php echo esc_url(home_url()); ?>"
                           title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                           rel="home"><?php bloginfo('name'); ?></a>
                    </h1>

                    <?php if ($bavotasan_theme_options['header_icon']) { ?>
                        <i class="fa <?php echo $bavotasan_theme_options['header_icon']; ?>"></i>
                    <?php } else {
                        $space_class = ' class="margin-top"';
                    } ?>

                    <h2 id="site-description"<?php echo $space_class; ?>>
                        <?php bloginfo('description'); ?>
                    </h2>
                    <?php
                    /**
                     * You can overwrite the defeault 'See More' text by defining the 'BAVOTASAN_SEE_MORE'
                     * constant in your child theme's function.php file.
                     */
                    if (! defined('BAVOTASAN_SEE_MORE')) {
                        define('BAVOTASAN_SEE_MORE', __('See More', 'arcade'));
                    }
                    ?>
                    <a href="#" id="more-site" class="btn btn-default btn-lg"><?php echo BAVOTASAN_SEE_MORE; ?></a>
                </div>

                <?php
                // Header image section
                bavotasan_header_images();
                ?>
            </div>
        </div>

    </header>

    <!-- end header for arcade basic -->

    <main>
        <?php
        //            global $shortcode_tags;
        //            echo "<pre>"; print_r($shortcode_tags); echo "</pre>";
        ?>
    
    
        <script>
            function setCookie(c_name, value, exdays) {
                var exdate = new Date();
                exdate.setDate(exdate.getDate() + exdays);
                var c_value = escape(value) +
                    ((exdays == null) ? "" : ("; expires=" + exdate.toUTCString()));
                document.cookie = c_name + "=" + c_value;
            }
        
            function getCookie(c_name) {
                var i, x, y, ARRcookies = document.cookie.split(";");
                for (i = 0; i < ARRcookies.length; i++) {
                    x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
                    y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
                    x = x.replace(/^\s+|\s+$/g, "");
                    if (x == c_name) {
                        return unescape(y);
                    }
                }
            }
        
            $(document).ready(function () {
            
            })
            ;
    
        </script>
