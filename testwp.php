<?php

require_once('wp-blog-header.php');

$args=array(
    'post_type' => 'mms-template',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'caller_get_posts'=> 1,
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'meal',
            'field'    => 'slug',
            'terms'    => array( 'Lunch' ),
        ),
        array(
            'taxonomy' => 'meal',
            'field'    => 'slug',
            'terms'    => array( 'Female' ),
        ),
    ),
);
$my_query = new WP_Query($args);

var_dump(sizeof($my_query->posts));