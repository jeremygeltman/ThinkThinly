<?php
/**
 * Template Name: Folia Inner
 *
 */
?>
<?php get_header(); ?>
    
    
    
    <section id="layout1-main-section">
    	
    	<div class="overlay"></div>
    	
    	<div class="container">
    		<div class="row">
    			<div class="col-md-8 col-md-offset-2">
	    			
	    			<h1><?php single_post_title(); ?> </h1>
	    			
	    			

					<?php
                        if (have_posts()) : while (have_posts()) : the_post(); the_content(); 
                        endwhile; endif;
                    ?>

	   
    			</div>
    		</div>
    	</div>
    </section>
    
    
    
	
	
	<div id="contact-popup" class="mfp-hide">
		
		<form action="#">				
			<a href="" class="close-btn">X</a>		
			<h2>Drop us your message</h2>
			<input type="text" class="form-control" placeholder="Your name">
			<input type="email" class="form-control" placeholder="Email Adress">
			<textarea class="form-control" placeholder="Your Message"></textarea>
			<div class="holder clearfix">
				<button type="submit" class="submit-btn pull-right">Submit <i class="fa fa-envelope-o"></i></button>
			</div>
			
		</form>
		
	</div>
	
	
    <script src="<?php bloginfo('template_directory');?>/folia/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.magnific-popup.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/wow.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.nav.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.scrollto.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.easing.min.js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/jquery.parallax-1.1.3.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/owl.carousel.min.js" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/google-maps.js" type="text/javascript"></script>
    
    <script src="<?php bloginfo('template_directory');?>/folia/js/style-options.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/folia/js/main.js" type="text/javascript"></script>
    
    <script type="text/javascript">
		window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return}js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));
	</script>


	<?php get_footer(); ?>
    
