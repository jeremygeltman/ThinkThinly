<?php
/**
 * The template for displaying article headers
 *
 * @since 1.0.6
 */
$bavotasan_theme_options = bavotasan_theme_options();
global $paged;
?>

	<h1 class="entry-title taggedlink">
		<?php if ( is_single() ) : ?>
			<?php the_title(); ?>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php endif; // is_single() ?>
	</h1>

	<?php if ( ! is_page_template( 'page-templates/template-post-block.php') ) { ?>
	<div class="entry-meta">
		<?php
		$display_author = $bavotasan_theme_options['display_author'];
		if ( $display_author )
			printf( __( 'by %s', 'arcade' ),
				'<span class="vcard author"><span class="fn"><a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . esc_attr( sprintf( __( 'Posts by %s', 'arcade' ), get_the_author() ) ) . '" rel="author">' . get_the_author() . '</a></span></span>'
			);

		$display_date = $bavotasan_theme_options['display_date'];
		if( $display_date ) {
			if( $display_author )
				echo '&nbsp;' . __( 'on', 'arcade' ) . '&nbsp;';

		    echo '<a href="' . get_permalink() . '" class="time"><time class="date published updated" datetime="' . get_the_date( 'Y-m-d' ) . '">' . get_the_date() . '</time></a>';
	    }

		$display_categories = $bavotasan_theme_options['display_categories'];
		if( $display_categories ) {
			if( $display_author || $display_date )
				echo '&nbsp;' . __( 'in', 'arcade' ) . '&nbsp;';

		    the_category( ', ' );
	    }

		$display_comments = $bavotasan_theme_options['display_comment_count'];
		if( $display_comments && comments_open() ) {
			if ( $display_author || $display_date || $display_categories )
				echo '&nbsp;&bull;&nbsp;';

			comments_popup_link( __( '0 Comments', 'arcade' ), __( '1 Comment', 'arcade' ), __( '% Comments', 'arcade' ) );
		}
		?>
	</div>
	<?php } ?>