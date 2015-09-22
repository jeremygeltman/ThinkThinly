<?php
/**
 * Template Name: Folia Inner
 *
 */
?>
<?php get_header(); ?>
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/inner.css"
    <header id="layout1-header">
        <nav class="navbar main-navigation-header top-nav navbar-fixed-top">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->

                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-navigation">
	                    <span class="sr-only">Toggle navigation</span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                </button>
                    <a href="<?php bloginfo('url');?>" class="navbar-brand logo"><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/06/ThinkThinlyLogo.png" alt="ThinkThinly Logo"></a>
                </div><!-- Collect the nav links, forms, and other content for toggling -->

                <div class="collapse navbar-collapse" id="header-navigation">
                	<ul class="nav navbar-nav main-navigation navbar-right">
                   		<?php wp_nav_menu( array( 'items_wrap' => '%3$s' ));?> 
                   	</ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </header><!-- Navigation -->
    
    
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
    
    
    
	
	
	
	
	<section id="layout1-prefooter">
    	<div class="container">
    		<div class="row">
    			
    			<div class="col-md-4 col-sm-12 about">
	    			<a href="http://www.thinkthinly.com" title=""><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/06/ThinkThinly-logo-v4-inverse-text.png" alt="logo" /></a>
	    			<p>ThinkThinly makes losing weight as easy as getting a text.</p>
	    			<ul class="social">
	    				<li><a href="https://www.facebook.com/thinkthinly" title="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
	    				<li><a href="https://twitter.com/think_thinly" title="twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
	    				<li><a href="https://www.pinterest.com/thinkthinly/" title="pinterest" target="_blank"><i class="fa fa-pinterest"></i></a></li>
	    				<!-- <li><a href="" title="instagram"><i class="fa fa-instagram"></i></a></li> -->
	    			</ul>
    			</div>
    			
    			<div class="col-md-2 col-sm-4 col-xs-6 col-md-offset-2 col-sm-offset-0 features">
	    			<h4>Partners</h4>
	    			<ul>
	    				<li><a href="https://www.myfooddiary.com/?aID=1722" title="distributors">MyFoodDiary.com</a></li>
	    				<li><a href="https://www.myfooddiary.com/?aID=1722&amp;source=lnk">Calorie Counter</a></li>
	    			</ul>
    			</div>
    			
    			<div class="col-md-2 col-sm-4 col-xs-6 company">
	    			<h4>Company</h4>
	    			<ul>
	    				<li><a href="" title="about us">About us</a></li>
	    				<li><a href="" title="contact">Features</a></li>
	    				<li><a href="" title="resources">Resources</a></li>
	    			</ul>	
    			</div>
    			
    			<div class="col-md-2 col-sm-4 col-xs-6 company">
	    			<p class="contact">
	    				<i class="fa fa-envelope-o"></i><a href="mailto:thinkthinly@gmail.com">Get in touch</a>
	    			</p>
    			</div>
    			
    		</div>
    		
    	</div>
	</section>
	
	
	<footer id="layout1-footer">
		<div class="container">
			<div class="row bottom-footer">
			    			
    			<div class="col-sm-6 col-xs-12">
	    			<p>Copyright © 2015 ThinkThinly.com. All rights are reserved.</p>
    			</div>
    			
    			<div class="col-sm-6 col-xs-12">
	    			<ul class="pull-right">
	    				<li><a href="" title="terms and conditions">Privacy and conditions</a></li>
	    				<li><a href="" title="privacy">Terms of use</a></li>
	    			</ul>
    			</div>
    			
    		</div>
    		
		</div>
	</footer>
	
	
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
    
