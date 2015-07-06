<?php
/**
 * Template Name: Post Block
 *
 * Description: A full width page template that will display 4 posts in a block without any sidebars
 *
 * @since 1.0.3
 */
if ( ! is_front_page() )
	get_header(); ?>

	<div class="container from-the-blog">
		<div class="row">
			<div id="primary" class="col-md-12 hfeed">
				<div class="page-header clearfix">
					<h1 class="pull-left"><?php the_title(); ?></h1>
				</div>

				<div class="row">
					<?php
					/**
					 * You can overwrite the following arguments in your child theme's functions.php by
					 * hooking into the 'bavotasan_post_block_query' and returning a custom array.
					 *
				 	 * https://codex.wordpress.org/Function_Reference/add_filter
					 */
					$bavotasan_post_block_query = new WP_Query( apply_filters( 'bavotasan_post_block_query', array(
						'posts_per_page' => 4,
						'ignore_sticky_posts' => 1,
						'no_found_rows' => true,
					) ) );

					while ( $bavotasan_post_block_query->have_posts() ) : $bavotasan_post_block_query->the_post();
						global $bavotasan_custom_excerpt_length;
						$home_page_post = false;
					    $bavotasan_custom_excerpt_length = 20;
						if ( 1 > $bavotasan_post_block_query->current_post ) {
							$home_page_post = true;
							echo '<div class="col-md-6">';
							$bavotasan_custom_excerpt_length = 50;
						}
						if ( 1 == $bavotasan_post_block_query->current_post )
							echo '<div class="col-md-6">';

						get_template_part( 'content' );

						if ( 1 > $bavotasan_post_block_query->current_post )
							echo '</div>';

						if ( 3 == $bavotasan_post_block_query->current_post )
							echo '</div>';
					endwhile;
					?>
				</div>
			</div>
		</div>
	</div>

<?php if ( ! is_front_page() ) get_footer(); ?>