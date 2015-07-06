<?php
if (has_post_thumbnail()) {
    the_post_thumbnail('nimbus_192_186', array('class' => 'nimbus_192_186'));
} else {
    if (nimbus_get_option('nimbus_example_content') == "on" ) {
    ?>
        <img src="<?php echo get_template_directory_uri(); ?>/images/preview/nimbus_192_186_<?php echo rand(1,4); ?>.jpg" class="nimbus_192_186" alt="<?php the_title(); ?>" />
    <?php 
    }
}
?>   
