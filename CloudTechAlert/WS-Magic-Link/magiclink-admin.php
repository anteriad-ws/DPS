<?php
/**
 * Author: Elumalai 
 * 
 * License: GPLv2 or later
 * 
 */
require_once 'magiclink.php';
require_once 'magiclink_setting.php';
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}
if ( isset( $_POST['Clear'] ) ) {
	ca_clear_cache();
	?>
<div class="updated">
	<p>
		<strong><?php _e('Cleared Cache.', 'magic-link' ); ?></strong>
	</p>
</div>
<?php
}
?>
<div class="updated">
	<p>
		<strong><?php _e('Options saved.', 'clicky-analytics'); ?></strong>
	</p>
</div>