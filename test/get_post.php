<?php

require_once (dirname(__DIR__).DIRECTORY_SEPARATOR.'wp-blog-header.php');

//$posts = query_posts( array ( 'category_name' => 'expired', 'posts_per_page' => -1 ) );

//var_dump($posts);

$args = array(
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field'    => 'name',
            'terms'    => 'welcome',
        ),
    ),
    'category_name' => 'expired'
);
$query = new WP_Query( $args );
$post = $query->post;

var_dump($post);