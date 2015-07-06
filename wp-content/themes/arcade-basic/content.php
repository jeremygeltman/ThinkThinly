<?php
/**
 * The default template for displaying content
 *
 * Used for both single and front-page/index/archive/search.
 *
 * @since 1.0.0
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
		<?php
		// Display a thumbnail if one exists and not on single post
		bavotasan_display_post_thumbnail();

	    get_template_part( 'content', 'header' ); ?>

	    <div class="entry-content description clearfix">
		    <?php
			if ( is_singular() && ! is_front_page() )
			    the_content( __( 'Read more', 'arcade') );
			else
				the_excerpt();
			?>
	    </div><!-- .entry-content -->
	    <?php if ( is_singular() && ! is_front_page() )
	    	get_template_part( 'content', 'footer' ); ?>
	</article><!-- #post-<?php the_ID(); ?> -->