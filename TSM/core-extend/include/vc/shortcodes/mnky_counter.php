<?php
$output = '';

	extract(shortcode_atts(array(
		'el_class' => '',
		'from' => '0',
		'to' => '1000',
		'speed' => '2000',
		'interval' => '10',
		'decimals' => '0'
	), $atts));
	
	wp_enqueue_script( 'vc_waypoints' );
	wp_enqueue_script( 'CountTo', MNKY_PLUGIN_URL . 'assets/js/countTo.js', array( 'vc_waypoints' ));
	
	$el_class = $this->getExtraClass($el_class);
	
	$output .= '<div class="counter_wrapper '. esc_attr( trim($el_class ) ) .'"><span class="count_data" data-count-from="'. esc_attr( $from ) .'" data-count-to="'. esc_attr( $to ) .'" data-count-speed="'.esc_attr( $speed ).'" data-count-interval="'. esc_attr( $interval) .'" data-count-decimals="'. esc_attr( $decimals ) .'">'. esc_html( $from ) .'</span></div>';

echo $output;
