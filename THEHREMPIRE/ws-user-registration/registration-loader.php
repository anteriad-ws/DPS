<?php
/**
 * Frontend  User Registration - File Loader
 * 
 * @version	1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load dependency files (functions etc)
require_once( USER_PATH . 'includes/cr-functions.php' );
require_once( USER_PATH . 'includes/settings/cr-settings.php');
require_once( USER_PATH . 'myaccount/navigation.php');
require_once( USER_PATH . 'myaccount/my-login-history.php');
require_once( USER_PATH . 'myaccount/my-downloads.php');
//require_once( USER_PATH . 'myaccount/my-email-preferences.php');

