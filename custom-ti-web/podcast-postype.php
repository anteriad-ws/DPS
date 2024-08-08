<?php

	function register_postType_ti(){
	
		$labels = [
			"name" => __("Podcast", "bitz"),
			"singular_name" => __("Podcast", "bitz"),
			"menu_name" => __("Podcasts", "bitz"),
			"all_items" => __("All Podcasts", "bitz"),
			"add_new" => __("Add Podcast", "bitz"),
			"add_new_item" => __("Add new Podcast", "bitz"),
			"edit_item" => __("Edit Podcast", "bitz"),
			"new_item" => __("New Podcast", "bitz"),
			"view_item" => __("View Podcast", "bitz"),
			"view_items" => __("View Podcasts", "bitz"),
			"search_items" => __("Search Podcasts", "bitz"),
			"not_found" => __("No Podcasts found", "bitz"),
			"not_found_in_trash" => __("No Podcasts found in trash", "bitz"),
			"parent" => __("Parent Podcasts:", "bitz"),
			"featured_image" => __("Featured image for this Podcast", "bitz"),
			"set_featured_image" => __("Set featured image for this Podcast", "bitz"),
			"remove_featured_image" => __("Remove featured image for this Podcast", "bitz"),
			"use_featured_image" => __("Use as featured image for this Podcast", "bitz"),
			"archives" => __("Podcasts archives", "bitz"),
			"insert_into_item" => __("Insert into Podcasts", "bitz"),
			"uploaded_to_this_item" => __("Upload to this Podcasts", "bitz"),
			"filter_items_list" => __("Filter Podcasts list", "bitz"),
			"items_list_navigation" => __("Podcasts list navigation", "bitz"),
			"items_list" => __("Podcasts list", "bitz"),
			"attributes" => __("Podcast attributes", "bitz"),
			"name_admin_bar" => __("Podcasts", "bitz"),
			"item_published" => __("Podcast published", "bitz"),
			"item_published_privately" => __("Podcast published privately.", "bitz"),
			"item_reverted_to_draft" => __("Podcast reverted to draft.", "bitz"),
			"item_scheduled" => __("Podcast scheduled", "bitz"),
			"item_updated" => __("Podcast updated.", "bitz"),
			"parent_item_colon" => __("Parent Podcast:", "bitz"),
		];

		$args = [
			"label" => __("Podcasts", "bitz"),
			"labels" => $labels,
			"description" => "",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive" => TRUE,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => ["slug" => "podcasts", "with_front" => true],
			"query_var" => true,
			"supports" => ["title", "editor", "thumbnail", "excerpt", "custom-fields", "author", "post-formats"],
			//"taxonomies" => ["post_tag", "geo-location", "new_categories", "category", "sponsored_by"],
			"taxonomies" => ["post_tag", "geo-location", "resource_types", "category", "sponsored_by"],
		];

		register_post_type("podcasts", $args);
	
	}
	add_action('init', 'register_postType_ti');

?>