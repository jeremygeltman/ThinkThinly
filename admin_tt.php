<?php
/** @var $wpdb */

require_once(dirname(__FILE__) . '/wp-blog-header.php');
$error_file_name = __DIR__ . DIRECTORY_SEPARATOR . "error_log";
require_once('vendor/autoload.php');

$meals = ['breakfast', 'lunch', 'dinner'];
$genders = ['male', 'female'];

foreach ($genders as $gender){
    foreach ($meals as $meal){
        $args     = array(
            'post_type' => 'mms-template',
            'post_status' => 'publish',
            'posts_per_page' => - 1,
            'caller_get_posts' => 1,
            'orderby' => 'title',
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
        echo "Posts for $meal - $gender: <br/>";
        var_dump($my_query->posts);

    }
}
