<?php
$nimbus_blog_sidebar_position = nimbus_get_option('nimbus_blog_sidebar_position');
if ($nimbus_blog_sidebar_position == 'right') {
    $sidebar_select_aside_classes = '';
    $sidebar_select_content_classes = '';
} else {
    $sidebar_select_aside_classes = 'col-sm-pull-8';
    $sidebar_select_content_classes = 'col-sm-push-4';
}
?>
<div class="main_content content row content_squeeze">

    <div class="col-sm-8 blog_content_col <?php echo $sidebar_select_content_classes; ?>">
        <?php
        global $more;
        $more = 0;
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <div <?php post_class('row blog_content_row'); ?>>
                    <div class="row blog_content_row">
                        <div class="col-sm-4">
                            <?php
                            get_template_part( 'parts/image', '192_186'); 
                            ?>
                            <p class="blog_date"><span class="mo"><?php the_time( 'M' ); ?></span><br /><span class="day"><?php the_time( 'j' ); ?></span></p>
                        </div>
                        <div class="col-sm-8">
                            <h2 class="blog_post_title"><a href="<?php the_permalink(); ?>"><?php get_template_part( 'parts/title', 'post'); ?></a></h2>
                            <?php 
                            the_excerpt(); 
                            ?>
                        </div>
                    </div>
                    <div class="row blog_meta">
                        <div class="col-sm-8">
                            <p class="blog_meta_left"> 
                            <?php 
                            if (has_category()) {
                                _e('Posted in: ', 'nimbus'); 
                                the_category(', ');
                            } 
                            ?>
                            | By <?php the_author_posts_link(); ?></p>
                        </div>
                        <div class="col-sm-4">
                            <?php if (comments_open()) { ?><p class="text-right"><a href="<?php the_permalink(); ?>#comments" ><?php comments_number( 'No comments', 'One comment', '% comments' ); ?></a></p><?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
                get_template_part( 'parts/error', 'no_results');
        }
        get_template_part( 'parts/blog', 'pagination');
        ?>
    </div>
    <div class="col-sm-4 blog_sidebar_col <?php echo $sidebar_select_aside_classes; ?>">
        <?php
        get_sidebar();
        ?>
    </div>
</div>


                    

                        
              
 
  
