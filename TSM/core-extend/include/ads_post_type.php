<?php
if ( ! function_exists('mnky_ads_post_type') ) {

	// Register Custom Post Type
	function mnky_ads_post_type() {

		$labels = array(
			'name'                => esc_html_x( 'Ads', 'Post Type General Name', 'core-extend' ),
			'singular_name'       => esc_html_x( 'Ad', 'Post Type Singular Name', 'core-extend' ),
			'menu_name'           => esc_html__( 'Ads', 'core-extend' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'core-extend' ),
			'all_items'           => esc_html__( 'All Ads', 'core-extend' ),
			'view_item'           => esc_html__( 'View Item', 'core-extend' ),
			'add_new_item'        => esc_html__( 'Add New Item', 'core-extend' ),
			'add_new'             => esc_html__( 'Add New', 'core-extend' ),
			'edit_item'           => esc_html__( 'Edit Item', 'core-extend' ),
			'update_item'         => esc_html__( 'Update Item', 'core-extend' ),
			'search_items'        => esc_html__( 'Search Item', 'core-extend' ),
			'not_found'           => esc_html__( 'Not found', 'core-extend' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'core-extend' ),
		);
		$args = array(
			'label'               => esc_html__( 'ad-options', 'core-extend' ),
			'description'         => esc_html__( 'Ad Post Type', 'core-extend' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor'),
			'taxonomies'          => array( 'ads_category' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon' => 'dashicons-megaphone',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite' => array('slug' => esc_html_x( 'site-commerce', 'Ads post type slug in permalink', 'core-extend' )),
			'capability_type'     => 'post',
		);
		register_post_type( 'ads', $args );

	}

	// Hook into the 'init' action portfolio post type
	add_action( 'init', 'mnky_ads_post_type', 0 );



	function mnky_ads_taxonomies() {
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => esc_html_x( 'Categories', 'taxonomy general name', 'core-extend' ),
			'singular_name'     => esc_html_x( 'Category', 'taxonomy singular name', 'core-extend' ),
			'menu_name'         => esc_html__( 'Categories', 'core-extend' ),
			'search_items'      => esc_html__( 'Search Categories', 'core-extend' ),
			'all_items'         => esc_html__( 'All Categories', 'core-extend' ),
			'parent_item'       => esc_html__( 'Parent Category', 'core-extend' ),
			'parent_item_colon' => esc_html__( 'Parent Category:', 'core-extend' ),
			'edit_item'         => esc_html__( 'Edit Category', 'core-extend' ),
			'update_item'       => esc_html__( 'Update Category', 'core-extend' ),
			'add_new_item'      => esc_html__( 'Add New Category', 'core-extend' ),
			'new_item_name'     => esc_html__( 'New Category Name', 'core-extend' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus'   => false,
		);

		register_taxonomy( 'ads_category', array( 'ads' ), $args );
	}

	// Hook into the 'init' action portfolio taxonomies
	add_action( 'init', 'mnky_ads_taxonomies', 0 );



	function mnky_ads_columns( $gallery_columns ) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = esc_html__('Title', 'core-extend' ); 
		$new_columns['shortcode'] = esc_html__('Shortcode', 'core-extend' ); 
		$new_columns['ads_category'] = esc_html__('Categories', 'core-extend' );
		$new_columns['date'] = esc_html__('Date', 'core-extend' );
		 
		return $new_columns;
	}

	// Add filter for portfolio custom columns
	add_filter('manage_edit-ads_columns', 'mnky_ads_columns');

	 
	function mnky_manage_ads_columns( $column_name ) {
		global $post;
		
		switch ($column_name) {
			
			/* If displaying the 'Shortcode' column. */
			case 'shortcode' :
				echo '[mnky_ads id="'. $post->ID .'"]';
				break;
				
			/* If displaying the 'Categories' column. */
			case 'ads_category' :

				$terms = get_the_terms( $post->ID, 'ads_category' );

				if ( !empty( $terms ) ) {

					$out = array();
					foreach ( $terms as $term ) {
						$out[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'ads_category' => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'ads_category', 'display' ) )
						);
					}
					/* Join the terms, separating them with a comma. */
					echo join( ', ', $out );
				}

				/* If no terms were found, output a default message. */
				else {
					echo '&macr;';
				}

				break;

			/* Just break out of the switch statement for everything else. */
			default :
				break;
			break;
		
		} 
	}	

	// Add filter for portfolio custom column view
	add_action('manage_ads_posts_custom_column', 'mnky_manage_ads_columns', 10, 2);
}