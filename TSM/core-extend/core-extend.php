<?php
/*
Plugin Name: Bitz | Theme Core Extend
Plugin URI: http://themeforest.net/user/MNKY
Description: Extend Theme and Visual Composer features.
Version: 1.2.2
Author: MNKY
Author URI: http://mnkythemes.com/
License: Envato Marketplaces Split Licence
License URI: Envato Marketplace Item License Certificate 
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) die;


class MNKY_Core_Extend {
	
	function __construct() {
		require_once ( 'include/aq_resizer.php' );
		require_once ( 'include/Mobile_Detect.php' );
		require_once ( 'include/ads_post_type.php' );
		require_once ( 'include/header_post_type.php' );
		require_once ( 'include/importer/importer.php' );
		$this->_constants();
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}
	
	protected function _constants() {
		define( 'MNKY_PLUGIN_MAIN', __FILE__);
		define( 'MNKY_PLUGIN_PATH', plugin_dir_path(__FILE__) );
		define( 'MNKY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}
	
	public function init() { 
		load_plugin_textdomain( 'core-extend', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 

		if ( ! function_exists( 'vc_map' ) ) {
			add_action('admin_notices', array( $this, 'vc_error' ) ); 
		} else {
			$this->vc_edit();
			add_action('wp_enqueue_scripts', array( $this, 'vc_scripts' ) );
			add_action( 'init', array( $this, 'remove_vc_options' ) );

			// Remove VC welcome screen and about page
			remove_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
			remove_action( 'init', 'vc_page_welcome_redirect' );
			remove_action( 'vc_menu_page_build', 'vc_page_welcome_add_sub_page', 11 );
			
			// Remove VC pointers
			if ( is_admin() ) {
				foreach ( vc_editor_post_types() as $post_type ) {
					remove_filter( 'vc_ui-pointers-' . $post_type, 'vc_backend_editor_register_pointer' );
				}
			}	
		}
	}

	// Display notice if Visual Composer is not installed or activated
	public function vc_error() {
		echo '
		<div class="updated">
			<p>'. sprintf (esc_html_x( '%1$s MNKY | Theme Core Extend %2$s requires Visual Composer plugin to be installed and activated on your site.', '%1$s and %2$s stand for <strong> tags.' ,'core-extend' ), '<strong>', '</strong>') .'</p>
		</div>';
	}
	
	// Enqueue scripts
	public function vc_scripts() {
		wp_register_style( 'core-extend', MNKY_PLUGIN_URL . 'assets/css/core-extend.css', array('js_composer_front') );
		
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'core-extend' );
	}
	
	// Disable VC design options
	public function remove_vc_options() {
		vc_set_as_theme($disable_updater = true);
	}
	
	// Extend & configure VC	
	public function vc_edit() { 
		
		// Set shortcode template dir
		$dir = MNKY_PLUGIN_PATH . '/include/vc/shortcodes/';
		vc_set_shortcodes_templates_dir($dir);
					
		// Add params
		require_once ('include/vc/params.php');

		// Add shortcodes
		require_once ('include/vc/classes/team.php');
		require_once ('include/vc/classes/testimonials.php');
		require_once ('include/vc/classes/list.php');
		require_once ('include/vc/classes/service.php');	
		require_once ('include/vc/classes/counter.php');	
		require_once ('include/vc/classes/pricing-box.php');	
		require_once ('include/vc/classes/countdown.php');	
		require_once ('include/vc/classes/icons.php');	
		require_once ('include/vc/classes/button.php');	
		require_once ('include/vc/classes/progress-bar.php');		
		require_once ('include/vc/classes/posts.php');	
		require_once ('include/vc/classes/menu-posts.php');	
		require_once ('include/vc/classes/posts-grid.php');	
		require_once ('include/vc/classes/ads.php');	
		require_once ('include/vc/classes/heading.php');	
		require_once ('include/vc/classes/article-info.php');	
		require_once ('include/vc/classes/related-posts.php');	
		
		// Edit VC map
		require_once ('include/vc/map.php');
		
		// Add templates
		require_once ('include/vc/templates.php');
	}
}
$mnky_core_extend = new MNKY_Core_Extend();