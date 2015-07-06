<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

<?php
		if (function_exists('orderStyleJS')) {
			orderStyleJS( 'start' );
		}
	?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--<script src="<?php // echo get_template_directory_uri(); ?>/js/masonry.pkgd.min.js"></script>-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo get_template_directory_uri(); ?>/css/menu.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.css" rel="stylesheet" />
	<script src="<?php echo get_template_directory_uri(); ?>/js/script.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/lightbox.css" type="text/css" media="screen" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png">


	
	<!--[if lt IE 7]>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/ie_png.js"></script>
	<script type="text/javascript">ie_png.fix('.png, .link1 span, .link1');</script>
	<link href="<?php echo get_template_directory_uri(); ?>/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	
	
	<![endif]-->
	
	
	
	
<?php  wp_head(); ?>

<?php
		if (function_exists('orderStyleJS')) {
			orderStyleJS( 'end' );
		}
?>

</head>


<body id="page1">

<!-- START PAGE SOURCE -->

  <div id="main">
     <div id="header">
     <div class="inner-wrapper">
        <div class="row-1">
          <div class="fleft"><a href="<?php bloginfo('url');?>"> Get<span>Daily</span>Updates</a></div>
        
        </div>
        <input id="menu-toggle" class="menu-toggle" type="checkbox">
        <nav style="display: none;">
	<div class="menu">
	<ul>
		<li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
		<li><a href="#">Work</a><ul>
        	<li><a href="#">Photoshop</a></li><li><a href="#">Illustrator</a></li><li><a href="#">Web Design</a></li></ul></li>
		<li><a href="#">Articles</a></li>
		<li><a href="#">Contact</a></li>
	</ul>
</div></nav>
<section id="content_1">
<div class="top-bar">
  <label for="menu-toggle" id="toggle">≡ </label> 
  </div>
  </section> 
        
        <div class="row-2">
          <ul>       
            <li class="must-read-list"><a href="#">About Us <i class="fa fa-sort-down"></i></a>
            
          <div class="box">
        
                  <div class="inner">
					    <?php $sel=mysql_fetch_array(mysql_query("select * from wp_posts where ID=64"));
       
         
         ?>
         <h3><?php the_field('sub_title','64'); ?></h3>
          <p> <?php  echo $sel['post_content']; ?></p>
              </div>
            </div>
            </li>
            <li class="social-media-list"><a href="#">Digital Marketing <i class="fa fa-sort-down"></i> </a>
            
            <div class="box">
        
              <div class="inner">
                                <div class="page_container">
              <ul class="channel">
				    
				  <?php
				$args = array(
										'type'                     => 'post',
										'child_of'                 => 0,
										'parent'                   => 12,
										'taxonomy'                 => 'category',
										'pad_counts'               => false ,
										'number'                   => '5',
										'orderby'                  => 'name',
										'order'                    =>'ASC'
									); 
				
				$categories = get_categories( $args );
				$j=0;
				//echo "<pre>";
				//print_r($categories); exit;
				foreach($categories as $cat)
				{
				
				
				$catid = $cat->term_id;
				$cat_name = $cat->name;
				$catlink = get_category_link( $catid );
				$args2 = array(
									'posts_per_page'   => 5,
									'offset'           => 0,
									'category'         => $catid,
									'orderby'          => 'post_date',
									'order'            => 'DESC',
									'post_type'        => 'post',
									'post_mime_type'   => '',
									'post_parent'      => '',
									'post_status'      => 'publish',
									'suppress_filters' => true
							);
				$posts_array = get_posts( $args2 );
				//echo "<pre>";
				//print_r($posts_array); exit;
				
				?>
	
              
                 <li rel="<?php the_ID();?>" class="fb hovermenu" id="smm_<?php echo $catid;?>" ><a href="<?php echo $catlink ?>"><?php echo $cat_name; ?> ❯</a> 
               
                <ul  class="sub_nav <?php if($j<1){ echo "activemenu";} else { echo "inactivemenu";}?>" id="<?php the_ID();?>">
                  <?php 
                 foreach($posts_array as $p)
				{ ?>
              <li class="subnav-post"><a href="<?php echo get_permalink( $p->ID ) ?>"><?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'thumbnail' ); ?><img src="<?php echo $image[0]; ?>" alt="" /><label><?php  $a=$p->post_title; echo substr($a,0,26);?> </a> </label></li> 
              
              <?php }?>            
            
              </ul>
             </li>
              
                 
              
              
   
             
             <?php 
              $j++;
		  }
            
             ?>
              
				  
             
             
              
              </ul>
                 
              </div>
              </div>
            </div>
            
            </li>
            <li class="tech-list"><a href="#">Gallery <i class="fa fa-sort-down"></i> </a>
              
            <div class="box">
        
              <div class="inner">
                                <div class="page_container">
              <ul class="channel">
				    
				  <?php
				$args = array(
										'type'                     => 'post',
										'child_of'                 => 0,
										'parent'                   => 21,
										'taxonomy'                 => 'category',
										'pad_counts'               => false ,
										'number'                   => '5',
										'orderby'                  => 'name',
	                                 'order'                    => 'Asc'
										
									); 
				
				$categories = get_categories( $args );
				$j=0;
				//echo "<pre>";
				//print_r($categories); exit;
				foreach($categories as $cat)
				{
				
				
				$catid = $cat->term_id;
				$cat_name = $cat->name;
				$catlink = get_category_link( $catid );
				$args2 = array(
									'posts_per_page'   => 5,
									'offset'           => 0,
									'category'         => $catid,
									
									'post_type'        => 'post',
									'post_mime_type'   => '',
									'post_parent'      => '',
									'post_status'      => 'publish',
									'suppress_filters' => true
							);
				$posts_array = get_posts( $args2 );
				//echo "<pre>";
				//print_r($posts_array); exit;
				
				?>
	
              
                 <li rel="<?php the_ID();?>" class="fb hovermenu" id="smm_<?php echo $catid;?>" ><a href="<?php echo $catlink ?>"><?php echo $cat_name; ?> ❯</a> 
               
                <ul  class="sub_nav <?php if($j<1){ echo "activemenu";} else { echo "inactivemenu";}?>" id="<?php the_ID();?>">
                  <?php 
                 foreach($posts_array as $p)
				{ ?>
              <li class="subnav-post"><a href="<?php echo  get_permalink( $p->ID ) ?>"> <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'thumbnail' ); ?><img src="<?php echo $image[0]; ?>" alt="" /><label><?php  $a=$p->post_title; echo substr($a,0,26);?> </a></label></li> 
              
              <?php }?>            
            
              </ul>
             </li>
              
                 
              
              
   
             
             <?php 
              $j++;
		  }
            
             ?>
              
				  
             
             
              
              </ul>
                 
              </div>
              </div>
            </div>
            
            </li>
            <li class="business-list"><a href="#">Media <i class="fa fa-sort-down"></i> </a>
            
               <div class="box">
        
              <div class="inner">
                                <div class="page_container">
              <ul class="channel">
				  
				  
				  <?php
				$args = array(
										'type'                     => 'post',
						                'parent'                   => 8,
										'taxonomy'                 => 'category',
										'pad_counts'               => false ,
										'number'                   => '5'
									); 
				
				$categories = get_categories( $args );
				$j=0;
				//echo "<pre>";
				//print_r($categories); exit;
				foreach($categories as $cat)
				{
				
				
				$catid = $cat->term_id;
				$cat_name = $cat->name;
				$catlink = get_category_link( $catid );
				$args2 = array(
									'posts_per_page'   => 5,
									'offset'           => 0,
									'category'         => $catid,
									'orderby'          => 'post_date',
									'order'            => 'DESC',
									'post_type'        => 'post',
									'post_mime_type'   => '',
									'post_parent'      => '',
									'post_status'      => 'publish',
									'suppress_filters' => true
							);
				$posts_array = get_posts( $args2 );
				//echo "<pre>";
				//print_r($posts_array); exit;
				
				?>
	
              
                 <li rel="<?php the_ID();?>" class="fb hovermenu" id="smm_<?php echo $catid;?>" ><a href="<?php echo $catlink ?>"><?php echo $cat_name; ?> ❯</a> 
               
                <ul  class="sub_nav <?php if($j<1){ echo "activemenu";} else { echo "inactivemenu";}?>" id="<?php the_ID();?>">
                  <?php 
                 foreach($posts_array as $p)
				{ 
					//print_r($p);
					
					?>
				
              <li class="subnav-post"><a href="<?php echo  get_permalink( $p->ID ) ?>"><?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'thumbnail' ); ?><img src="<?php echo $image[0]; ?>" alt="" /> <label><?php  $a=$p->post_title; echo substr($a,0,26);?></label></a></li> 
              
              <?php }?>            
            
              </ul>
             </li>
              
                 
              
              
   
             
             <?php 
              $j++;
		  }
            
             ?>
              
              
             
              </ul>
                 
              </div>
              </div>
            </div>
            
            </li>
             <li class="usa-list"><a href="#"> Business <i class="fa fa-sort-down"></i> </a>
             
           <div class="box">
        
              <div class="inner">
                                <div class="page_container">
              <ul class="channel">
				    
				  <?php
				$args = array(
										'type'                     => 'post',
										'child_of'                 => 0,
										'parent'                   => 4,
										'taxonomy'                 => 'category',
										'pad_counts'               => false ,
										'number'                   => '5',
										'order'                    =>'DESC'
									); 
				
				$categories = get_categories( $args );
				$j=0;
				//echo "<pre>";
				//print_r($categories); exit;
				foreach($categories as $cat)
				{
				
				
				$catid = $cat->term_id;
				$cat_name = $cat->name;
				$catlink = get_category_link( $catid );
				$args2 = array(
									'posts_per_page'   => 5,
									'offset'           => 0,
									'category'         => $catid,
									'orderby'          => 'post_date',
									'order'            => 'DESC',
									'post_type'        => 'post',
									'post_mime_type'   => '',
									'post_parent'      => '',
									'post_status'      => 'publish',
									'suppress_filters' => true
							);
				$posts_array = get_posts( $args2 );
				//echo "<pre>";
				//print_r($posts_array); exit;
				
				?>
	
              
                 <li rel="<?php the_ID();?>" class="fb hovermenu" id="smm_<?php echo $catid;?>" ><a href="<?php echo $catlink ?>"><?php echo $cat_name; ?> ❯</a> 
               
                <ul  class="sub_nav <?php if($j<1){ echo "activemenu";} else { echo "inactivemenu";}?>" id="<?php the_ID();?>">
                  <?php 
                 foreach($posts_array as $p)
				{ ?>
              <li class="subnav-post"><a href="<?php echo  get_permalink( $p->ID ) ?>"><?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'thumbnail' ); ?><img src="<?php echo $image[0]; ?>" alt="" /><label><?php  $a=$p->post_title; echo substr($a,0,26);?></a></label></li> 
              
              <?php }?>            
            
              </ul>
             </li>
              
                 
              
              
   
             
             <?php 
              $j++;
		  }
            
             ?>
              
				  
             
             
              
              </ul>
                 
              </div>
              </div>
            </div>
            
             </li>
            <li class="more-list"><a href="#">More <i class="fa fa-sort-down"></i> </a>
            
            <div class="box">
        <div class="inner">
                                        
        <div class="more_navbar">
       <h1>Advertise</h1>
       <ul class="inner_more">
       <li><p><a href="#">Advertise with the leading source of news, information &amp; resources for the Connected Generation today. Learn more about who we are and how we can work together.</a> </p></li>
      
       </ul>
       </div>
       
        <div class="more_navbar">
       <h1>Contact Us</h1>
       <ul class="inner_more">
       

       <li><a href="mailto:info@getdailyupdates.com">info[at]getdailyupdates[dot]com</a></li>
      <li><a href="mailto:support@getdailyupdates.com">support[at]getdailyupdates[dot]com</a></li>

       </ul>
       </div>
       
           <div class="more_navbar">
       <h1>Submit</h1>
       <ul class="inner_more">
       <li><a href="#">News</a></li>
       <li><a href="#">Article</a> </li>
       <li><a href="#">Events</a></li>
        <li><a href="#">Banner Advertisement</a></li>
       </ul>
       </div>
       
       
       
            </div>
      
      </div>
            
            </li>
            
          </ul>
        </div>
        <div class="row-3">
          <ul>
        <li> 
        <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
        <div class="box_search">
			
<input type="text" value="" name="s" id="s" />
<input type="button" id="searchsubmit" value="" onClick='submitDetailsForm()'>
</div>

</form>
<script language="javascript" type="text/javascript">
    function submitDetailsForm() {
       $("#searchform").submit();
    }
</script>
        </li>
            <li><a href="https://www.facebook.com/Getdailyupdates" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social.png" /></a></li>
            <li><a href="https://twitter.com/Getdailyupdates" target="_blank"><img src="<?php bloginfo('template_url');?>/images/twitter.png" /></a></li>
            <li><a href="https://plus.google.com/108630945278393148558/posts" target="_blank"><img src="<?php bloginfo('template_url');?>/images/google.png" /></a></li>
           <li><a href="http://www.pinterest.com/getdailyupdates/" target="_blank" ><img src="<?php bloginfo('template_url');?>/images/pinterest.png" /></a></li>
             <li><a href="http://instagram.com/getdailyupdates/" target="_blank" ><img src="<?php bloginfo('template_url');?>/images/instagram.png" /></a></li>
           <li><a href="<?php bloginfo('rss_url'); ?>"><img src="<?php bloginfo('template_url');?>/images/rss.png" /></a></li>
           
          </ul>
     
        </div>
        <div class="clear"></div>
        </div>
        
        </div>
      </div>
     
    
      
      </div>
      
      
   
      <div id="content">

<!--
<script>
var span = document.getElementsByTagName('span')[0];
span.textContent = 'Daily'; // change DOM text content
span.style.display = 'inline';  // change CSSOM property
// create a new element, style it, and append it to the DOM
var loadTime = document.createElement('div');
loadTime.textContent = 'You loaded this page on: ' + new Date();
loadTime.style.color = 'blue';
document.body.appendChild(loadTime);
</script>
-->

<script type="text/javascript">
	$(document).ready(function(){
		$(".tab_content").hide();
		$(".tab_content:first").show(); 
		$("ul.tabs li:first").addClass("active");
		$("ul.tabs li").click(function() {
			
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_content").hide();
		var activeTab = $(this).attr("rel"); 
		$("#"+activeTab).fadeIn(); 
		var catname = $(this).attr('title');
		//alert(catname);
	$.ajax({ 
		type: 'POST',
		url: "http://www.getdailyupdates.com/data.php", //give your URL here
		
		data: {name:catname} , //(optional) if you wish you can send this data to server, just like this.
		success: function(data){
			//alert(data);
		  $('.data').html(data); //here is your data
		    
		}
   
	  });
	
	
	});
		
		$.ajax({
		
		url: "http://www.getdailyupdates.com/data.php", //give your URL here
		success: function(data){
		  $('.data').html(data); //here is your data
		   
		}
   
	  });
		
      
});
</script>
 
 <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({cache: false}); // disabling cache, omit if u dont need
            var defaultBtnText = "Load More Content";
            var buttonLoadingText = "<img src='/wp-content/themes/blog/images/ajax-loader.gif' alt='' /> Loading.."; 
           var counter=0;
	  
		
	    $(document).scroll(function(){ 
         if ($(window).scrollTop() + $(window).height() >= $(document).height())
               {
                 
                   loadMore();
           }
          });
            
            $("#loadButton").live( "click", function() {
		
                loadMore();

           });
            
            function loadMore()
            { counter++;
                $("#loadButton").html(buttonLoadingText); var catname = $(".data input#loadmoredata").val();
	
                $.ajax({
                    url: 'load.php',
                    method: 'get',
		   data: {count: counter, name:catname},
                    success: function(data){
                        $("#bodyContent").append(data);
                      
			$('#content').append(data);
                    }
                });
            }
   
        });
    </script>
 
    <style type="text/css">
        
        
       
        
       
        
        .bodyContent {
            
            
            margin: 0 auto;
            padding: 5px;
            position: relative;
            margin-bottom: 20px;
        }
        
        
    </style>
