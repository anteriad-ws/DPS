<?php
/*	
*	---------------------------------------------------------------------
*	MNKY Demo data importer
*	--------------------------------------------------------------------- 
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) die;


// Include importer in back-end
add_action( 'admin_init', 'mnky_importer' );
function mnky_importer() {
    global $wpdb;

    if ( current_user_can( 'manage_options' ) && isset( $_GET['import_data_content'] ) ) {
		
		// Define importer
        if ( ! defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); 

		// Load Importer API
		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require $class_wp_importer;
		}

        if ( ! class_exists('WP_Import') ) {
            $wp_import = MNKY_PLUGIN_PATH . 'include/importer/wordpress-importer.php';
            require $wp_import;
        }
		

        if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {

            $importer = new WP_Import();
            
			// Import demo data
            $theme_xml = MNKY_PLUGIN_PATH . 'include/importer/data/theme_data.xml';
            $importer->fetch_attachments = true;
            ob_start();
            $importer->import($theme_xml);
            ob_end_clean();

			
            // Assign imported menus
            $locations = get_theme_mod( 'nav_menu_locations' );
            $registred_menus = wp_get_nav_menus();

            if( $registred_menus ) {
                foreach( $registred_menus as $menu ) {
                    if( $menu->name == 'Main Menu' ) {
                        $locations['primary'] = $menu->term_id;
                    }
					if( $menu->name == 'Secondary Menu' ) {
                        $locations['secondary'] = $menu->term_id;
                    }
					if( $menu->name == 'Mobile Menu' ) {
                        $locations['mobile'] = $menu->term_id;
                    }
                }
            }

            set_theme_mod( 'nav_menu_locations', $locations );


            // Add widgets to sidebars
            $widget_data = MNKY_PLUGIN_PATH . 'include/importer/data/widget_data.wie';
			mnky_process_import_file( $widget_data );


            // Change "Settings/Reading" options
            $front_page = get_page_by_title( 'Home' );
            $posts_page = get_page_by_title( 'All Articles' );
            if($front_page->ID && $posts_page->ID) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $front_page->ID); // Set front page
                update_option('page_for_posts', $posts_page->ID); // Set blog Page
            }

            // Redirect to success page
            wp_redirect( admin_url( 'themes.php?page=import_demo_data#success' ) );
        }
    }
}


// Widget Importer & Exporter (http://wordpress.org/plugins/widget-importer-exporter/)
function mnky_process_import_file( $widget_data ) {

	// Get file and decode
	$data = file_get_contents( $widget_data );
	$data = json_decode( $data );

	// Import the widget data
	mnky_import_data( $data );

}

// Available widgets
function mnky_available_widgets() {

	global $wp_registered_widget_controls;

	$widget_controls = $wp_registered_widget_controls;
	$available_widgets = array();

	foreach ( $widget_controls as $widget ) {
		if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes
			$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
			$available_widgets[$widget['id_base']]['name'] = $widget['name'];
		}
	}

	return apply_filters( 'mnky_available_widgets', $available_widgets );
}

// Import widget JSON data
function mnky_import_data( $data ) {

	global $wp_registered_sidebars;

	$data = apply_filters( 'mnky_import_data', $data );

	// Get all available widgets site supports
	$available_widgets = mnky_available_widgets();

	// Get all existing widget instances
	$widget_instances = array();
	foreach ( $available_widgets as $widget_data ) {
		$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
	}

	// Begin results
	$results = array();

	// Loop import data's sidebars
	foreach ( $data as $sidebar_id => $widgets ) {

		// Skip inactive widgets
		// (should not be in export file)
		if ( 'wp_inactive_widgets' == $sidebar_id ) {
			continue;
		}

		// Check if sidebar is available on this site
		// Otherwise add widgets to inactive, and say so
		if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
			$sidebar_available = true;
			$use_sidebar_id = $sidebar_id;
			$sidebar_message_type = 'success';
			$sidebar_message = '';
		} else {
			$sidebar_available = false;
			$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
			$sidebar_message_type = 'error';
			$sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'wordpress-importer' );
		}

		// Result for sidebar
		$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
		$results[$sidebar_id]['message_type'] = $sidebar_message_type;
		$results[$sidebar_id]['message'] = $sidebar_message;
		$results[$sidebar_id]['widgets'] = array();

		// Loop widgets
		foreach ( $widgets as $widget_instance_id => $widget ) {

			$fail = false;

			// Get id_base (remove -# from end) and instance ID number
			$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
			$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

			// Does site support this widget?
			if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
				$fail = true;
				$widget_message_type = 'error';
				$widget_message = __( 'Site does not support widget', 'wordpress-importer' ); // explain why widget not imported
			}

			// Filter to modify settings before import
			// Do before identical check because changes may make it identical to end result (such as URL replacements)
			$widget = apply_filters( 'mnky_widget_settings', $widget );

			// Does widget with identical settings already exist in same sidebar?
			if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

				// Get existing widgets in this sidebar
				$sidebars_widgets = get_option( 'sidebars_widgets' );
				$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

				// Loop widgets with ID base
				$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
				foreach ( $single_widget_instances as $check_id => $check_widget ) {

					// Is widget in same sidebar and has identical settings?
					if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

						$fail = true;
						$widget_message_type = 'warning';
						$widget_message = __( 'Widget already exists', 'wordpress-importer' ); // explain why widget not imported

						break;

					}
	
				}

			}

			// No failure
			if ( ! $fail ) {

				// Add widget instance
				$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
				$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
				$single_widget_instances[] = (array) $widget; // add it

					// Get the key it was given
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					// If key is 0, make it 1
					// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;
						$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					// Move _multiwidget to end of array for uniformity
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					// Update option with new widget
					update_option( 'widget_' . $id_base, $single_widget_instances );

				// Assign widget instance to sidebar
				$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
				$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
				$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
				update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

				// Success message
				if ( $sidebar_available ) {
					$widget_message_type = 'success';
					$widget_message = __( 'Imported', 'wordpress-importer' );
				} else {
					$widget_message_type = 'warning';
					$widget_message = __( 'Imported to Inactive', 'wordpress-importer' );
				}

			}

			// Result for widget instance
			$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
			$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = $widget->title ? $widget->title : __( 'No Title', 'wordpress-importer' ); // show "No Title" if widget instance is untitled
			$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
			$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

		}
	}
}



// Build import page
if ( ! function_exists( 'mnky_import_demo_data' ) ) { 
	function mnky_import_demo_data() { 
	?>
	
	<script>
	jQuery(document).ready(function($) {
		"use strict";
		
		// Import button loading
		$('.mnky_import_demo').click(function () {
			$(this).addClass('importing_demo');
			$('.mnky_import_demo span').delay(400).fadeOut(function() {
				$(this).text('Please Wait!').fadeIn(400);
			});	
		});
		
		if (window.location.hash == "#success") {
			$('.import-demo-wrap').hide();
			$('.import-successful-wrap').show();
		}

		
	});
	</script>
	
	<style>
		/* Import demo section */
		.import-demo-wrap{background:#fff; max-width:800px; margin:60px auto; padding:80px; text-align:center;  border-radius:4px;  box-shadow:0 1px 3px rgba(0,0,0,.13);}
		.import-demo-wrap h1{font-size: 50px; font-weight:normal; color:#585858; line-height:1.6; margin:0 0 10px;}
		.import-demo-wrap a{position:relative; display:inline-block; min-width: 300px; height: 60px; background-color:#2ecc71; color: #fff; font-weight:600; text-align: center; line-height: 60px; font-size: 16px; cursor: pointer; border-radius: 3px; text-decoration:none; overflow: hidden;}
		.import-demo-wrap a:hover{background-color:#27ae60;}
		.import-demo-wrap span.import_note{font-size: 14px; font-weight:normal; color:#B0ADAD; line-height:1.6; margin:0 0 60px; display:block;}
		.import-demo-wrap span.import_note.import_warning{font-size:12px; margin:0px;}
		.import-demo-wrap .info{font-size: 15px; font-weight: 600; padding-bottom:25px}
		.more-wrap {margin-top:60px;}
		.more-wrap .more_column {float:left; width:32.3%; margin-right:1.5%;}
		.more-wrap .more_column.last {margin-right:0}
		.more-wrap .more_column .column_title {font-size:18px; font-weight:600; margin-bottom:20px;}
		.more-wrap .mnky_small_button {position:relative; display:inline-block; margin-top:20px; min-width: 170px; height:auto; background-color:#2ecc71; color: #fff; font-weight:600; text-align: center; line-height: 40px; font-size: 13px; border-radius: 3px; text-decoration:none; overflow: hidden;}
		.more-wrap .mnky_small_button:hover {background-color:#27ae60;}
		.clearfix:before, .clearfix:after { content: "\0020"; display: block; height: 0; visibility: hidden;	} 
		.clearfix:after { clear: both; }
		.clearfix { zoom: 1; }

		/* Import demo successful */
		.import-successful-wrap {display:none; background:#fff; max-width:800px; margin:60px auto; padding:80px; text-align:center;  border-radius:4px;  box-shadow:0 1px 3px rgba(0,0,0,.13);}
		.import-successful-wrap h1{font-size: 40px; font-weight:normal; color:#585858; line-height:1.6; margin: 30px 0px 60px;}
		.import-successful-wrap .success-icon:before{font-size:200px; content: "\f328"; color:#C4C4C4; display: inline-block; font-family: "dashicons"; font-style: normal; font-weight: 400; line-height: 1;}

		/* Importing data loader */
		.loading {position:absolute; top:16px; right:-55px;  margin: 0 auto; width: 20px; height: 20px; border-radius: 50%; border: solid 4px rgba(255, 255, 255, 0.2); border-top-color: #FFF;  -webkit-animation: spin 1s infinite linear; -moz-animation: spin 1s infinite linear; animation: spin 1s infinite linear;
		}

		.importing_demo .loading {right:16px; -webkit-transition: right 0.5s ease-in-out; -moz-transition: right 0.5s ease-in-out; transition: right 0.5s ease-in-out;}
		
		/* Loader animation */
		@-webkit-keyframes spin { 
		  100% { 
			-webkit-transform: rotate(360deg); 
		  } 
		}

		@-moz-keyframes spin { 
		  100% { 
			transform: rotate(360deg); 
		  } 
		}

		@keyframes spin { 
		  100% { 
			transform: rotate(360deg); 
		  } 
		}
		
		@media only screen and (max-width: 979px) {
		.more-wrap .more_column {width:100%; margin:0 0 40px 0; float:none;}
		.more-wrap .more_column span {display:block;}
		.import-demo-wrap, .import-successful-wrap {padding:20px;}
		}
		
	</style>

	<div class="import-demo-wrap">
		<h1><?php esc_html_e('Welcome to Bitz!', 'core-extend')?></h1>
		<span class="import_note"><?php	esc_html_e( 'Replicate live preview website by importing demo data. You can change and edit everything later.', 'core-extend' ) ?></span>
		<a class="mnky_import_demo" href="<?php echo esc_url('themes.php?page=import_demo_data&import_data_content=true', 'core-extend')?>"><span><?php esc_html_e('Import demo data', 'core-extend')?></span><div class="loading"></div></a>
		<hr style="margin-top:60px;">
		<span class="import_note import_warning"><?php esc_html_e('NOTICE: Images and graphics used in live demo that are licensed for preview purposes only are replaced with placeholder images.', 'core-extend')?></span>
		<hr>
		
		<div class="more-wrap clearfix">
		<div class="more_column" >
		<div class="column_title"><?php esc_html_e('Documentation', 'core-extend')?></div>
		<span><?php esc_html_e('Read online documentation to learn everything about the theme.', 'core-extend')?></span>
		<a class="mnky_small_button" href="<?php echo esc_url('http://www.mnkystudio.com/bitz-documentation/') ?>" target="_blank"><span><?php esc_html_e('Read documentation', 'core-extend')?></span></a>
		</div>
		<div class="more_column" >
		<div class="column_title"><?php esc_html_e('Theme options', 'core-extend')?></div>
		<span><?php esc_html_e('Go to theme options panel to adjust theme settings.', 'core-extend')?></span>
		<a class="mnky_small_button" href="<?php echo esc_url('themes.php?page=ot-theme-options') ?>"><span><?php esc_html_e('Customize theme', 'core-extend')?></span></a>
		</div>
		<div class="more_column last" >
		<div class="column_title"><?php esc_html_e('Help desk', 'core-extend')?></div>
		<span><?php esc_html_e('Visit our support forums to ask questions and get the answers.', 'core-extend')?></span>
		<a class="mnky_small_button" href="<?php echo esc_url('http://support.mnkystudio.com/categories/bitz') ?>" target="_blank"><span><?php esc_html_e('Receive support', 'core-extend')?></span></a>
		</div>
		</div>
	</div>

	<div class="import-successful-wrap">
		<div class="success-icon"></div>
		<h1><?php esc_html_e('Data Imported Successfully!', 'core-extend')?></h1>
		<hr>
		<div class="more-wrap clearfix">
		<div class="more_column" >
		<div class="column_title"><?php esc_html_e('Documentation', 'core-extend')?></div>
		<div><?php esc_html_e('Read online documentation to learn everything about the theme.', 'core-extend')?></div>
		<a class="mnky_small_button" href="<?php echo esc_url('http://www.mnkystudio.com/bitz-documentation/') ?>" target="_blank"><span><?php esc_html_e('Read documentation', 'core-extend')?></span></a>
		</div>
		<div class="more_column" >
		<div class="column_title"><?php esc_html_e('Theme options', 'core-extend')?></div>
		<div><?php esc_html_e('Go to theme options panel to adjust theme settings.', 'core-extend')?></div>
		<a class="mnky_small_button" href="<?php echo esc_url('themes.php?page=ot-theme-options') ?>"><span><?php esc_html_e('Customize theme', 'core-extend')?></span></a>
		</div>
		<div class="more_column last" >
		<div class="column_title"><?php esc_html_e('See result', 'core-extend')?></div>
		<div><?php esc_html_e('Visit your home page to see imported demo content.', 'core-extend')?></div>
		<a class="mnky_small_button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank"><span><?php esc_html_e('Visit homepage', 'core-extend')?></span></a>
		</div>
		</div>
	</div>
	

	<?php	
	}	
}	