<?php 
global $post;
$banner_option = nimbus_get_option('nimbus_banner_option');
$nimbus_content_width_banner = nimbus_get_option('nimbus_content_width_banner');

// Do frontpage banner options
if (is_front_page()) {
    // Content width banner
    if ($banner_option == 'image_content_width') { 
    ?>
        <div>
            <?php
            if (!empty($nimbus_content_width_banner)) {
            ?>
            <img id="frontpage_banner" class="static_banner nimbus_1168_526" src="<?php echo $nimbus_content_width_banner; ?>" />
            <?php
            } else {
                if (nimbus_get_option('nimbus_example_content') == 'on') {
                ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/preview/nimbus_1168_526.jpg" class="nimbus_1168_526 static_banner" alt="Example Banner" />
                <?php
                }
            }
            ?>
        </div>
    <?php 
    } else {
        // No Banner
    } 
} else {
    // Not on frontpage
}     
?>