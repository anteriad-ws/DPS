<?php

add_action( 'admin_init', 'herald_buddy_compatibility' );

function herald_buddy_compatibility() {

	if ( is_admin() && current_user_can( 'activate_plugins' ) && !herald_buddy_is_theme_active() ) {

		add_action( 'admin_notices', 'herald_buddy_compatibility_notice' );

		deactivate_plugins( HERALD_BUDDY_BASENAME );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

function herald_buddy_compatibility_notice() {
	echo '<div class="notice notice-warning"><p><strong>Note:</strong> Herald Buddy plugin has been deactivated as it requires Herald Theme to be active.</p></div>';
}

function herald_buddy_is_theme_active() {
	return defined( 'HERALD_THEME_VERSION' );
}

?>
