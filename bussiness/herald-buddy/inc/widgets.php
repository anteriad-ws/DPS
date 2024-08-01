<?php

/**
 * Register widgets
 *
 * Callback function which includes widget classes and initialize theme specific widgets
 *
 * @since  1.0
 */

add_action( 'widgets_init', 'herald_register_widgets' );


function herald_register_widgets() {
		
		include_once HERALD_BUDDY_DIR . 'inc/widgets/posts.php';
		include_once HERALD_BUDDY_DIR . 'inc/widgets/video.php';
		include_once HERALD_BUDDY_DIR . 'inc/widgets/adsense.php';

		register_widget('HRD_Posts_Widget');
		register_widget('HRD_Video_Widget');
		register_widget('HRD_Adsense_Widget');
}


?>