<?php
/**
 * Plugin Name: Ws User Registartion
 * Description: user registration form and login form builder.
 * Version: 1.0
 * Author: Elumalai
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'USER_FILE', __FILE__ );
define( 'USER_PATH', plugin_dir_path( __FILE__ ) );
define( 'USER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Require main plugin loader
require_once( USER_PATH . 'registration-loader.php' );