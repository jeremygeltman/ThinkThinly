<?php
// set variables if front-page
$nimbus_left_featured = nimbus_get_option('nimbus_left_featured');
$nimbus_center_featured = nimbus_get_option('nimbus_center_featured');
$nimbus_right_featured = nimbus_get_option('nimbus_right_featured');
$toggle = nimbus_get_option('nimbus_toggle_featured');
if (is_front_page()) {
    $nimbus_featured = array(
        'nimbus_left_featured'              =>  $nimbus_left_featured,
        'nimbus_center_featured'            =>  $nimbus_center_featured,
        'nimbus_right_featured'             =>  $nimbus_right_featured,
    );
    if ($toggle == 1 || $toggle == 'show') {
    ?>
        <div class="row frontpage_featured content_squeeze">
            <?php
            foreach ($nimbus_featured as $key => $featured) {
                $icon = nimbus_get_option( $key . '_icon');
                $color = nimbus_get_option( $key . '_icon_color');
                ?>
                <div id="<?php echo $key; ?>" class="col-sm-4 featured">
                    <?php
                    if (!empty($featured)) {
                        $original_query = $wp_query;
                        $wp_query = null;
                        $wp_query = new WP_Query(array('page_id' => $featured, 'posts_per_page' => 1, 'post__not_in' => get_option( 'sticky_posts' )));
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                
                                ?>
                                <div class="frontpage_featured_item">
                                    <i class="fa <?php echo $icon; ?>" style="color:<?php echo $color; ?>;"></i>
                                    <h3 class="text-center"><a href="<?php echo get_permalink($featured); ?>"><?php get_template_part( 'parts/title', 'page'); ?></a></h3>
                                    <?php 
                                    $the_excerpt = nimbus_get_the_excerpt_by_id($featured);
                                    if (!empty($the_excerpt)) {
                                        echo $the_excerpt; 
                                    } else {
                                        nimbus_clear(8);
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        } else {
                                get_template_part( 'parts/error', 'no_results');
                        }
                        $wp_query = null;
                        $wp_query = $original_query;
                        wp_reset_postdata();
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
}
?>