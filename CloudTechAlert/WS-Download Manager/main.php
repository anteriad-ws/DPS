<?php
/**
  * Plugin Name: WS Download Manager
 * Description: The WS Download Manager is easily manage downloadable files and monitor downloads of your digital files from your WordPress site.
 * Version: 1.0
 * Author: Ramkiran and Parameswari
 * Development:  2023/July

 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_SIMPLE_DL_MONITOR_DIR_NAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'WP_SIMPLE_DL_MONITOR_URL', plugins_url( '', __FILE__ ) );
define( 'WP_SIMPLE_DL_MONITOR_PATH', plugin_dir_path( __FILE__ ) );


//File includes

require_once 'includes/custom-sdm-downloads.php'; 
require_once 'sdm-post-type-and-taxonomy.php';


//Activation hook handler
register_activation_hook( __FILE__, 'sdm_install_db_table' );

function sdm_install_db_table() {

	global $wpdb;
	$table_name = $wpdb->prefix .'custom_sdm_downloads';

	$sql = 'CREATE TABLE ' . $table_name . ' (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  post_id mediumint(9) NOT NULL,
			  post_title mediumtext NOT NULL,
			  file_url mediumtext NOT NULL,
			  visitor_ip mediumtext NOT NULL,
			  date_time datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
			  visitor_country mediumtext NOT NULL,
			  visitor_name mediumtext NOT NULL,
                          user_agent mediumtext NOT NULL,
                          referrer_url mediumtext NOT NULL,
			  UNIQUE KEY id (id)
		);';

	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
	//Register the post type so you can flush the rewrite rules
	//sdm_register_post_type();

	// Flush rules after install/activation
	//flush_rewrite_rules();
}



/*
 * * Handle Generic Init tasks
 */
add_action( 'init', 'sdm_init_time_tasks' );

function sdm_init_time_tasks() {
	//Handle download request if any
	handle_sdm_download_via_direct_post();

	//Check if the redirect option is being used
	sdm_check_redirect_query_and_settings();

	
}

function sdm_delete_data_handler() {
	if ( ! check_ajax_referer( 'sdm_delete_data', 'nonce', false ) ) {
		//nonce check failed
		wp_die( 0 );
	}
	global $wpdb;
	//let's find and delete smd_download posts and meta
	$posts = $wpdb->get_results( 'SELECT id FROM ' . $wpdb->prefix . 'posts WHERE post_type="sdm_downloads"', ARRAY_A );
	if ( ! is_null( $posts ) ) {
		foreach ( $posts as $post ) {
			wp_delete_post( $post['id'], true );
		}
	}
	//let's delete options
	delete_option( 'sdm_downloads_options' );
	
	//remove post type and taxonomies
	unregister_post_type( 'sdm_downloads' );
	unregister_taxonomy( 'sdm_categories' );
	unregister_taxonomy( 'sdm_tags' );
	//let's delete sdm_downloads table
	$wpdb->query( 'DROP TABLE ' . $wpdb->prefix . 'custom_sdm_downloads' );
	//deactivate plugin
	deactivate_plugins( plugin_basename( __FILE__ ) );
	//flush rewrite rules
	flush_rewrite_rules( false );
	echo '1';
	wp_die();
}



// Houston... we have lift-off!!
class simpleDownloadManager {

	public function __construct() {

		add_action( 'init', 'sdm_register_post_type' );  // Create 'sdm_downloads' custom post type
		add_action( 'init', 'sdm_create_taxonomies' );  // Register 'tags' and 'categories' taxonomies
		//add_action( 'init', 'sdm_register_shortcodes' ); //Register the shortcodes
		add_action( 'wp_enqueue_scripts', array( $this, 'sdm_frontend_scripts' ) );  // Register frontend scripts
		
		if ( is_admin() ) {
			//add_action( 'admin_menu', array( $this, 'sdm_create_menu_pages' ) );  // Create admin pages
			add_action( 'add_meta_boxes', array( $this, 'sdm_create_upload_metabox' ) );  // Create metaboxes

			
			add_action( 'save_post', array( $this, 'sdm_save_upload_meta_data' ) );  // Save 'upload file' metabox
			add_action( 'save_post', array( $this, 'sdm_save_dispatch_meta_data' ) );  // Save 'dispatch' metabox
			

			add_action( 'admin_enqueue_scripts', array( $this, 'sdm_admin_scripts' ) );  // Register admin scripts
			//add_action( 'admin_print_styles', array( $this, 'sdm_admin_styles' ) );  // Register admin styles

			//add_action( 'admin_init', array( $this, 'sdm_register_options' ) );  // Register admin options
			
		}
	}

	public function sdm_admin_scripts() {

		global $current_screen, $post;

		if ( is_admin() && $current_screen->post_type == 'sdm_downloads' && $current_screen->base == 'post' ) {

			// These scripts are needed for the media upload thickbox
			wp_enqueue_script( 'media-upload' );
			
			wp_register_script( 'sdm-upload', plugins_url( '', __FILE__ ) . '/js/sdm_admin_scripts.js', array( 'jquery', 'media-upload', 'thickbox' ) );
			wp_enqueue_script( 'sdm-upload' );

			// Localize langauge strings used in js file
			$sdmTranslations = array(
				'select_file'      => __( 'Select File', 'simple-download-monitor' ),
				'select_thumbnail' => __( 'Select Thumbnail', 'simple-download-monitor' ),
				'insert'           => __( 'Insert', 'simple-download-monitor' ),
				'image_removed'    => __( 'Image Successfully Removed', 'simple-download-monitor' ),
				'ajax_error'       => __( 'Error with AJAX', 'simple-download-monitor' ),
			);
			wp_localize_script( 'sdm-upload', 'sdm_translations', $sdmTranslations );
		}
	}

	public function sdm_frontend_scripts() {
		//Use this function to enqueue fron-end js scripts.
		
		wp_register_script( 'sdm-scripts', plugins_url( '', __FILE__ ) . '/js/sdm_wp_scripts.js', array( 'jquery' ) );
		wp_enqueue_script( 'sdm-scripts' );

		

		// Localize ajax script for frontend
		wp_localize_script( 'sdm-scripts', 'sdm_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}



	

	public function sdm_create_upload_metabox() {

		//*****  Create metaboxes for the custom post type
	
		add_meta_box( 'sdm_upload_meta_box', __( 'Downloadable File (Visitors will download this item)', 'simple-download-monitor' ), array( $this, 'display_sdm_upload_meta_box' ), 'sdm_downloads', 'normal', 'default' );
		add_meta_box( 'sdm_dispatch_meta_box', __( 'PHP Dispatch or Redirect', 'simple-download-monitor' ), array( $this, 'display_sdm_dispatch_meta_box' ), 'sdm_downloads', 'normal', 'default' );
		
		
		do_action( 'sdm_admin_add_edit_download_before_other_details_meta_box_action' );
		
		add_meta_box( 'sdm_shortcode_meta_box', __( 'Shortcodes', 'simple-download-monitor' ), array( $this, 'display_sdm_shortcode_meta_box' ), 'sdm_downloads', 'normal', 'default' );
	}

	

	public function display_sdm_upload_meta_box( $post ) {
		// File Upload metabox
		$old_upload = get_post_meta( $post->ID, 'sdm_upload', true );
		$old_value  = isset( $old_upload ) ? $old_upload : '';

		//Trigger filter to allow "sdm_upload" field validation override.
		$url_validation_override = apply_filters( 'sdm_file_download_url_validation_override', '' );
		if ( ! empty( $url_validation_override ) ) {
			//This site has customized the behavior and overriden the "sdm_upload" field validation. It can be useful if you are offering app download URLs (that has unconventional URL patterns).
		} else {
			//Do the normal URL validation.
			$old_value = esc_url( $old_value );
		}

		_e( 'Manually enter a valid URL of the file in the text box below, or click "Select File" button to upload (or choose) the downloadable file.', 'simple-download-monitor' );
		echo '<br /><br />';

		echo '<div class="sdm-download-edit-file-url-section">';
		echo '<input id="sdm_upload" type="text" size="100" name="sdm_upload" value="' . $old_value . '" placeholder="http://..." />';
		echo '</div>';

		echo '<br />';
		echo '<input id="upload_image_button" type="button" class="button-primary" value="' . __( 'Select File', 'simple-download-monitor' ) . '" />';

		

		wp_nonce_field( 'sdm_upload_box_nonce', 'sdm_upload_box_nonce_check' );
	}

	public function display_sdm_dispatch_meta_box( $post ) {
		$dispatch = get_post_meta( $post->ID, 'sdm_item_dispatch', true );

		if ( $dispatch === '' ) {
			// No value yet (either new item or saved with older version of plugin)
			$screen = get_current_screen();

			if ( $screen->action === 'add' ) {
				// New item: set default value as per plugin settings.
				$main_opts = get_option( 'sdm_downloads_options' );
				$dispatch  = isset( $main_opts['general_default_dispatch_value'] ) && $main_opts['general_default_dispatch_value'];
			}
		}

		echo '<input id="sdm_item_dispatch" type="checkbox" name="sdm_item_dispatch" value="yes"' . checked( true, $dispatch, false ) . ' />';
		echo '<label for="sdm_item_dispatch">' . __( 'Dispatch the file via PHP directly instead of redirecting to it. PHP Dispatching keeps the download URL hidden. Dispatching works only for local files (files that you uploaded to this site via this plugin or media library).', 'simple-download-monitor' ) . '</label>';

		wp_nonce_field( 'sdm_dispatch_box_nonce', 'sdm_dispatch_box_nonce_check' );
	}

	// Open Download in new window
	public function display_sdm_misc_properties_meta_box( $post ) {

		//Check the open in new window value
		$new_window = get_post_meta( $post->ID, 'sdm_item_new_window', true );
		if ( $new_window === '' ) {
			// No value yet (either new item or saved with older version of plugin)
			$screen = get_current_screen();
			if ( $screen->action === 'add' ) {
				//New item: we can set a default value as per plugin settings. If a general settings is introduced at a later stage.
				//Does nothing at the moment.
			}
		}

		//Check the sdm_item_disable_single_download_page value
		$sdm_item_disable_single_download_page        = get_post_meta( $post->ID, 'sdm_item_disable_single_download_page', true );
		$sdm_item_hide_dl_button_single_download_page = get_post_meta( $post->ID, 'sdm_item_hide_dl_button_single_download_page', true );

		wp_nonce_field( 'sdm_misc_properties_box_nonce', 'sdm_misc_properties_box_nonce_check' );
	}
        
	public function display_sdm_shortcode_meta_box( $post ) {
		//Shortcode metabox
		_e( 'The following shortcode can be used on posts or pages to embed a download now button for this file. You can also use the shortcode inserter (in the post editor) to add this shortcode to a post or page.', 'simple-download-monitor' );
		echo '<br />';
		$shortcode_text = '[sdm_download id="' . $post->ID . '" fancy="0"]';
		echo "<input type='text' class='code' onfocus='this.select();' readonly='readonly' value='" . $shortcode_text . "' size='40'>";
		echo '<br /><br />';

		_e( 'The following shortcode can be used to show a download counter for this item.', 'simple-download-monitor' );
		echo '<br />';
		$shortcode_text = '[sdm_download_counter id="' . $post->ID . '"]';
		echo "<input type='text' class='code' onfocus='this.select();' readonly='readonly' value='" . $shortcode_text . "' size='40'>";
	}

	public function sdm_save_upload_meta_data( $post_id ) {
		// Save File Upload metabox
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['sdm_upload_box_nonce_check'] ) || ! wp_verify_nonce( $_POST['sdm_upload_box_nonce_check'], 'sdm_upload_box_nonce' ) ) {
			return;
		}

		if ( isset( $_POST['sdm_upload'] ) ) {
			update_post_meta( $post_id, 'sdm_upload', sanitize_text_field( $_POST['sdm_upload'] ) );
		}
	}

	public function sdm_save_dispatch_meta_data( $post_id ) {
		// Save "Dispatch or Redirect" metabox
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['sdm_dispatch_box_nonce_check'] ) || ! wp_verify_nonce( $_POST['sdm_dispatch_box_nonce_check'], 'sdm_dispatch_box_nonce' ) ) {
			return;
		}
		// Get POST-ed data as boolean value
		$value = filter_input( INPUT_POST, 'sdm_item_dispatch', FILTER_VALIDATE_BOOLEAN );
		update_post_meta( $post_id, 'sdm_item_dispatch', $value );
	}


	/**
	 * Returns duplicate post URL
	 *
	 * @return string
	 */
	public function get_duplicate_url( $post_id ) {
		global $wp;
		return add_query_arg(
			array(
				'action' => 'sdm_clone_post',
				'post'   => $post_id,
				'ref'    => urlencode( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) ),
				'_nonce' => wp_create_nonce( 'sdm_downloads' ),
			),
			esc_url( admin_url( 'admin.php' ) )
		);
	}

	public function sdm_action_clone_post() {

		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sdm_clone_post' == $_REQUEST['action'] ) ) ) {
			wp_die( __( 'No post to duplicate has been supplied!', 'simple-download-monitor' ) );
		}

		/*
		* Nonce verification
		*/
		if ( ! isset( $_GET['_nonce'] ) || ! wp_verify_nonce( $_GET['_nonce'], 'sdm_downloads' ) ) {
			return;
		}

		/*
		* get the original post id
		*/
		$post_id = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		/*
		* and all the original post data then
		*/
		$post = get_post( $post_id );

		/*
		* if you don't want current user to be the new post author,
		* then change next couple of lines to this: $new_post_author = $post->post_author;
		*/
		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		* if post data exists, create the post duplicate
		*/
		if ( isset( $post ) && $post != null ) {

			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
			if ( count( $post_meta_infos ) != 0 ) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ( $post_meta_infos as $meta_info ) {
					$meta_key = $meta_info->meta_key;
					if ( $meta_key == '_wp_old_slug' ) {
						continue;
					}
					$meta_value      = addslashes( $meta_info->meta_value );
					$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query .= implode( ' UNION ALL ', $sql_query_sel );
				$wpdb->query( $sql_query );
			}

			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
			exit;
		} else {
			wp_die( __( 'Post creation failed, could not find original post: ', 'simple-download-monitor' ) . $post_id );
		}
	}

}

//End of simpleDownloadManager class
//Initialize the simpleDownloadManager class
$simpleDownloadManager = new simpleDownloadManager();

// Tinymce Button Populate Post ID's
add_action( 'wp_ajax_nopriv_sdm_tiny_get_post_ids', 'sdm_tiny_get_post_ids_ajax_call' );
add_action( 'wp_ajax_sdm_tiny_get_post_ids', 'sdm_tiny_get_post_ids_ajax_call' );

function sdm_tiny_get_post_ids_ajax_call() {

	$posts = get_posts(
		array(
			'post_type'   => 'sdm_downloads',
			'numberposts' => -1,
		)
	);
	foreach ( $posts as $item ) {
		$test[] = array(
			'post_id'    => $item->ID,
			'post_title' => $item->post_title,
		);
	}

	$response = json_encode(
		array(
			'success' => true,
			'test'    => $test,
		)
	);

	header( 'Content-Type: application/json' );
	echo $response;
	exit;
}


// Populate category tree
add_action( 'wp_ajax_nopriv_sdm_pop_cats', 'sdm_pop_cats_ajax_call' );
add_action( 'wp_ajax_sdm_pop_cats', 'sdm_pop_cats_ajax_call' );

function sdm_pop_cats_ajax_call() {

	$cat_slug  = sanitize_text_field( $_POST['cat_slug'] );  // Get button cpt slug
	$parent_id = intval( $_POST['parent_id'] );  // Get button cpt id
	// Query custom posts based on taxonomy slug
	$posts = get_posts(
		array(
			'post_type'   => 'sdm_downloads',
			'numberposts' => -1,
			'tax_query'   => array(
				array(
					'taxonomy'         => 'sdm_categories',
					'field'            => 'slug',
					'terms'            => $cat_slug,
					'include_children' => 0,
				),
			),
			'orderby'     => 'title',
			'order'       => 'ASC',
		)
	);

	$final_array = array();

	// Loop results
	foreach ( $posts as $post ) {
		// Create array of variables to pass to js
		$final_array[] = array(
			'id'        => $post->ID,
			'permalink' => get_permalink( $post->ID ),
			'title'     => $post->post_title,
		);
	}

	// Generate ajax response
	$response = json_encode( array( 'final_array' => $final_array ) );
	header( 'Content-Type: application/json' );
	echo $response;
	exit;
}

/*
 * * Setup Sortable Columns
 */
add_filter( 'manage_edit-sdm_downloads_columns', 'sdm_create_columns' ); // Define columns
add_filter( 'manage_edit-sdm_downloads_sortable_columns', 'sdm_downloads_sortable' ); // Make sortable
add_action( 'manage_sdm_downloads_posts_custom_column', 'sdm_downloads_columns_content', 10, 2 ); // Populate new columns

function sdm_create_columns( $cols ) {

	unset( $cols['title'] );
	unset( $cols['taxonomy-sdm_tags'] );
	unset( $cols['taxonomy-sdm_categories'] );
	unset( $cols['date'] );

	$cols['title']                   = __( 'Title', 'simple-download-monitor' );
	$cols['sdm_downloads_id']        = __( 'ID', 'simple-download-monitor' );
	$cols['sdm_downloads_file']      = __( 'File', 'simple-download-monitor' );
	$cols['taxonomy-sdm_categories'] = __( 'Categories', 'simple-download-monitor' );
	$cols['taxonomy-sdm_tags']       = __( 'Tags', 'simple-download-monitor' );
	$cols['sdm_downloads_count']     = __( 'Downloads', 'simple-download-monitor' );
	$cols['date']                    = __( 'Date Posted', 'simple-download-monitor' );
	return $cols;
}

function sdm_downloads_sortable( $cols ) {

	$cols['sdm_downloads_id']        = 'sdm_downloads_id';
	$cols['sdm_downloads_file']      = 'sdm_downloads_file';
	$cols['sdm_downloads_count']     = 'sdm_downloads_count';
	$cols['taxonomy-sdm_categories'] = 'taxonomy-sdm_categories';
	$cols['taxonomy-sdm_tags']       = 'taxonomy-sdm_tags';
	return $cols;
}

function sdm_downloads_columns_content( $column_name, $post_ID ) {

	
	if ( $column_name == 'sdm_downloads_id' ) {
		echo '<p class="sdm_downloads_postid">' . $post_ID . '</p>';
	}
	if ( $column_name == 'sdm_downloads_file' ) {
		$old_file = get_post_meta( $post_ID, 'sdm_upload', true );
		$file     = isset( $old_file ) ? $old_file : '--';
		echo '<p class="sdm_downloads_file">' . $file . '</p>';
	}
	if ( $column_name == 'sdm_downloads_count' ) {
		global $wpdb;
		$wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'custom_sdm_downloads WHERE post_id=%s', $post_ID ) );
		echo '<p class="sdm_downloads_count">' . $wpdb->num_rows . '</p>';
	}
}
