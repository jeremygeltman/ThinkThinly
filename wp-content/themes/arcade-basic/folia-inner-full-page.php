<?php
/**
 * Template Name: Folia Inner
 *
 */
?>
<?php get_header(); ?>

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
                    <a href="" class="navbar-brand logo"><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/06/ThinkThinlyLogo.png" alt="ThinkThinly Logo"></a>
                </div><!-- Collect the nav links, forms, and other content for toggling -->

                <div class="collapse navbar-collapse" id="header-navigation">
                	<ul class="nav navbar-nav main-navigation navbar-right">
                   		<?php wp_nav_menu( array( 'items_wrap' => '%3$s' ));?> 
                   	</ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </header><!-- Navigation -->
    
    
    <section id="layout1-main-section" class="parallax-scroll1">
    	
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
    
    
    <section id="layout1-features">
    	<div class="container">
    		<div class="row">
    			
    			<div class="col-sm-4 feature-item">
					<div class="feature-wrapper clearfix">
						<div class="feature-icon"><img src="<?php bloginfo('template_directory');?>/folia/img/feature-icon-alarm.png" /></div>
						<div class="text-container pull-left">
							<h3><span class="number">1.</span> Pick your reminder times</h3>
							<p>Tell us your mealtimes or moments of weakness when you want help staying on track 
</p>
						</div>
					</div>
				</div>
			
    			<div class="col-sm-4 feature-item">
					<div class="feature-wrapper clearfix">
						


						<div class="feature-icon"><img src="<?php bloginfo('template_directory');?>/folia/img/feature-icon-heart-message.png" /></div>



						<div class="text-container pull-left">
							<h3><span class="number">2.</span> Get motivational picture texts</h3>
							<p>We’ll text you photos that will make you want to choose carrots over cupcakes.</p>
						</div>
					</div>
				</div>

				<div class="col-sm-4 feature-item">
					<div class="feature-wrapper clearfix">
						<div class="feature-icon"><img src="<?php bloginfo('template_directory');?>/folia/img/feature-icon-happy.png" /></div>
						<div class="text-container pull-left">
							<h3><span class="number">3.</span> Lose weight finally</h3>
							<p>Watch the pounds melt off and make your friends jealous. Seriously this time. No take backs.</p>
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
								<p> “I finally lost that last 10 pounds of baby weight with Think Thinly. It kept my eye on the prize by reminding me what I don’t want to look like.”</p>
								<span>Sara, 34, San Diego</span>
							</li>
							
							<li class="testi-slide">
								<a href=""><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/05/Anna-Long-headshot-round.png" alt="" /></a>
								<p> “Think Thinly is better than the skinny pic of myself I put on the fridge to stop me from eating crap because it’s in my pocket all of the time.”</p>
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
					
					<p>You're great sticking to your no-carb-Mediterranean-vegan-paleo-real-food diet until about 3:30 when that peanut butter brownie in the break room starts calling to you. Brownies are persuasive.  We get it.</p>

                    <p>Imagine if right at the moment you were about to cave you got a text with a photo reminding you what you want to look like.  Would it make you back away from the ledge of death by brownie?  Science says it will.  And science knows everything.  Give it a try for free.  
</p>
					
					<a href="" class="button">Free sign up</a>
					
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
					
					<h2>Try it out for free</h2>
					<span class="subtitle">There's no cost to sign up and give it a try.</span>
					<p>You don't need to enter any payment information.  Get a test message now to see what this is all about.</p>
					
				</div>
				
				<div class="col-sm-6 col-md-5 col-md-offset-1">
					<form action="#" novalidate="novalidate">
						
						<h4>Subscribe now - Free 30 Day Trial</h4>
						<input type="text" name="subscribe_phone" class="form-control" placeholder="Phone">
                        <select id="ewd-feup-register-input-11" name="subscribe_gender" class="ewd-feup-select pure-input-1-3" required="">
                            <option value="">Gender</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                            <option value="Other">Other</option>
                        </select>
						<div class="holder clearfix">
							<!-- <span class="pull-left">* we are note spammers <br>Read Privacy for more information</span> -->
							<button class="btn btn-default pull-right" id="subscribe_btn" type="button">Try it out</button>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</section>
	
	
	
	
	
	<section id="layout1-prefooter">
    	<div class="container">
    		<div class="row">
    			
    			<div class="col-md-4 col-sm-12 about">
	    			<a href="" title="instasent"><img src="<?php bloginfo('url');?>/wp-content/uploads/2015/06/ThinkThinly-logo-v4-inverse-text.png" alt="logo" /></a>
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
	
	
	<script src="<?php bloginfo('template_directory');?>/folia/js/jquery-2.1.0.min.js" type="text/javascript"></script>
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
    
