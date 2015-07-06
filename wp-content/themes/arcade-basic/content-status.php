<?php
/**
 * The template for displaying posts in the Status post format
 *
 * @since 1.0.6
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h3 class="post-format"><?php _e( '<i class="fa fa-plus-square"></i> Status', 'arcade' ); ?></h3>
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 60 ); ?>
		<h1 class="author"><span class="vcard author"><span class="fn"><?php the_author(); ?></span></span></h1>

		<div class="entry-content description">
			<time class="date published updated" datetime="<?php echo get_the_date( 'Y-m-d' ) . 'T' . get_the_time( 'H:i' ) . 'Z'; ?>">
				<?php printf( __( 'Posted on %1$s at %2$s', 'arcade' ), get_the_date(), get_the_time() );	?>
			</time>

			<?php the_content( __( 'Read more', 'arcade') ); ?>
	    </div><!-- .entry-content -->

	    <?php get_template_part( 'content', 'footer' ); ?>
    </article>