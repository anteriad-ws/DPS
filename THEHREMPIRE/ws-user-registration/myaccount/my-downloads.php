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
?>


<?php
// Add shortcode to display the login history
function download_history_shortcode($atts) {
    
    ob_start();
    ?>
    <table id="downloadHistorynew" class="display" style="width:100%">
        <thead>
            <tr>
                                <th width="30%">Asset Name</th>
                                <th width="30%">Asset Type</th>
                                <th width="30%">Category</th>
                                <th width="15%">IP Address</th>
                                <th width="10%">Location</th>
                                <th width="20%">Download On</th>
            </tr>
        </thead>
    </table>
    
    <script>
        jQuery(document).ready(function ()  {
            $('#downloadHistorynew').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    
                    url:  'https://staging.bizversions.com/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        'action': 'download_history_ajax',   
                    }
                },
                columns: [
                    { data: 'post_title'},
                    { data: 'resource_type'},
                    { data: 'category_name'},
                    { data: 'visitor_ip'},
                    { data: 'visitor_country'},
                    { data: 'date_time'}
                ]
                
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

?>


