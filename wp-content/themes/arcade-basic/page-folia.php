<?php
/**
 * Template Name: Page Folia
 *
 * @package WordPress2
 * @subpackage Twenty_Fourteen2
 * @since Twenty Fifteen
 */
?>

    <section id="layout1-main-section" class="parallax-scroll1">
    	
    	<div class="overlay"></div>
    	
    	<div class="container">
    		<div class="row">
    			<div class="col-md-8 col-md-offset-2">
	    			
	    			<h1><a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	    			
	    			<p class="main-text"><?php bloginfo( 'description' ); ?></p>

					<div class="subscribe-form center-block clearfix">
						<?php
                        if ( is_front_page() ) {

                            $featuredPosts = new WP_Query('');
                            $featuredPosts->query('');
                            while ($featuredPosts->have_posts()) : $featuredPosts->the_post(); 
                                
                            the_content( __( 'Read more', 'arcade') );
                           ?>
                                                        
                            <?php endwhile;
                        }

						if ( ! defined( 'BAVOTASAN_SEE_MORE' ) )
							define( 'BAVOTASAN_SEE_MORE', __( 'See More', 'arcade' ) );
						?>
	    				
	    			</div>
	    			




	    			
    			</div>
    		</div>
    	</div>
    </section>
    
    
    <section id="layout1-features">
    	<div class="container">
    		<div class="row">
    			
    			<div class="col-sm-4 feature-item">
					<div class="feature-wrapper clearfix">
						<div class="feature-icon"><img src="<?php bloginfo('template_directory');?>/folia/img/feature-icon-alarm.png" /></div>
						<div class="text-container pull-left">
							<h3><span class="number">1.</span> Pick your reminder times</h3>
							<p>Tell us when you need a boost.</p>
						</div>
					</div>
				</div>
			
    			<div class="col-sm-4 feature-item">
					<div class="feature-wrapper clearfix">
						


						<div class="feature-icon"><img src="<?php bloginfo('template_directory');?>/folia/img/feature-icon-heart-message.png" /></div>



						<div class="text-container pull-left">
							<h3><span class="number">2.</span> Get motivational picture texts</h3>
							<p>We’ll text you photos that will make you want to choose to sweat instead of sit.</p>
						</div>
					</div>
				</div>

				<div class="col-sm-4 feature-item">
					<div class="feature-wrapper clearfix">
						<div class="feature-icon"><img src="<?php bloginfo('template_directory');?>/folia/img/feature-icon-happy.png" /></div>
						<div class="text-container pull-left">
							<h3><span class="number">3.</span> Get fit finally</h3>
							<p>Get buff and make your friends jealous. For real this time.</p>
						</div>
					</div>
				</div>
    			
    		</div>
    	</div>
    </section>
    


	<div id="layout1-testimonials" class="parallax-scroll2">
		
			<div class="overlay"></div>
			
			<div class="container">
				
				<div class="row">
					
					<div class="col-md-12">
						
						<ul id="testi-slider" class="owl-carousel">
							
							<li class="testi-slide">
								<a href=""><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/05/Steph-round-headshot-pic.png" alt="" /></a>
								<p> “I finally lost that last 10 pounds of baby weight with ThinkThinly. It was like a motivational coach with me all day.”</p>
								<span>Sara, 34, San Diego</span>
							</li>
							
							<li class="testi-slide">
								<a href=""><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/05/Anna-Long-headshot-round.png" alt="" /></a>
								<p> “ThinkThinly is often the boost I need to get me to the gym.  It probably makes the difference a few times a week.”</p>
								<span>Molly, 42, Albuquerque</span>
							</li>
							
							
						</ul>
					</div>
				</div>
			</div>
		</div>


    
    <section id="layout1-z-layout1">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-6 col-sm-6 text-wrapper">
					
					<h2>Pictures Worth 1,000 Calories</h2>
					
					<!-- <span class="subtitle">Just o make your brand with he latest trends</span> -->
					
					<p>You're great sticking to your no-carb-Mediterranean-vegan-paleo-real-food diet but when 5:30 hits and you can head home or to the gym, you want to just go easy.  We get it.</p>

                    <p>Imagine if right at the moment you were about to cave you got a text with a photo reminding you of how you can choose to feel.  Would it push you off the ledge of the couch?  Science says it will.  And science knows everything.</p>
					
					<a href="" id="more-site" class="button btn btn-default btn-lg" data-scroll-to="0">Free sign up</a>
					
				</div>
				
				<div class="col-lg-6 col-sm-6 clearfix">
					<a href=""><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/06/1000-calories-pic.png" class="img-responsive pull-right" alt="" /></a>	
				</div>
				
			</div>
		</div>
	</section>


	
		

	<section id="layout1-subscribe-form" class="parallax-scroll3">
		
		<div class="overlay"></div>
		
		<div class="container">
			<div class="row">
				
				<div class="col-sm-6">
					
					<h2>Get motivated to go further</h2>
					<span class="subtitle">Get a test message right now so you can see if this is for you.</span>
					<!-- <p>You don't need to enter any payment information.  Get a test message now to see what this is all about.</p> -->
					
				</div>
				
				<div class="col-sm-6 col-md-5 col-md-offset-1">
					<form action="#" novalidate="novalidate">
						
						<h4>Try it now</h4>
						<input type="text" name="subscribe_phone" class="form-control" placeholder="Phone">
                        <select id="ewd-feup-register-input-11" name="subscribe_gender" class="ewd-feup-select pure-input-1-3" required="">
                           <!-- <option value="">Gender</option>-->
                            <option value="Female" selected>Female</option>
                            <option value="Male">Male</option>
                            <!--<option value="Other">Other</option> -->
                        </select>
                        <div class="terms_cond">
                            By signing up I accept the <a href="/terms-and-conditions" target="_blank">Terms and Conditions</a>
                        </div>

						<div class="holder clearfix">
							<!-- <span class="pull-left">* we are note spammers <br>Read Privacy for more information</span> -->
							<button class="btn btn-default" id="subscribe_btn" type="button">Try it out</button>
						</div>
						
					</form>
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
    
