<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main>
 * and the left sidebar conditional
 *
 * @since 1.0.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">




<!-- folia includes start -->
	
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind:400,300,500,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,700,900,400italic,300italic,700italic,900italic' rel='stylesheet' type='text/css'>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/fonts/flaticon.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/magnific-popup.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/owl.carousel.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/owl.theme.css" type="text/css" media="all">
<!--    <link rel="stylesheet" href="--><?php //bloginfo('template_directory');?><!--/folia/css/bootstrap-select.min.css" type="text/css" media="all">-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/animate.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/folia/css/style-switch.css" type="text/css" media="all">
<!--    <link rel="stylesheet" id="css-color" href="--><?php //bloginfo('url');?><!--/folia/css/skins/orange.css"/>-->
<!-- folia includes end -->



	<!--[if IE]><script src="<?php echo BAVOTASAN_THEME_URL; ?>/library/js/html5.js"></script><![endif]-->
	<?php
    wp_head(); ?>


</head>
<?php
$bavotasan_theme_options = bavotasan_theme_options();
$space_class = '';
global $paged;
?>
<body <?php body_class(); ?>>

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-NQBB2F"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NQBB2F');</script>
<!-- End Google Tag Manager -->    

	<div id="page">

    <header id="header">


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

<!-- start header for arcade basic  -->
             <div class="title-card-wrapper">
                <div class="title-card">
                    <div id="site-meta">
                        <h1 id="site-title">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h1>

                        <?php if ( $bavotasan_theme_options['header_icon'] ) { ?>
                        <i class="fa <?php echo $bavotasan_theme_options['header_icon']; ?>"></i>
                        <?php } else {
                            $space_class = ' class="margin-top"';
                        } ?>





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




                        
                    </div>

<!-- bavotasan_header_images start -->
                    <?php
                    // Header image section
                    bavotasan_header_images();
                    ?>
<!-- bavotasan_header_images end  -->

                </div>
            </div>

        </header>

<!-- end header for arcade basic -->


		<main>


<!-- post_header_images start -->
                    
<!-- post_header_images end -->


	<div id="layout1-testimonials" class="parallax-scroll2" style="background-image:url('<?php post_header_image(); ?>');">
		
			<div class="overlay"></div>
			
			<div class="container">
				
				<div class="row">
		
					<div class="col-md-12">
						<h1 id="site-title">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h1>

					</div>
				</div>
			</div>
		</div>








	<div class="container">
		<div class="row">
			<div id="primary" <?php bavotasan_primary_attr(); ?>>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

					<div id="posts-pagination" class="clearfix">
						<h3 class="sr-only"><?php _e( 'Post navigation', 'arcade' ); ?></h3>
						<div class="previous pull-left"><?php previous_post_link( '%link', __( '&larr; %title', 'arcade' ) ); ?></div>
						<div class="next pull-right"><?php next_post_link( '%link', __( '%title &rarr;', 'arcade' ) ); ?></div>
					</div><!-- #posts-pagination -->

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>


	
		

	
	
	
	
	
	
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