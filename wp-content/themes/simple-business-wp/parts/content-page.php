<?php
$sidebar_select = get_post_meta($post->ID, 'sidebar_select', true);
if ($sidebar_select == 'right') {
    $sidebar_select_aside_classes = '';
    $sidebar_select_content_classes = '';
} else {
    $sidebar_select_aside_classes = 'col-sm-pull-8';
    $sidebar_select_content_classes = 'col-sm-push-4';
}
if (empty($sidebar_select) || ($sidebar_select == 'none')) {
?>
    <div <?php post_class('main_content content row content_squeeze'); ?>>
        <div class="col-xs-12">
            <?php 
            if (get_post_meta($post->ID, 'toggle_page_title', true) == "hide") {
                // don't show title
            } else if (!is_front_page()) {
            ?>
                <h2 class="page_title"><?php get_template_part( 'parts/title', 'page'); ?></h2>
            <?php
            }
            if (get_post_meta($post->ID, 'toggle_featured', true) == "show") {
                get_template_part( 'parts/image', '1168_526');
            }
            the_content();
            nimbus_clear();
            get_template_part( 'parts/wp_link_pages');
            comments_template();
            ?>
        </div>
    </div>
<?php    
} else {
?>
    <div <?php post_class('main_content content row content_squeeze'); ?>>
        
        <div class="col-sm-8 <?php echo $sidebar_select_content_classes; ?>">
            <?php 
            if (get_post_meta($post->ID, 'toggle_page_title', true) == "hide") {
                // don't show title
            } else if (!is_front_page()) {
            ?>
                <h2 class="page_title"><?php get_template_part( 'parts/title', 'page'); ?></h2>
            <?php
            }
            if (get_post_meta($post->ID, 'toggle_featured', true) == "show") {
                get_template_part( 'parts/image', '1168_526');
            }
            the_content();
            nimbus_clear();
            get_template_part( 'parts/wp_link_pages');
            comments_template();
            ?>
        </div>
        <div class="col-sm-4 <?php echo $sidebar_select_aside_classes; ?>">
            <?php
            get_sidebar();
            ?>
        </div>
    </div>
<?php
}
?>