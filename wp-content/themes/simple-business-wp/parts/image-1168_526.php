<?php
if (has_post_thumbnail()) {
    the_post_thumbnail('nimbus_1168_526', array('class' => 'img-responsive nimbus_1168_526 sub_banner'));
} else {
    if (nimbus_get_option('nimbus_example_content') == "on" ) {
    ?>
        <img src="<?php echo get_template_directory_uri(); ?>/images/preview/nimbus_1168_526.jpg" class="img-responsive nimbus_1168_526 sub_banner" alt="<?php the_title(); ?>" />
    <?php 
    }
}
?>   
