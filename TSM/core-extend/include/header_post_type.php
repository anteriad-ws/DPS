<?php
if ( ! function_exists('mnky_header_post_type') ) {

// Register Custom Post Type
function mnky_header_post_type() {

	$labels = array(
		'name'                => esc_html_x( 'Custom Headers', 'Post Type General Name', 'bitz' ),
		'singular_name'       => esc_html_x( 'Custom Header', 'Post Type Singular Name', 'bitz' ),
		'menu_name'           => esc_html__( 'Custom Headers', 'bitz' ),
		'name_admin_bar'      => esc_html__( 'Custom Header', 'bitz' ),
		'parent_item_colon'   => esc_html__( 'Parent Item:', 'bitz' ),
		'all_items'           => esc_html__( 'All Headers', 'bitz' ),
		'add_new_item'        => esc_html__( 'Add New Header', 'bitz' ),
		'add_new'             => esc_html__( 'Add New', 'bitz' ),
		'new_item'            => esc_html__( 'New Item', 'bitz' ),
		'edit_item'           => esc_html__( 'Edit Item', 'bitz' ),
		'update_item'         => esc_html__( 'Update Item', 'bitz' ),
		'view_item'           => esc_html__( 'View Item', 'bitz' ),
		'search_items'        => esc_html__( 'Search Item', 'bitz' ),
		'not_found'           => esc_html__( 'Not found', 'bitz' ),
		'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'bitz' ),
	);
	$args = array(
		'label'               => esc_html__( 'Custom Header', 'bitz' ),
		'description'         => esc_html__( 'Create custom header styles.', 'bitz' ),
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-schedule',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,		
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'custom_headers', $args );

}
add_action( 'init', 'mnky_header_post_type', 0 );

}