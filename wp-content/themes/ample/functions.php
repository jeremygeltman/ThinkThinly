<?php
/**
 * Ample functions related to defining constants, adding files and WordPress core functionality.
 *
 * @package ThemeGrill
 * @subpackage Ample
 * @since Ample 0.1
 */

add_action( 'after_setup_theme', 'ample_setup' );

if ( ! function_exists( 'ample_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 */
function ample_setup() {
   global $content_width;
   /**
    * Set the content width based on the theme's design and stylesheet.
    */
   if ( ! isset( $content_width ) )
      $content_width = 710; /* pixels */

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'ample', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	//Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

   // Cropping the images to different sizes to be used in the theme
	add_image_size( 'ample-featured-blog-large', 710, 300, true );
	add_image_size( 'ample-featured-blog-small', 230, 230, true );
	add_image_size( 'ample-portfolio-image', 330, 330, true );

	// Registering navigation menus.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'ample' ),
		'footer' => __( 'Footer Menu', 'ample' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ample_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Adding excerpt option box for pages as well
	add_post_type_support( 'page', 'excerpt' );
}
endif; // ample_setup

/**
 * Register widget area.
 *
 */
require get_template_directory() . '/inc/widgets/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/functions.php';

/**
 * Functions related to header.
 */
require get_template_directory() . '/inc/header-functions.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Add meta Box
 */
require get_template_directory() . '/inc/admin/meta-boxes.php';

/**
 * Adds support for a theme option.
 */
if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/admin/options/' );
	require_once dirname( __FILE__ ) . '/inc/admin/options/options-framework.php';
	require_once dirname( __FILE__ ) . '/options.php';
}