<?php
/*
Plugin Name: Herald Buddy
Description: A plugin which adds specific features to Herald WordPress theme.
Version: 1.0.3
Author: meks
Author URI: https://mekshq.com/
Text Domain: herald-buddy
Domain Path: /languages
*/

/* Prevent direct access */
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/* Define */
define( 'HERALD_BUDDY_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'HERALD_BUDDY_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'HERALD_BUDDY_BASENAME', plugin_basename( __FILE__ ) );

define( 'HERALD_BUDDY_VER', '1.0.3' );


/* Compatibility checks */
require_once HERALD_BUDDY_DIR . 'inc/compat.php';

/* Register widgets */
require_once HERALD_BUDDY_DIR . 'inc/widgets.php';

if ( is_admin() ) {

	/* Update API */
	require_once HERALD_BUDDY_DIR . 'inc/update.php';

}

/* Load text domain */
add_action( 'plugins_loaded', 'herald_buddy_text_domain' );

function herald_buddy_text_domain() {
	load_plugin_textdomain( 'herald-buddy', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

?>