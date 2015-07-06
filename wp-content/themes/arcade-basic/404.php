<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @since 1.0.0
 */
get_header(); ?>

	<div class="container">
		<div class="row">
			<div id="primary" <?php bavotasan_primary_attr(); ?>>
    			<article id="post-0" class="post error404 not-found">
    				<i class="fa fa-frown-o"></i>
    		    	<header>
    		    	   	<h1 class="entry-title taggedlink"><?php _e( '404 Error', 'arcade' ); ?></h1>
    		        </header>
    		        <div class="entry-content description">
    		            <p><?php _e( "Sorry. We can't seem to find the page you're looking for.", 'arcade' ); ?></p>
    		        </div>
    		    </article>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>

<?php get_footer(); ?>