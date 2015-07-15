<?php
/**
 * Template Name: ThinkThinly form page tempalte
 */
?>


<?php get_header(); ?>

<?php
    if (have_posts()) : while (have_posts()) : the_post(); the_content(); 
    endwhile; endif;
?>


<?php get_footer(); ?>