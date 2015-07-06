<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @since 1.0.0
 */
get_header();
?>

    <div class="container">
        <div class="row">
            <div id="primary" <?php bavotasan_primary_attr(); ?>>
                <?php
                if ( ! is_front_page() ) {
                    while ( have_posts() ) : the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <h1 class="entry-title"><?php the_title(); ?></h1>

                            <div class="entry-content description clearfix">
                                <?php the_content(__('Read more', 'arcade')); ?>
                            </div>
                            <!-- .entry-content -->

                            <?php get_template_part('content', 'footer'); ?>
                        </article><!-- #post-<?php the_ID(); ?> -->

                        <?php
                        comments_template('', true);
                    endwhile;
                }
                ?>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div>

<?php get_footer(); ?>