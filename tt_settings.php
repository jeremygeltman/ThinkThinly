<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc= sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ=="
      crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"
        integrity="sha256-Sk3nkD6mLTMOF0EOpNtsIry+s1CsaqQC1rVLTAy+0yc= sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ=="
        crossorigin="anonymous"></script>
<body>
<?php

require_once("wp-blog-header.php");

global $wpdb;

if (!array_key_exists('secret', $_REQUEST) || $_REQUEST['secret'] != "thinkthinlyrocks"){
    echo 'wrong secret'; die();
}

if (! empty($_POST)) {
    extract($_POST);
    if (isset($payment_amount)) {
        $wpdb->update('tt_settings', array('value'=>$payment_amount), array('name'=>'payment_amount'));
    }
    if (isset($default_trial_period)) {
        $wpdb->update('tt_settings', array('value'=>$default_trial_period), array('name'=>'default_trial_period'));
    }

}

$payment_amount = $wpdb->get_row("SELECT value from tt_settings where name= 'payment_amount'");
$default_trial_period = $wpdb->get_row("SELECT value from tt_settings where name= 'default_trial_period'");
if (property_exists($payment_amount, 'value')):
    ?>
    <div class="container-fluid">

        <form action="" method="post" role="form">
            <legend>Thinkthinly Settings</legend>

            <div class="form-group">
                <label for="payment_amount">Payment Amount</label><br/>
                <input disabled="disabled" name="payment_amount" value="<?= $payment_amount->value ?>">
            </div>
            <div class="form-group">
                <label for="default_trial_period">Default Trial Period (days)</label><br/>
                <input name="default_trial_period" value="<?= $default_trial_period->value ?>">
            </div>

            <input type="hidden" value="thinkthinlyrocks" name="secret">
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

<?php endif; ?>

</body>

