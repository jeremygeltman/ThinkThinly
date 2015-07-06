<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @since 1.0.0
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php $quote = ( is_rtl() ) ? 'right' : 'left'; // Conditional for RTL languages ?>
	    <i class="fa fa-quote-<?php echo $quote; ?> quote"></i>
	    <div class="entry-content description">
		    <?php the_content( __( 'Read more', 'arcade') ); ?>
	    </div><!-- .entry-content -->

	    <?php get_template_part( 'content', 'footer' ); ?>
	</article>