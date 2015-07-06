<?php
if (is_single()) {
    if (have_posts()) {
        while (have_posts()) {
            the_post();
                get_template_part( 'parts/content', 'single');
        }
    } else {
            get_template_part( 'parts/error', 'no_results');
    }                
} else if (is_page()) {
    if (have_posts()) {
        while (have_posts()) {
            the_post();
                get_template_part( 'parts/content', 'page');
        }
    } else {
            get_template_part( 'parts/error', 'no_results');
    } 
} else {
    get_template_part( 'parts/content', 'blog');
}
?>
