<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

function optionsframework_option_name() {
   // Change this to use your theme slug
   return 'ample';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {

   $options = array();

   // Pull all the categories into an array
   $options_categories = array();
   $options_categories_obj = get_categories();
   foreach ($options_categories_obj as $category) {
      $options_categories[$category->cat_ID] = $category->cat_name;
   }

   // Header Options Area
   $options[] = array(
      'name' => __( 'Header', 'ample' ),
      'type' => 'heading'
   );
   // Header Logo upload option
   $options[] = array(
      'name'   => __( 'Header Logo', 'ample' ),
      'desc'   => __( 'Upload logo for your header.', 'ample' ),
      'id'     => 'ample_header_logo_image',
      'type'   => 'upload'
   );
   // Header logo and text display type option
   $header_display_array = array(
      'logo_only'    => __( 'Header Logo Only', 'ample' ),
      'text_only'    => __( 'Header Text Only', 'ample' ),
      'both'         => __( 'Show Both', 'ample' ),
      'none'         => __( 'Disable', 'ample' )
   );
   $options[] = array(
      'name'      => __( 'Show', 'ample' ),
      'desc'      => __( 'Choose the option that you want.', 'ample' ),
      'id'        => 'ample_show_header_logo_text',
      'std'       => 'text_only',
      'type'      => 'radio',
      'options'   => $header_display_array
   );
   // Header Title Bar Background Image upload option
   $options[] = array(
      'name'   => __( 'Header Title Bar Background Image', 'ample' ),
      'desc'   => __( 'Upload Background Image for Header Title Bar.', 'ample' ),
      'id'     => 'ample_header_title_background_image',
      'type'   => 'upload'
   );
   // Header Title Bar Background color option
   $options[] = array(
      'desc'      => __( 'Choose Background Color for Header Title Bar', 'ample' ),
      'id'        => 'ample_title_bar_background_color',
      'std'       => '#80abc8',
      'type'      => 'color'
   );
   // Header Title Bar Text color option
   $options[] = array(
      'desc'      => __( 'Choose Text Color for Header Title Bar', 'ample' ),
      'id'        => 'ample_header_title_color',
      'std'       => '#ffffff',
      'type'      => 'color'
   );
   // Header image position option
   $options[] = array(
      'name'      => __( 'Header Image Position', 'ample' ),
      'desc'      => __( 'Choose top header image display position.', 'ample' ),
      'id'        => 'ample_header_image_position',
      'std'       => 'above',
      'type'      => 'radio',
      'options'   => array(
                     'above' => __( 'Position Above (Default): Display the Header image just above the site title and main menu part.', 'ample' ),
                     'below' => __( 'Position Below: Display the Header image just below the site title and main menu part.', 'ample' )
                  )
   );

   /*************************************************************************/

   $options[] = array(
      'name' => __( 'Design', 'ample' ),
      'type' => 'heading'
   );
   // Site Layout
   $options[] = array(
      'name'      => __( 'Site Layout', 'ample' ),
      'desc'      => __( 'Choose your site layout. The change is reflected in whole site.', 'ample' ),
      'id'        => 'ample_site_layout',
      'std'       => 'wide',
      'type'      => 'radio',
      'options'   => array(
                     'wide'  => __( 'Wide layout', 'ample' ),
                     'box'   => __( 'Boxed layout', 'ample' ),
                  )
   );

   $options[] = array(
      'name'      => __( 'Default layout', 'ample' ),
      'desc'      => __( 'Select default layout. This layout will be reflected in whole site archives, search etc. The layout for a single post and page can be controlled from below options.', 'ample' ),
      'id'        => 'ample_default_layout',
      'std'       => 'right_sidebar',
      'type'      => 'images',
      'options'   => array(
                        'right_sidebar' => get_template_directory_uri() . '/inc/admin/images/right-sidebar.png',
                        'left_sidebar' => get_template_directory_uri() . '/inc/admin/images/left-sidebar.png',
                        'no_sidebar_full_width' => get_template_directory_uri() . '/inc/admin/images/no-sidebar-full-width-layout.png',
                        'no_sidebar_content_centered' => get_template_directory_uri() . '/inc/admin/images/no-sidebar-content-centered-layout.png',
                        'both_sidebar' => get_template_directory_uri() . '/inc/admin/images/both-sidebar.png',
                     )
   );

   $options[] = array(
      'name'      => __( 'Default layout for pages only', 'ample' ),
      'desc'      => __( 'Select default layout for pages. This layout will be reflected in all pages unless unique layout is set for specific page.', 'ample' ),
      'id'        => 'ample_pages_default_layout',
      'std'       => 'right_sidebar',
      'type'      => 'images',
      'options'   => array(
                        'right_sidebar' => get_template_directory_uri() . '/inc/admin/images/right-sidebar.png',
                        'left_sidebar' => get_template_directory_uri() . '/inc/admin/images/left-sidebar.png',
                        'no_sidebar_full_width' => get_template_directory_uri() . '/inc/admin/images/no-sidebar-full-width-layout.png',
                        'no_sidebar_content_centered' => get_template_directory_uri() . '/inc/admin/images/no-sidebar-content-centered-layout.png',
                        'both_sidebar' => get_template_directory_uri() . '/inc/admin/images/both-sidebar.png',
                     )
   );

   $options[] = array(
      'name'      => __( 'Default layout for single posts only', 'ample' ),
      'desc'      => __( 'Select default layout for single posts. This layout will be reflected in all single posts unless unique layout is set for specific post.', 'ample' ),
      'id'        => 'ample_single_posts_default_layout',
      'std'       => 'right_sidebar',
      'type'      => 'images',
      'options'   => array(
                        'right_sidebar' => get_template_directory_uri() . '/inc/admin/images/right-sidebar.png',
                        'left_sidebar' => get_template_directory_uri() . '/inc/admin/images/left-sidebar.png',
                        'no_sidebar_full_width' => get_template_directory_uri() . '/inc/admin/images/no-sidebar-full-width-layout.png',
                        'no_sidebar_content_centered' => get_template_directory_uri() . '/inc/admin/images/no-sidebar-content-centered-layout.png',
                        'both_sidebar' => get_template_directory_uri() . '/inc/admin/images/both-sidebar.png',
                     )
   );
   // Site primary color option
   $options[] = array(
      'name'      => __( 'Primary color option', 'ample' ),
      'desc'      => __( 'This will reflect in links, buttons and many others. Choose a color to match your site.', 'ample' ),
      'id'        => 'ample_primary_color',
      'std'       => '#80abc8',
      'type'      => 'color'
   );

   $options[] = array(
      'name'      => __( 'Custom CSS', 'ample' ),
      'desc'      => __( 'Write your custom css.', 'ample' ),
      'id'        => 'ample_custom_css',
      'std'       => '',
      'type'      => 'textarea'
   );

   /*************************************************************************/

   $options[] = array(
      'name' => __( 'Slider', 'ample' ),
      'type' => 'heading'
   );
   // Slider activate option
   $options[] = array(
      'name'      => __( 'Activate slider', 'ample' ),
      'desc'      => __( 'Check to activate slider.', 'ample' ),
      'id'        => 'ample_activate_slider',
      'std'       => '0',
      'type'      => 'checkbox'
   );
   // Slide options
   for($i=1; $i<=4; $i++) {
      $options[] = array(
         'name'   => sprintf( __( 'Slider #%1$s', 'ample' ), $i ),
         'desc'   => __( 'Upload image', 'ample' ),
         'id'     => 'ample_slider_image'. $i,
         'type'   => 'upload'
      );
      $options[] = array(
         'desc'   => __( 'Enter title for this slide', 'ample' ),
         'id'     => 'ample_slider_title'. $i,
         'std'    => '',
         'type'   => 'text'
      );
      $options[] = array(
         'desc'   => __( 'Enter button text', 'ample' ),
         'id'     => 'ample_slider_button_text'. $i,
         'std'    => '',
         'type'   => 'text'
      );
      $options[] = array(
         'desc'   => __( 'Enter link to redirect', 'ample' ),
         'id'     => 'ample_slider_link'. $i,
         'std'    => '',
         'type'   => 'text'
      );
   }

   /*************************************************************************/

   $options[] = array(
      'name' => __( 'Additional', 'ample' ),
      'type' => 'heading'
   );
   // Favicon activate option
   $options[] = array(
      'name'      => __( 'Favicon', 'ample' ),
      'desc'      => __( 'Check to activate favicon. Upload fav icon from below option', 'ample' ),
      'id'        => 'ample_activate_favicon',
      'std'       => '0',
      'type'      => 'checkbox'
   );
   // Fav icon upload option
   $options[] = array(
      'desc'   => __( 'Upload favicon for your site.', 'ample' ),
      'id'     => 'ample_favicon',
      'type'   => 'upload'
   );
   // Select category to hide from Post Page
   if ( $options_categories ) {
      $options[] = array(
         'name' => __( 'Category to hide from Blog', 'ample' ),
         'desc' => __( 'Select a Category or Categories to hide its posts from Blog page.', 'ample' ),
         'id' => 'ample_hide_category',
         'std' => '',
         'type' => 'multicheck',
         'options' => $options_categories
      );
   }

   return $options;
}

add_action( 'optionsframework_after','ample_options_display_sidebar' );
/**
 * Ample admin sidebar
 */
function ample_options_display_sidebar() { ?>
   <div id="optionsframework-sidebar">
      <div class="metabox-holder">
         <div class="postbox">
            <h3><?php esc_attr_e( 'About Ample', 'ample' ); ?></h3>
            <div class="inside">
               <div class="option-btn"><a class="btn upgrade" target="_blank" href="<?php echo esc_url( 'http://themegrill.com/themes/ample-pro/' ); ?>"><?php esc_attr_e( 'Upgrade to Pro' , 'spacious' ); ?></a></div>
               <div class="option-btn"><a class="btn support" target="_blank" href="<?php echo esc_url( 'http://themegrill.com/support-forum/' ); ?>"><?php esc_attr_e( 'Free Support' , 'ample' ); ?></a></div>
               <div class="option-btn"><a class="btn doc" target="_blank" href="<?php echo esc_url( 'http://themegrill.com/theme-instruction/ample/' ); ?>"><?php esc_attr_e( 'Documentation' , 'ample' ); ?></a></div>
               <div class="option-btn"><a class="btn demo" target="_blank" href="<?php echo esc_url( 'http://demo.themegrill.com/ample/' ); ?>"><?php esc_attr_e( 'View Demo' , 'ample' ); ?></a></div>
               <div class="option-btn"><a class="btn rate" target="_blank" href="<?php echo esc_url( 'http://wordpress.org/themes/ample/' ); ?>"><?php esc_attr_e( 'Rate this theme' , 'ample' ); ?></a></div>

                  <div align="center" style="padding:5px; background-color:#fafafa;border: 1px solid #CCC;margin-bottom: 10px;">
                     <strong><?php esc_attr_e( 'If you like our work. Buy us a beer.', 'ample' ); ?></strong>
                     <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                     <input type="hidden" name="cmd" value="_s-xclick">
                     <input type="hidden" name="hosted_button_id" value="8AHDCA8CDGAJG">
                     <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal">
                     <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                  </form>
               </div>
            </div><!-- inside -->
         </div><!-- .postbox -->
      </div><!-- .metabox-holder -->
   </div><!-- #optionsframework-sidebar -->
<?php
}
?>