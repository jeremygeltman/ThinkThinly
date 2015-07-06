        </div> <!-- //.container -->
        <?php if (is_active_sidebar( 'Footer Left' ) || is_active_sidebar( 'Footer Center' ) || is_active_sidebar( 'Footer Right' )) { ?>
        <div class="container footer">
            <div class="row content_squeeze footer_widgets">
                <div id="footer_widget_left" class="col-md-4">
                    <?php
                    if (is_active_sidebar( 'Footer Left' )) {
                        dynamic_sidebar( 'Footer Left' );
                    } else {
                        get_template_part( 'parts/example_widgets', 'footer');
                    }
                    ?>
                </div>
                <div id="footer_widget_center_left" class="col-md-4">
                    <?php
                    if (is_active_sidebar( 'Footer Center' )) {
                        dynamic_sidebar( 'Footer Center' );
                    } else {
                        get_template_part( 'parts/example_widgets', 'footer');
                    }
                    ?>
                </div>
                <div id="footer_widget_right" class="col-md-4">
                    <?php
                    if (is_active_sidebar( 'Footer Right' )) {
                        dynamic_sidebar( 'Footer Right' );
                    } else {
                        get_template_part( 'parts/example_widgets', 'footer');
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="container noborder base">
            <div class="row">
                <div class="col-md-5">
                    <p id="copyright"><?php echo nimbus_get_option('copyright') ?></p>
                </div>
                <div class="col-md-5 col-md-offset-2 text-right">
                    <p id="credit"><a href="http://www.nimbusthemes.com/wordpress-themes/simple-business/">Simple Business Theme</a><br />Powered by <a href="http://wordpress.org">WordPress</a></p>
                </div>
            </div>
        </div>
<?php wp_footer(); ?>
</body>
</html>