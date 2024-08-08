<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" /> -->
<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/user-registration/myaccount/form-edit-profile.php.
 *
 * HOWEVER, on occasion UserRegistration will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.wpeverest.com/user-registration/template-structure/
 * @author  WPEverest
 * @package UserRegistration/Templates
 * @version 1.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

$user_groups = sendgridController::get_supression_group();
$user_groups = json_decode($user_groups['response']);
$curr_user = wp_get_current_user();
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sendgrid.com/v3/asm/suppressions/global/".$curr_user->data->user_email,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "{}",
    CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".sendgridController::SG_API_KEY,
        "content-type: application/json"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);


$global_unsubscribe = json_decode($response);
$global_suppressed = 0;
if(isset($global_unsubscribe->recipient_email)) {
    $global_suppressed = 1;
}
?>
<?php

add_shortcode('email_preferences', 'email_preferences_form');
add_action('init','email_preferences_form');
function email_preferences_form($atts) {
    
    ob_start();
    ?>
<div class="ur-form-row">
    <div class="ur-form-grid my-account-content-block my-account-email-preferences">
        <div >
            <h2 class="pull-left" style="margin-left: 20px;">Email Newsletter Preferences </h2>
            <button class="pull-right btn btn-primary" id="subscribe-all-btn">Subscribe to all</button>
            <div class="clearfix"></div>
        </div>
        <div class="preference-block">
            <p style='text-align:left;font-weight:bold;padding: 10px 24px;'>To receive your personalized newsletter, manage your newsletter preference, here.</p>
            <?php
            foreach ($user_groups->suppressions as $single_group) {
                ?>
                <div class="unsub-group td-pb-span12">
                    <div class="child-unsub-group">
                        <div class="grp-row1">
                            <h4><?php echo $single_group->name; ?></h4>
                        </div>
                        <div class="grp-row2"><p>
                            <?php echo sendgridController::SG_UNSUBSCRIBE_GROUP_DESC[$single_group->id]; ?>
                            </p>
                        </div>
                        <div class="grp-row3">
                            <!-- Default switch -->
                            <div class="custom-control custom-switch">
                                <input type="checkbox" <?php echo ($single_group->suppressed == 1 || $global_suppressed == 1) ? '' : 'checked'; ?> class="custom-control-input" id="customSwitches-<?php echo $single_group->id; ?>">
                                <label class="custom-control-label" for="customSwitches-<?php echo $single_group->id; ?>">Click to Subscribe/Un-subscribe</label>
                            </div>

                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="clearfix"></div>
        </div>
        <div class="text-center">
            <button class="btn btn-primary-border" id="unsubscribe-all-btn">Unsubscribe from all</button>
        </div>
    </div>

    
</div>
<?php
  return ob_get_clean();
}


?>
<script>
    jQuery('.custom-control-input').change(function () {
        var curr_ele = jQuery(this);
        var get_id_arr = curr_ele.attr('id').split('-');
        var group_id = get_id_arr[1];
        if (jQuery(curr_ele).prop("checked") === true) {
//            alert('Hello');
            //Toggle ONalert('unChecked');
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: "https://bizversions.com/wp-admin/admin-ajax.php",
                data: {action: 'remove_receipient_func', group_id: group_id},
                success: function (msg) {
                    if (msg.status === 'error') {

                    }
                }, error: function () {
                    console.log('Error');
                }
            });
        } else {
            //Toggle OFF
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: "https://bizversions.com/wp-admin/admin-ajax.php",
                data: {action: 'update_suppression_group_func', group_id: group_id},
                success: function (msg) {
                    if (msg.status === 'error') {

                    }
                }, error: function () {
                    console.log('Error');
                }
            });
        }
    });

    jQuery('#subscribe-all-btn').click(function () {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: "https://bizversions.com/wp-admin/admin-ajax.php",
            data: {action: 'subscribe_to_all', group_id: 'all_groups'},
            success: function (msg) {
                if (msg.status === 'success') {
                    jQuery('.custom-control-input').prop("checked", true);
                    toastr["success"]("Thanks for subscribing to all newsletters!", "Subscribe to all");

                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": true,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "10000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        "maxOpened": 1,
                        "closeOnHover": false,
                    };
                }
            }, error: function () {
                console.log('Error');
            }
        });
    });

    jQuery('#unsubscribe-all-btn').click(function () {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: "https://bizversions.com/wp-admin/admin-ajax.php",
            data: {action: 'unsubscribe_from_all'},
            success: function (msg) {
                if (msg.status === 'success') {
                    jQuery('.custom-control-input').prop("checked", false);
                    toastr["error"]("You have unsubscribed from all our email communications", "Unsubscribe from all");

                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": true,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "10000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        "maxOpened": 1,
                        "closeOnHover": false,
                    }
                }
            }, error: function () {
                console.log('Error');
            }
        });
    });
</script>