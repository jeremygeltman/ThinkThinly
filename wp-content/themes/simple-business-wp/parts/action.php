<?php 
global $post;
// set variables if front-page
if (is_front_page()) {
    $nimbus_action_text = nimbus_get_option('nimbus_action_text');
    $toggle = nimbus_get_option('nimbus_toggle_action');
}
// Do frontpage banner options
if (is_front_page()) {
    if ($toggle == 1 || $toggle == 'show') {
    ?>
        <div class="row content_squeeze action_row">
            <div class="col-xs-12">
            <?php echo $nimbus_action_text; ?>
            </div>
        </div>
    <?php
    }
} else {
// Not on frontpage
}     
?>